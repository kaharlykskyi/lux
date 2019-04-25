<?php


namespace App\Services;

use App\TecDoc\Tecdoc;
use App\UserCar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class Home
{
    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function getPopularProduct(){

        $popular_products = DB::select("SELECT DISTINCT p.articles,p.name FROM `cart_products` AS cp 
                                              JOIN `products` AS p ON p.id=cp.product_id LIMIT 32");
                foreach ($popular_products as $product){
                    $buff = DB::connection('mysql_tecdoc')->select("SELECT supplierId FROM articles 
                                                                      WHERE FoundString='".preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$product->articles )."'");
                    if(isset($buff[0])){
                        $product->supplierId = $buff[0]->supplierId;
                    }
                }

        return $popular_products ;
    }

    public function getSearchCars($request){
        $search_cars = null;
        if (Auth::check()){
            if ($request->hasCookie('search_cars')){
                $cookies = json_decode($request->cookie('search_cars'),true);
                foreach ($cookies as $k => $cookie){
                    if (!DB::table('user_cars')->where([['modification_auto',(int)$cookie['modification_auto']],['user_id',Auth::id()]])->exists()){
                        $userCar = new UserCar();
                        $userCar->fill([
                            'user_id' => Auth::id(),
                            'vin_code' => ' ',
                            'type_auto' => $cookie['type_auto'],
                            'year_auto' => (int)$cookie['year_auto'],
                            'brand_auto' => (int)$cookie['brand_auto'],
                            'model_auto' => (int)$cookie['model_auto'],
                            'modification_auto' => (int)$cookie['modification_auto']
                        ]);
                        $userCar->save();
                    }
                }
                Cookie::queue(Cookie::forget('search_cars'));
            }
            $userCars = UserCar::where('user_id',Auth::id())->get();
            foreach ($userCars as $k => $car){
                $this->tecdoc->setType($car->type_auto);
                $search_cars[$k]['data'] = $this->tecdoc->getModificationById($car->modification_auto);
                $search_cars[$k]['cookie'] = $car;
            }
        } else{
            if ($request->hasCookie('search_cars')){
                $cookies = json_decode($request->cookie('search_cars'),true);
                foreach ($cookies as $k => $cookie){
                    $this->tecdoc->setType($cookie['type_auto']);
                    $search_cars[$k]['data'] = $this->tecdoc->getModificationById($cookie['modification_auto']);
                    $search_cars[$k]['cookie'] = $cookie;
                }
            }
        }

        return $search_cars;
    }

    public function getSubCategory($request){
        $subCategory = null;
        if (isset($request->type) && isset($request->category)){
            $this->tecdoc->setType($request->type);
            if (isset($request->level)){
                $subCategory = $this->tecdoc->getCategory([
                    ['id','usagedescription','normalizeddescription'],
                    [
                        [$request->level,'=',"'$request->category'"]
                    ]
                ]);
            }
        } else{
            $this->tecdoc->setType($request->type);
            $subCategory = $this->tecdoc->getCategory();
        }

        $buff = [];
        $sortSubCategory = [];
        foreach ($subCategory as $item){
            if (isset($request->category)){
                if (!in_array($item->normalizeddescription,$buff)){
                    $buff[] = $item->normalizeddescription;
                    $sortSubCategory[] = $item;
                }
            }else{
                if (!in_array($item->assemblygroupdescription,$buff)){
                    $buff[] = $item->assemblygroupdescription;
                    $sortSubCategory[] = $item;
                }
            }
        }

        return $sortSubCategory;
    }

    public function getAllBrands($request){

        if (isset($request->type_auto)){
            $brands = DB::table('show_brand')
                ->where(($request->type_auto === 'passenger')?'ispassengercar':'iscommercialvehicle','=','true')
                ->select('brand_id AS id','description')->get();
            return response()->json([
                'response' => isset($brands[0])?$brands:[
                    'id' => 0,
                    'description' => 'не найдено'
                ]
            ]);
        } else {
            return response()->json([
                'response' => [
                    'id' => 0,
                    'description' => 'не найдено'
                ]
            ]);
        }
    }

    public function getModel($request){
        $this->tecdoc->setType($request->type_auto);
        $buff = $this->tecdoc->getModels($request->brand_id);
        if (isset($request->year_auto)){
            $model = [];
            foreach ($buff as $k => $item){
                $constructioninterval = explode(' - ',$item->constructioninterval);
                $start = ($constructioninterval[0] !== '') ? explode('.',$constructioninterval[0]): null;
                $end = ($constructioninterval[1] !== '') ? explode('.',$constructioninterval[1]): null;
                if (isset($start) && isset($end)){
                    if ((int)$request->year_auto > (int)$start[1] && (int)$request->year_auto < (int)$end[1]){
                        $model[] = $item;
                    }
                } elseif (isset($start) && !isset($end)){
                    if ((int)$request->year_auto > (int)$start[1]){
                        $model[] = $item;
                    }
                }
            }
        } else {
            $model = $buff;
        }

        return $model;
    }
}
