<?php

namespace App\Services;

use App\{CartProduct, FastBuy, Http\Controllers\Controller, Product as ProductModel, Cart as CartModel, ProductComment};
use Illuminate\Support\Facades\DB;

class Product
{
    protected $base_controller;

    public function __construct()
    {
        $this->base_controller = new Controller();
    }

    public function addToCart($request,$iser_id){
        $count = (integer)$request->post('product_count');
        $product = ProductModel::find((integer)$request->id);
        $cart_session_id = $request->cookie('cart_session_id');

        $cart = CartModel::where([
            isset($iser_id)
                ?['user_id',$iser_id]
                :['session_id',$cart_session_id],
            ['oder_status', 1]
        ])->first();

        if (!isset($cart)){
            $cart = new CartModel();
            $data = [
                'user_id' => (isset($iser_id))? $iser_id: null,
                'session_id' => (isset($iser_id))?null:$cart_session_id,
                'oder_status' => 1
            ];
            $cart->fill($data);
            $cart->save();
        }

        if (DB::table('cart_products')->where([['cart_id',$cart->id],['product_id',$product->id]])->exists()){
            CartProduct::where([['cart_id',$cart->id],['product_id',$product->id]])->update(['count' => $count]);
            $save = 'Продукт добавлен в корзину';
        }else{
            $data = ['cart_id' => $cart->id,'product_id' => $product->id,'count' => $count];
            $cart_product = new CartProduct();
            $cart_product->fill($data);
            if ($cart_product->save()){
                $save = 'Продукт добавлен в корзину';
            } else {
                $save = 'Произошла ошибка! Попробуйте позже.';
            }

        }

        $added_products = $this->base_controller->getCartProducts($cart->id);
        $sum = 0.00;
        foreach ($added_products as $added_product){
            $sum += (float)$added_product->price * (int)$added_product->count;
        }

        return [
            'response' => [
                'sum' => $sum,
                'save' => $save
            ]
        ];
    }

    public function setProductComment($data,$user_id,$user_name){
        $data['user_id'] = $user_id;
        $comment = new ProductComment();
        $comment->fill($data);
        if ($comment->save()){
            return ['response' => $comment,'user' => $user_name];
        } else{
            return ['error' => 'коментарий не сохранён'];
        }
    }

    public function setFastBuy($data){
        $fast_buy = new FastBuy();
        $fast_buy->fill($data);
        if($fast_buy->save()){
            return ['response' => 'Запрос сделан'];
        } else {
            return ['response' => 'Произошла ошибка, попробуйте ещё раз'];
        }
    }
}
