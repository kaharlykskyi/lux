<?php

namespace App\Http\Controllers;

use App\CallOrder;
use App\Cart;
use App\CartProduct;
use App\OrderPay;
use App\Page;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\{Auth, Cache, Cookie, DB, Input, View};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        if (!Cookie::has('cart_session_id')){
            Cookie::queue(Cookie::make('cart_session_id',session()->getId(),60*24));
        }
        if (!Cookie::has('vin_catalog')){
            Cookie::queue('vin_catalog', 'quickGroup');
        }

        if (Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager') ){
            $count_new_orders = Cart::where([['seen',0],['oder_status','<>',1]])->count();
            $count_new_call_orders = CallOrder::where('status',0)->count();
            $count_new_pay_mass = OrderPay::where('seen',0)->where('success_pay','true')->count();
        }
        $pages = Cache::remember('pages_all', 60, function () {
            return Page::all();
        });
        $cart = Cart::where([
            Auth::check()
                ?['user_id',Auth::id()]
                :['session_id',Cookie::get('cart_session_id')],
            ['oder_status', 1]
        ])->first();
        if (isset($cart)){
            $products = CartProduct::where('cart_products.cart_id',$cart->id)
                ->join('products','products.id','=','cart_products.product_id')
                ->select('products.*','cart_products.count')
                ->get();
        }else{
            $products = null;
        }
        View::share([
            'products_cart_global' => $products,
            'pages_global' => $pages,
            'count_new_orders_global' => isset($count_new_orders)?$count_new_orders:0,
            'count_new_call_orders_global' => isset($count_new_call_orders)?$count_new_call_orders:0,
            'count_new_pay_mass_global' => isset($count_new_pay_mass)?$count_new_pay_mass:0
        ]);
    }

    protected $alias_manufactures = [
        'VOLKSWAGEN' => 'VW'
    ];

    public function getCartProducts($cart){
        return DB::table('cart_products')
            ->where('cart_products.cart_id',$cart)
            ->join('products','products.id','=','cart_products.product_id')
            ->select('products.price','cart_products.count','products.id')
            ->get();
    }

    public function getCart(Request $request){
        $cart = Cart::where([
            Auth::check()
                ?['user_id',Auth::id()]
                :['session_id',$request->cookie('cart_session_id')],
            ['oder_status', 1]
        ])->first();

        return $cart;
    }

    public function transliterateRU($sts,$en = false){
        $rus = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ');
        $lat = array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya',' ');
        if ($en){
            $transliterate_str = str_replace($lat,$rus, $sts);
        }else {
            $transliterate_str = str_replace($rus, $lat, $sts);
        }
        return $transliterate_str;
    }

    public function arrayPaginator($array, $request,$pre_page)
    {
        $page = Input::get('page', 1);
        $perPage = $pre_page;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
