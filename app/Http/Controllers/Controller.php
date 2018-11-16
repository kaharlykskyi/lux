<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getCartProducts($cart){
        return DB::table('cart_products')
            ->where('cart_products.cart_id',$cart)
            ->join('products','products.id','=','cart_products.product_id')
            ->select('products.price','cart_products.count','products.id')
            ->get();
    }
}
