<?php

namespace App\Providers;

use App\Page;
use App\TecDoc\Tecdoc;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;
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
            $pages = Page::all();
            View::share('pages', $pages);

            $cart_session_id = Cookie::get('cart_session_id');
            if (!isset($cart_session_id)){
                Cookie::forever('cart_session_id',session()->getId());
            }

            if (Cache::has('category_tecdoc_1-level')){
                \view()->share('category',Cache::get('category_tecdoc_1-level'));
            } else {
                $tecdoc = new Tecdoc('mysql_tecdoc');
                Cache::put('category_tecdoc_1-level',$tecdoc->getPrd(),60*24);
                \view()->share('category',Cache::get('category_tecdoc_1-level'));
            }

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
