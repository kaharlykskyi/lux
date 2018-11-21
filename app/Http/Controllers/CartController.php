<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request){
        $cart = $this->getCart($request);
        $products = [];
        if (isset($cart)){
            $products = DB::table('cart_products')
                ->where('cart_products.cart_id',$cart->id)
                ->join('products','products.id','=','cart_products.product_id')
                ->select('products.*','cart_products.count','cart_products.cart_id')
                ->get();
        }

        return view('component.cart_table', compact('products'));
    }

    public function productCount(Request $request){
        $data = $request->post();
        DB::table('cart_products')->where([
            ['cart_id',$data['cart_id']],
            ['product_id',$data['product_id']]
        ])->update(['count' => (int)$data['count']]);

        $sum = 0.00;
        $product_cost = 0.00;
        $products = $this->getCartProducts($data['cart_id']);

        foreach ($products as $product){
            if ($product->id === (int)$data['product_id']){
                $product_cost = (float)$product->price * (int)$product->count;
            }
            $sum += (float)$product->price * (int)$product->count;
        }

        return response()->json([
            'response' => ['product_cost' => $product_cost,'sum' => $sum]
        ]);
    }

    public function productDelete(Request $request){
        $data = $request->post();

        $id_product = null;
        $delete = DB::table('cart_products')->where([
            ['cart_id',$data['cart_id']],
            ['product_id',$data['product_id']]
        ])->delete();
        if ($delete){
            $id_product = $data['product_id'];
        }

        $sum = 0.00;
        $products = $this->getCartProducts($data['cart_id']);
        foreach ($products as $product){
            $sum += (float)$product->price * $product->count;
        }

        return response()->json([
            'response' => ['id_product' => $id_product,'sum' => (float)$sum]
        ]);
    }
}