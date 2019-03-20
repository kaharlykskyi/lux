<?php

namespace App\Http\Controllers\Admin;

use App\{Cart, CartProduct, MutualSettlement, Order, Product, User, UserBalance, Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request){
        if ($request->status === 'new'){
            $orders = $this->getOrder(true);
        } else{
            $orders = $this->getOrder();
        }

        $orders = $this->arrayPaginator($orders,$request,30);
        $orders->setPath($request->fullUrl());

        $order_code = DB::table('oder_status_codes')->get();

        return view('admin.orders.index',compact('orders','order_code'));
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
            $order_pay = Order::where('cart_id',$order->id)->first();
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

        return response()->json([
            'response' => 'Статус заказа изменен'
        ]);
    }

    public function getOrder($new = false){
        if ($new) {
            $order_status = "=2";
        } else{
            $order_status = " IN (3,4,5,6)";
        }

        return DB::select("SELECT c.id, c.updated_at,c.oder_status,u.name,c.invoice_np,d.percent,u.id user_id,
                                      (SELECT SUM(p.price * cp.count) FROM `products` AS p 
                                              JOIN `cart_products` AS cp WHERE p.id=cp.product_id AND cp.cart_id=c.id) AS total_price
                                      FROM `carts` AS c
                                      JOIN `users` AS u ON u.id=c.user_id
                                      JOIN `discounts` AS d ON d.id=u.discount_id
                                      WHERE c.oder_status{$order_status} ORDER BY c.updated_at DESC");
    }

    public function editOder(Cart $order,Request $request){
        /*if($request->isMethod('post')){

        }*/
        $user = User::with('deliveryInfo')->find($order->user_id);
        $product = Product::join('cart_products','cart_products.product_id','=','products.id')
            ->where('cart_products.cart_id','=',$order->id)
            ->select('products.*','cart_products.count')->get();
        $order_pay = Order::where('cart_id',$order->id)->first();
        $order_code = DB::table('oder_status_codes')->get();

        return view('admin.orders.edit_order',compact('order','user','product','order_pay','order_code'));
    }

    public function stockProductDelivery(Request $request){
        CartProduct::where([
            ['cart_id',$request->order_id],
            ['product_id',$request->id_product]
        ])->update(['stock_id' => $request->id_stock]);
    }
}
