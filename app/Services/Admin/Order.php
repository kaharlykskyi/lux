<?php


namespace App\Services\Admin;


use App\Cart;
use Illuminate\Support\Facades\DB;

class Order
{
    public function getOrders($request){
        return Cart::with(['cartProduct','status','client' =>
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
