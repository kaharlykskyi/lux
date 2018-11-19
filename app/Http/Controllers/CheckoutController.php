<?php

namespace App\Http\Controllers;

use App\AppTrait\GEO;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    use RegistersUsers, GEO;

    public function index(){
        $cart = $this->getCart();
        $products = [];
        if (isset($cart)){
            $products = DB::table('cart_products')
                ->where('cart_products.cart_id',$cart->id)
                ->join('products','products.id','=','cart_products.product_id')
                ->select('products.*','cart_products.count','cart_products.cart_id')
                ->get();
        }

        return view('checkout.index',compact('products'));
    }

    public function newUser(Request $request){

    }
}
