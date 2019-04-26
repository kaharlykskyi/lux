<?php

namespace App\Http\Controllers\Admin;

use App\{Cart,
    CartProduct,
    MutualSettlement,
    OderStatus,
    OrderPay,
    Product,
    User,
    UserBalance,
    Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request){
        if (isset($request->delete_oder)){
            Cart::destroy((int)$request->delete_oder);
            return back();
        }
        $orders = Cart::with(['cartProduct','status','client' =>
            function($query){
                $query->with(['discount','type_user','deliveryInfo','userCity']);
            }
            ,'payOder'])
            ->orderByDesc('carts.oder_dt')
            ->where('carts.oder_status','<>',1)
            ->where('carts.id',isset($request->oder_id)?'=':'<>',isset($request->oder_id)?$request->oder_id:null)
            ->where('carts.oder_status',(int)$request->status_oder!==0?'=':'<>',(int)$request->status_oder!==0?$request->status_oder:1)
            ->where('carts.seen',isset($request->seen)?'=':'>=',isset($request->seen)?1:0)
            ->where('carts.oder_dt',isset($request->date_oder_start)?'>=':'<>',isset($request->date_oder_start)?$request->date_oder_start:null)
            ->where('carts.oder_dt',isset($request->date_oder_end)?'<=':'<>',isset($request->date_oder_end)?$request->date_oder_end:null)
            ->join('users','users.id','=','carts.user_id')
            ->where('carts.user_id',(int)$request->client_id!==0?'=':'<>',(int)$request->client_id!==0?$request->client_id:null)
            ->select('carts.*')
            ->paginate(50);
        $order_code = OderStatus::all();
        $clients = User::all();
        return view('admin.orders.index',compact('orders','order_code','clients'));
    }

    public function getOrderData(Request $request){
        $product_data = DB::select("SELECT p.id,p.price,p.name,p.articles,cp.count AS count_in_cart FROM `products` AS p 
                                          JOIN `cart_products` AS cp ON cp.product_id=p.id 
                                          WHERE cp.product_id=p.id AND cp.cart_id={$request->idOrder}");

        foreach ($product_data as $k => $item){
            if ($item->price < 2000){
                $product_data[$k]->price = $item->price - $item->price * 0.2;
            } elseif ($item->price >= 2000 && $item->price <= 5000){
                $product_data[$k]->price = $item->price - $item->price * 0.15;
            } elseif ($item->price > 5000){
                $product_data[$k]->price = $item->price - $item->price * 0.1;
            }
            $product_data[$k]->price = round($product_data[$k]->price,2);
        }

        return response()->json([
            'response' => $product_data
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

    }
}
