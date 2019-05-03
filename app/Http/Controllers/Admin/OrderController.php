<?php

namespace App\Http\Controllers\Admin;

use App\{Cart,
    CartProduct,
    MutualSettlement,
    OderStatus,
    OrderPay,
    Product,
    Services\Admin\Order,
    User,
    UserBalance,
    Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new Order();
    }

    public function index(Request $request){
        if (isset($request->delete_oder)){
            Cart::destroy((int)$request->delete_oder);
            return back();
        }
        $orders = $this->service->getOrders($request);
        $order_code = OderStatus::all();
        $clients = User::all();
        return view('admin.orders.index',compact('orders','order_code','clients'));
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
        /*if($request->isMethod('post')){

        }*/
        Cart::where('id',(int)$request->order)->update(['seen' => 1]);
        $order = Cart::with(['cartProduct','status','client' =>
            function($query){
                $query->with(['type_user','deliveryInfo','userCity']);
            }
            ,'payOder'])->find((int)$request->order);
        $order_code = DB::table('oder_status_codes')->get();

        return view('admin.orders.edit_order',compact('order','order_code'));
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

            $pdf->loadHTML($this->service->makeOrderCheckTemplate($data));
            return $pdf->stream();
        }

        $oder_info = Cart::with(['cartProduct','client'=>function($query){$query->with(['deliveryInfo','discount']);}])->where('id',$request->id)->first();

        return response()->json($oder_info);
    }
}
