<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\FastBuy;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request){
        $product = Product::where('alias', $request->alias)->first();

        return view('product.product_detail',compact('product'));
    }

    public function fastBuy(Request $request){
        $data = ['product_id' => $request->id,'phone' => $request->phone];

        $fast_buy = new FastBuy();
        $fast_buy->fill($data);
        if($fast_buy->save()){
            return response()->json([
                'response' => 'Запрос сделан'
            ]);
        } else {
            return response()->json([
                'response' => 'Произошла ошибка, попробуйте ещё раз'
            ]);
        }
    }

    public function addCart(Request $request){
        $count = (integer)$request->post('product_count');
        $product = Product::find((integer)$request->id);
        if ($request->cookie('cart_session_id')){
            $cart_session_id = $request->cookie('cart_session_id');
        } else {
            $cookie = cookie('cart_session_id',session()->getId(),30*24*60*60);
            $cart_session_id = session()->getId();
        }

        $cart = Cart::where([
            (isset(Auth::user()->id))? ['user_id',Auth::user()->id]:['user_id',null],
            ['session_id',$cart_session_id],
            ['oder_status', 1]
        ])->first();

        if (!isset($cart)){
            $cart = new Cart();
            $data = [
                'user_id' => (isset(Auth::user()->id))? Auth::user()->id: null,
                'session_id' => $cart_session_id,
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

        $added_products = $this->getCartProducts($cart->id);
        $sum = 0.00;
        foreach ($added_products as $added_product){
            $sum += (float)$added_product->price * (int)$added_product->count;
        }

        if (!empty($cookie)) {
            return response()->json([
               'response' => [
                   'sum' => $sum,
                   'save' => $save
               ]
            ])->cookie($cookie);
        } else {
            return response()->json([
                'response' => [
                    'sum' => $sum,
                    'save' => $save
                ]
            ]);
        }
    }
}
