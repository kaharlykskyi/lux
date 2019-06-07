<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use Illuminate\{Http\Request, Support\Facades\DB};
use App\Services\Cart as CartService;

class CartController extends Controller
{
    protected $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = new CartService();
    }

    public function index(Request $request){
        $cart = $this->getCart($request);
        $products = [];
        if (isset($cart)){
            $products = CartProduct::with('product')->where('cart_id',$cart->id)->get();
        }

        return view('component.cart_table', compact('products'));
    }

    public function productCount(Request $request){
        $data = $request->post();
        DB::table('cart_products')->where([
            ['cart_id',$data['cart_id']],
            ['product_id',$data['product_id']]
        ])->update(['count' => (int)$data['count']]);


        $cart = Cart::with('cartProduct')->find((int)$data['cart_id']);

        $sum = $this->service->getSumOrder($cart->cartProduct);
        $product_cost = $this->service->getCostProduct($cart->cartProduct,$data['product_id']);

        $sum_not_discount = $sum;

        if (isset($cart->user_id)){
            $sum = $this->service->getDiscountSum($cart->user_id,$sum);
        }

        return response()->json([
            'response' => [
                'product_cost' => (int)$product_cost,
                'sum' => (int)$sum,
                'sum_not_disc' => (int)$sum_not_discount
            ]
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

        $cart = Cart::with('cartProduct')->find((int)$data['cart_id']);
        $sum = $this->service->getSumOrder($cart->cartProduct);

        $sum_not_discount = $sum;

        if (isset($cart->user_id)){
            $sum = $this->service->getDiscountSum($cart->user_id,$sum);
        }

        return response()->json([
            'response' => [
                'id_product' => $id_product,
                'sum' => (int)$sum,
                'count' => count($cart->cartProduct),
                'sum_not_disc' => (int)$sum_not_discount
            ]
        ]);
    }
}
