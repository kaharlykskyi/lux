<?php

namespace App\Http\Middleware;

use App\CallOrder;
use App\Cart;
use App\CartProduct;
use App\OrderPay;
use App\Page;
use App\TopMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\{Facades\Auth, Facades\Cache, Facades\Cookie, Facades\View};

class InitApp
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Cookie::has('cart_session_id')){
            $cart_session_id = session()->getId() . time();
            Cookie::queue(Cookie::make('cart_session_id',$cart_session_id,60*24));
        } else{
            $cart_session_id = Cookie::get('cart_session_id');
        }
        if (!Cookie::has('vin_catalog')){
            Cookie::queue('vin_catalog', 'quickGroup');
        }

        if (Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager') ){
            $count_new_orders = Cart::where([['seen',0],['oder_status','<>',1]])->count();
            $count_new_call_orders = CallOrder::where('status',0)->count();
            $count_new_pay_mass = OrderPay::where('seen',0)->where('success_pay','true')->count();
        }
        $pages = Cache::remember('pages_all', 60*24, function () {
            return Page::all();
        });

        $top_menu = Cache::remember('top_menu', 60*24, function () {
            return TopMenu::where('show_menu',1)->get();
        });

        $cart = Cart::where([
            Auth::check()
                ?['user_id',Auth::id()]
                :['session_id',$cart_session_id],
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
            'top_menu_global' => $top_menu,
            'count_new_orders_global' => isset($count_new_orders)?$count_new_orders:0,
            'count_new_call_orders_global' => isset($count_new_call_orders)?$count_new_call_orders:0,
            'count_new_pay_mass_global' => isset($count_new_pay_mass)?$count_new_pay_mass:0
        ]);

        return $next($request);
    }
}
