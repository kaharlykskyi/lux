<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(){
        $cart = DB::table('carts')->where([['user_id', Auth::user()->id],['oder_status', 1]])->first();
        if (isset($cart)){
            $products = DB::table('cart_products')
                ->where('cart_products.cart_id',$cart->id)
                ->join('products','products.id','=','cart_products.product_id')
                ->select('products.*','cart_products.count')
                ->get();
        }

        return view('component.cart_table', compact('products'));
    }
}
