<?php

namespace App\Http\Controllers;

use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\{Auth, DB, Input};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

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
