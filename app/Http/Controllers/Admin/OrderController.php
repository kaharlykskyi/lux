<?php

namespace App\Http\Controllers\Admin;

use App\{Cart,
    CartProduct,
    MutualSettlement,
    OderStatus,
    OrderPay,
    Product,
    Provider,
    Services\Admin\Order,
    TecDoc\Tecdoc,
    User,
    UserBalance,
    Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $service;

    protected $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->service = new Order();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        if (isset($request->delete_oder)){
            Cart::destroy((int)$request->delete_oder);
            return back();
        }
        $orders = $this->service->getOrders($request);
        $order_code = OderStatus::all();
        $clients = User::all();
        $suppliers = $this->tecdoc->getAllSuppliers();
        $manufacturers = $this->tecdoc->getBrands();
        return view('admin.orders.index',compact('orders','order_code','clients','suppliers','manufacturers'));
    }

    public function getOrderData(Request $request){
        return response()->json([
            'response' => $this->service->getOrderData($request)
        ]);
    }

    public function getInfoProductStock(Request $request){
        return response()->json([
            'response' => Product::where('articles',$request->article)->get()
        ]);
    }

    public function changeStatusOrder(Request $request){
        if (isset($request->invoice)){
            DB::table('carts')->where('id',$request->orderID)
                ->update([
                    'invoice_np' => $request->invoice
                ]);

            return response()->json([
                'response' => 'Номер накладной сохранён'
            ]);
        }

        DB::table('carts')->where('id',$request->orderID)
            ->update([
                'oder_status' => (int)$request->statusID
            ]);

        if ((int)$request->statusID === 5){
            $order = DB::table('carts')->where('id',$request->orderID)->first();
            $order_pay = OrderPay::where('cart_id',$order->id)->first();
            $user_balance = UserBalance::where('user_id',(int)$order->user_id)->first();
            if (isset($order_pay) && isset($user_balance)){
                DB::transaction(function () use ($order_pay, $order, $user_balance) {
                    $mutual_settelement = new MutualSettlement();
                    $mutual_settelement->fill([
                        'description' => 'Возврат по заказу №' . $order->id,
                        'type_operation' => 3,
                        'user_id' => (int)$order->user_id,
                        'currency' => 'UAH',
                        'change' => $order_pay->price_pay,
                        'balance' => (float)$user_balance->balance + (float)$order_pay->price_pay
                    ]);
                    $mutual_settelement->save();
                    UserBalance::where('id',$user_balance->id)->update(['balance' => round((float)$user_balance->balance + (float)$order_pay->price_pay,2)]);
                },5);

            }
        }
        Cart::where('id',(int)$request->orderID)->update(['seen' => 1]);
        return response()->json([
            'response' => 'Статус заказа изменен'
        ]);
    }

    public function editOder(Request $request){

        if($request->isMethod('post')){
            $mass = '';
            if ($request->has('id') && $request->has('count')){
                if (CartProduct::where([['cart_id',$request->order],['product_id',$request->id]])->exists()){
                    CartProduct::where([
                        ['cart_id',$request->order],
                        ['product_id',$request->id]
                    ])->increment('count',$request->count);

                    $mass = 'Товар обновлён';
                } else {
                    $cart_product = new CartProduct();
                    $cart_product->fill([
                        'cart_id' => $request->order,
                        'product_id' => $request->id,
                        'count' => $request->count
                    ]);
                    if ($cart_product->save()){
                        $mass = 'Товар добавлен';
                    }else{
                        $mass = 'Произошла ошибка';
                    }
                }
            } elseif ($request->has('delete_product')){
                CartProduct::where('id',$request->delete_product)->delete();
                $mass = 'Товар удалён';
            }

            return response()->json([
                'mass' => $mass,
                'products' => CartProduct::with('product')->where('cart_id',$request->order)->get()
            ]);
        }

        Cart::where('id',(int)$request->order)->update(['seen' => 1]);
        $order = Cart::with(['cartProduct','status','client' =>
            function($query){
                $query->with(['type_user','deliveryInfo']);
            }
            ,'payOder'])->find((int)$request->order);
        $order_code = DB::table('oder_status_codes')->get();
        $providers = Provider::all();

        return view('admin.orders.edit_order',compact('order','order_code','providers'));
    }

    public function searchProduct(Request $request){
        $filter = [];

        if (isset($request->provider) && !empty($request->provider)) $filter[] = ['provider_id','=',(int)$request->provider];
        if (isset($request->name) && !empty($request->name)) $filter[] = ['name','LIKE',"%{$request->name}%"];
        if (isset($request->article) && !empty($request->article)) $filter[] = ['articles','LIKE',"%{$request->article}%"];
        if (isset($request->supplier) && !empty($request->supplier)) $filter[] = ['brand','=',$request->supplier];
        if (isset($request->count) && !empty($request->count)) $filter[] = ['count','>=',(int)$request->count];

        $original = Product::where($filter)->where('products.original','=',1)
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.manufacturers AS m'),'m.id','=','products.brand')
            ->select('products.*','m.matchcode')->get();

        $no_original = Product::where($filter)->where('products.original','=',0)
            ->join(DB::raw(config('database.connections.mysql_tecdoc.database').'.suppliers AS sp'),'sp.id','=','products.brand')
            ->select('products.*','sp.matchcode')->get();

        $full_products = $original->merge($no_original);

        return response()->json($full_products);
    }

    public function stockProductDelivery(Request $request){
        CartProduct::where([
            ['cart_id',$request->order_id],
            ['product_id',$request->id_product]
        ])->update(['stock_id' => $request->id_stock]);
    }

    public function generatePdf(Request $request){
        if ($request->isMethod('post')){
            $data = $request->except('_token');
            $pdf = App::make('dompdf.wrapper');
            if ($request->has('client_info')){
                session(['client_info' => $request->client_info]);
            }
            $pdf->loadHTML($this->service->makeOrderCheckTemplate($data));
            return $pdf->stream();
        }

        $oder_info = Cart::with(['cartProduct','client'=>function($query){$query->with(['deliveryInfo','discount']);}])->where('id',$request->id)->first();

        return response()->json($oder_info);
    }

    public function createOrder(Request $request){
        if ($request->isMethod('post')){
            $data = $request->except('_token');

            if ((int)$data['client_id'] === 0 ){
                $user = new User();
                $user->fill([
                    'fio' => $data['fio'],
                    'phone' => $data['phone'],
                    'role' => 3,
                    'permission' => 'user'
                ]);
                $user->save();
            } else{
                $user = User::findOrFail((int)$data['client_id']);
            }

            $order = new Cart();
            $order->fill([
                'user_id' => $user->id,
                'oder_status' => 2,
                'manager_id' => Auth::id(),
                'oder_dt' => Carbon::now()
            ]);

            if ($order->save()){
                foreach ($data['product_id'] as $k => $product){
                    CartProduct::insert([
                        'cart_id' => $order->id,
                        'product_id' => (int)$product,
                        'count' => (int)$data['count_product'][$k]
                    ]);
                }
            }

            return redirect()->route('admin.orders')->with('status','Заказ добавлен');
        }

        $users = null;
        $providers = Provider::all();

        if (!isset($request->user)){
            $users = User::all();
        }

        return view('admin.orders.create_order',compact('users','providers'));
    }
}
