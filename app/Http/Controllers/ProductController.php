<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $product = Product::where('alias', $request->alias)->first();


        return view('product.product_detail',compact('product'));
    }
}
