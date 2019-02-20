<?php

namespace App\Http\Controllers;

use App\Category;
use App\TecDoc\Tecdoc;
use App\UserCar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $search_cars = $this->getSearchCars($request);

        $this->tecdoc->setType('passenger');
        $brands = DB::table('show_brand')
            ->where('ispassengercar','=','true')
            ->select('brand_id AS id','description')->limit(20)->get();
        $models = [];
        if (isset($brands)) {
            foreach ($brands as $key){
                $buff = $this->tecdoc->getModels($key->id,null,2);
                foreach ($buff as $item){
                    $item->manufacturerid = $key->id;
                    $models[] = $item;
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

    public function allBrands(Request $request){

        if (isset($request->brand) && isset($request->model)){
            $this->tecdoc->setType('passenger');
            $modifications = $this->tecdoc->getModifications($request->model,'General');
            $categories = null;
            $uses_cat = [];
            foreach ($modifications as $modification){
                $buff = $this->tecdoc->getSections($modification->id);
                foreach ($buff as $item){
                    if (!in_array($item->id,$uses_cat)){
                        $item->subCategories = $this->tecdoc->getSections($modification->id,$item->id);
                        $categories[] = $item;
                        $uses_cat[] = $item->id;
                    }
                }
            }

            return view('home.categories',['categories' => $categories,'model' => $this->tecdoc->getModelById($request->model),'brand' => $this->tecdoc->getBrandById($request->brand)]);
        }

        if (isset($request->modification_auto)){
            $this->tecdoc->setType(isset($request->type_auto)?$request->type_auto:'passenger');
            $categories = $this->tecdoc->getSections($request->modification_auto);
            foreach ($categories as $category){
                $category->subCategories = $this->tecdoc->getSections($request->modification_auto,$category->id);
            }
            return view('home.modif_category',['categories' => $categories,'modification' => $this->tecdoc->getModificationById($request->modification_auto)]);
        }

        if (isset($request->brand)){
            $this->tecdoc->setType('passenger');
            $brand = $this->tecdoc->getBrandById($request->brand);
            $brand[0]->models = $this->tecdoc->getModels($brand[0]->id);

            return view('home.brand')->with(['brand' => $brand[0]]);
        }

        $brands = DB::table('show_brand')
            ->where('ispassengercar','=','true')
            ->select('brand_id AS id','description')->get();
        $this->tecdoc->setType('passenger');
        foreach ($brands as $k => $brand){
            $brands[$k]->models = $this->tecdoc->getModels($brand->id);
        }

        return view('home.all_brands',compact('brands'));
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

        foreach ($category as $k => $item){
            $category[$k]->sub_category = $this->tecdoc->getSections($data['modification_auto'],$item->id,7);
            $category[$k]->image_data = Category::where([
                ['tecdoc_id',(int)$item->id],
                ['type',($data['type_auto'] === 'passenger')?'passanger':'commercial']
            ])->first();
        }


        return response()->json([
            'response' => $category,
            'modification_auto' => $data['modification_auto'],
            'type_auto' => $data['type_auto']
        ])->withCookie($cookies);
    }

    public function delGarageCar(Request $request){
        if (Auth::check()){
            UserCar::where([['modification_auto',$request->mod],['user_id',Auth::id()]])->delete();
        } else{
            if ($request->hasCookie('search_cars')){
                $cookies = json_decode($request->cookie('search_cars'),true);
                foreach ($cookies as $k => $cookie){
                    if ($cookie['modification_auto'] === $request->mod){
                        unset($cookies[$k]);
                    }
                }
                Cookie::queue(Cookie::forever('search_cars',json_encode($cookies)));
            }
        }
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
                            'modification_auto' => (int)$cookie['modification_auto'],
                            'body_auto' => (int)$cookie['body_auto'],
                            'type_motor' => (int)$cookie['engine_auto']
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
}
