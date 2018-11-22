<?php

namespace App\Providers;

use App\Page;
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
