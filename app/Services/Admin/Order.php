<?php


namespace App\Services\Admin;


use App\Cart;
use Illuminate\Support\Facades\DB;

class Order
{
    public function getOrders($request){
        $filters = [
            ['carts.oder_status','<>',1]
        ];

        if (isset($request->oder_id) && !empty($request->oder_id)) $filters[] = ['carts.id','=',(int)$request->oder_id];
        if (isset($request->status_oder) && !empty($request->status_oder)) $filters[] = ['carts.oder_status','=',(int)$request->status_oder];
        if (isset($request->new_oder) && !empty($request->new_oder)) $filters[] = ['carts.seen','=',0];
        if (isset($request->date_oder_start) && !empty($request->date_oder_start)) $filters[] = ['carts.oder_dt','>=',$request->date_oder_start];
        if (isset($request->date_oder_end) && !empty($request->date_oder_end)) $filters[] = ['carts.oder_dt','>=',$request->date_oder_end];
        if (isset($request->client_id) && !empty($request->client_id)) $filters[] = ['carts.user_id','=',(int)$request->client_id];

        return Cart::with(['cartProduct','status','client' =>
            function($query){
                $query->with(['discount','type_user','deliveryInfo','userCity']);
            }
            ,'payOder'])
            ->orderByDesc('carts.oder_dt')
            ->join('users','users.id','=','carts.user_id')
            ->where($filters)
            ->select('carts.*')
            ->paginate(50);
    }

    public function getOrderData($request){
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

        return $product_data;
    }

    public function makeOrderCheckTemplate($data){
        return view('admin.pdf_template.order_check',compact('data'));
    }
}
