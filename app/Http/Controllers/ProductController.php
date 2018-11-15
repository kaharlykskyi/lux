<?php

namespace App\Http\Controllers;

use App\FastBuy;
use App\Product;
use Illuminate\Http\Request;

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
}
