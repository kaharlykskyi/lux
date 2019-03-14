<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartProduct;
use App\FastBuy;
use App\Product;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $request->alias = str_replace('@','/',$request->alias);
        if(!isset($request->supplierid)){
            $duff = DB::connection('mysql_tecdoc')->select("SELECT supplierId FROM articles WHERE DataSupplierArticleNumber='{$request->alias}' OR FoundString='{$request->alias}'");
            $request->supplierid = $duff[0]->supplierId;
        }
        $product_attr = $this->tecdoc->getArtAttributes($request->alias,$request->supplierid);
        $product_data= $this->tecdoc->getProductByArticle($request->alias,$request->supplierid);
        $product_vehicles = $this->tecdoc->getArtVehicles($request->alias,$request->supplierid);
        $files = $this->tecdoc->getArtFiles($request->alias,$request->supplierid);
        $product = Product::where('articles', $request->alias)->first();

        return view('product.product_detail',compact('product','product_attr','product_vehicles','product_data','files'));
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
        $cart_session_id = $request->cookie('cart_session_id');

        DB::table('carts')->where('user_id','<>',null)->update([
            'session_id' => null
        ]);

        $cart = Cart::where([
            isset(Auth::user()->id)
                ?['user_id',Auth::user()->id]
                :['session_id',$cart_session_id],
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

        return response()->json([
            'response' => [
                'sum' => $sum,
                'save' => $save
            ]
        ]);
    }
}
