<?php

namespace App\Providers;

use App\{CallOrder, Cart, CartProduct, Page};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(!app()->runningInConsole() ){
            if (!Cookie::has('cart_session_id')){
                Cookie::queue(Cookie::make('cart_session_id',session()->getId(),60*24));
            }
            if (!Cookie::has('vin_catalog')){
                Cookie::queue('vin_catalog', 'quickGroup');
            }
            view()->composer('*', function($view){
                $count_new_orders = Cart::where('seen',0)->count();
                $count_new_call_orders = CallOrder::where('status',0)->count();
                $pages = Page::all();
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
                $view->with([
                    'products_cart_global' => $products,
                    'pages_global' => $pages,
                    'count_new_orders_global' => $count_new_orders,
                    'count_new_call_orders_global' => $count_new_call_orders
                ]);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
