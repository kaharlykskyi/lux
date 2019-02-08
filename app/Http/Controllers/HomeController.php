<?php

namespace App\Http\Controllers;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    protected $tecdoc;

    protected $data = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request)
    {
        if ($request->hasCookie('search_cars')){
            $cookies = json_decode($request->cookie('search_cars'),true);
            foreach ($cookies as $k => $cookie){
                $this->tecdoc->setType($cookie['type_auto']);
                $search_cars[$k]['data'] = $this->tecdoc->getModificationById($cookie['modification_auto']);
                $search_cars[$k]['cookie'] = $cookie;
            }
        } else {
            $search_cars = null;
        }

        $this->tecdoc->setType('passenger');
        $brands = DB::table('show_brand')
            ->where('ispassengercar','=','true')
            ->select('brand_id AS id','description')->get();
        $models = [];
        if (isset($brands)) {
            foreach ($brands as $key){
                $buff = $this->tecdoc->getModels($key->id,null,2);
                foreach ($buff as $item){
                    array_push($models,$item);
                }
            }
        }

        $popular_products = DB::select("SELECT DISTINCT p.articles,p.name FROM `cart_products` AS cp 
                                              JOIN `products` AS p ON p.id=cp.product_id LIMIT 32");

        foreach ($popular_products as $product){
            $buff = DB::connection('mysql_tecdoc')->select("SELECT supplierId FROM articles 
                                                                      WHERE FoundString='".preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$product->articles )."'");
            if(isset($buff[0])){
                $product->supplierId = $buff[0]->supplierId;
            }
        }

        return view('home.index',compact('search_cars','brands','models','popular_products'));
    }

    public function subcategory(Request $request){
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
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

        return response()->json([
            'subCategory' => $subCategory
        ]);
    }

    public function getBrands(Request $request){
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

    public function getModel(Request $request){
        if (isset($request->type_auto)){
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


            return response()->json([
                'response' => $model
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

    public function getModifications(Request $request){
        if (isset($request->type_auto)){
            $this->tecdoc->setType($request->type_auto);
            switch ($request->type_mod){
                case 'General':
                    $this->data = $this->tecdoc->getModifications($request->model_id,$request->type_mod);
                    break;
                case 'Engine':
                    $buff = $this->tecdoc->getModifications($request->model_id,'TechnicalData','FuelType');
                    $use_val = [];
                    foreach ($buff as $item){
                        if(!in_array($item->displayvalue,$use_val)){
                            $use_val[] = $item->displayvalue;
                            $this->data[] = $item;
                        }
                    }
                    break;
                case 'Body':
                    $buff = $this->tecdoc->getModifications($request->model_id,$request->type_mod,'BodyType');
                    $use_val = [];
                    foreach ($buff as $item){
                        if(!in_array($item->displayvalue,$use_val)){
                            $use_val[] = $item->displayvalue;
                            $this->data[] = $item;
                        }
                    }
                    break;
            }
            return response()->json([
                'response' => $this->data
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

    public function getSectionParts(Request $request){
        $data = $request->except('_token');

        $coocie_cars = $request->cookie('search_cars');
        if(isset($coocie_cars)){
            $cars = json_decode($coocie_cars,true);
            $new_car = true;
            foreach ($cars as $item){
                if ($item['modification_auto'] === $data['modification_auto']){
                    $new_car = false;
                }
            }
            if ($new_car){
                array_push($cars,$data);
            }
            $cookies =  Cookie::forever('search_cars',json_encode($cars));
        } else {
            $cars[] = $data;
            $cookies =  Cookie::forever('search_cars',json_encode($cars));
        }

        $this->tecdoc->setType($data['type_auto']);
        $category = $this->tecdoc->getSections($data['modification_auto']);


        return response()->json([
            'response' => $category,
            'modification_auto' => $data['modification_auto'],
            'type_auto' => $data['type_auto']
        ])->withCookie($cookies);
    }
}
