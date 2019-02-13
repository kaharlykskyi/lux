<?php

namespace App\Providers;

use App\{Cart,CartProduct,Page};
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
            $cart_session_id = Cookie::get('cart_session_id');
            if (!isset($cart_session_id)){
                Cookie::forever('cart_session_id',session()->getId());
            }

            view()->composer('*', function($view)
            {
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
                $view->with(['products_cart_global' => $products, 'pages_global' => $pages]);
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
