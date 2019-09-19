<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\Cart;
use App\CategoresGroupForCar;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\{Auth, Cache, DB, Input};

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

    }

    protected $alias_manufactures = [
        'VOLKSWAGEN' => 'VW'
    ];

    public static function getCartProducts($cart){
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
        $rus = array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',' ','/');
        $lat = array('a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','a','b','v','g','d','e','e','gh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','y','y','y','e','yu','ya','_','_');
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

    public static function getSumOrder($products){
        $sum = 0;
        foreach ($products as $item){
            $sum += $item->count * floatval($item->product->price);
        }

        return $sum;
    }

    public static function getMenu($cars = null,$modification_auto = null){
        $all_tecdoc_ids = [];
        $data =  Cache::remember('all_category', 60*24*7*365, function () use ($cars) {
            $all_category = CategoresGroupForCar::with(['childCategories' => function($query){
                $query->with('childRootCategories');
            },'childRootCategories'])
                ->whereNull('parent_id')->orderByDesc(DB::raw('-`range`'))->get();
            foreach ($all_category as $item){
                $item = self::getSubCategory($item);
                if (isset($item->sub_categores)){
                    foreach ($item->sub_categores as $sub){
                        $all_tecdoc_ids[] = $sub->tecdoc_id;
                    }
                }
                foreach ($item->childCategories as $child){
                    $child = self::getSubCategory($child);
                    if (isset($child->sub_categores)){
                        foreach ($child->sub_categores as $subChild){
                            $all_tecdoc_ids[] = $subChild->tecdoc_id;
                        }
                    }
                }
            }

            if (!isset($modification_auto) && isset($cars)){
                $modification_auto = $cars[0]['cookie']->modification_auto;
            }

            if (isset($modification_auto) && !empty($all_tecdoc_ids)){
                $tecdoc = new Tecdoc('mysql_tecdoc');
                $tecdoc->setType('passenger');
                Cache::remember('count_product_modif_'.$modification_auto,60*24,function () use ($tecdoc, $modification_auto, $all_tecdoc_ids) {
                    return $tecdoc->getAllCategoryTree($all_tecdoc_ids,'modif',(int)$modification_auto);
                });
            }

            return $all_category;
        });
        return $data;
    }

    private static function getSubCategory($category){
        if (isset($category->categories)){
            $sub_cat = json_decode($category->categories);
            if (!empty($sub_cat[0])){
                $category->sub_categores = AllCategoryTree::with('subCategory')->whereIn('id',$sub_cat)->get();
            }
        }
        foreach ($category->childRootCategories as $childRootCategories){
            $category->sub_categores = collect($category->sub_categores)
                ->merge(AllCategoryTree::with('subCategory')->where('parent_category',$childRootCategories->id)->get());
        }

        return $category;
    }
}
