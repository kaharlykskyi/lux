<?php

namespace App\Http\Controllers;

use App\{Banner, CallOrder, Category, Services\Home, TecDoc\Tecdoc, UserCar};
use Illuminate\Http\Request;
use Illuminate\Support\{Facades\Auth, Facades\Cookie, Facades\DB, Facades\Validator};

class HomeController extends Controller
{

    protected $service;

    protected $tecdoc;

    protected $data = [];

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->service = new Home();
    }

    public function index(Request $request)
    {
        $search_cars = $this->service->getSearchCars($request);

        $this->tecdoc->setType('passenger');
        $brands = DB::table('show_brand')
            ->where('ispassengercar','=','true')
            ->select('brand_id AS id','description')->limit(20)->get();

        $popular_products = $this->service->getPopularProduct();

        $slides = Banner::all();

        return view('home.index',compact('search_cars','brands','popular_products','slides'));
    }

    public function allBrands(Request $request){

        if (isset($request->brand) && isset($request->model)){
            $this->tecdoc->setType('passenger');

            $all_category = DB::connection('mysql_tecdoc')
                ->table('manufacturers AS m')
                ->join('models AS mod','m.id','=','mod.manufacturerid')
                ->join('passanger_cars AS pc','pc.modelid','=','m.id')
                ->join('passanger_car_trees AS pct','pct.passangercarid','=','pc.id')
                ->where('m.id',(int)$request->brand)
                ->where('m.ispassengercar','TRUE')
                ->where('mod.id',(int)$request->model)
                ->select('pct.id','pct.description','pct.parentid')
                ->distinct()
                ->get();

            $categories = null;

            foreach ($all_category as $category){
                if ($category->parentid === 0){
                    $categories[] = $category;
                }
            }

            foreach ($categories as $k => $category){
                foreach ($all_category as $item){
                    if ($category->id === $item->parentid){
                        $categories[$k]->subCategories[] = $item;
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
        return response()->json([
            'subCategory' => $this->service->getSubCategory($request)
        ]);
    }

    public function getBrands(Request $request){
        return $this->service->getAllBrands($request);
    }

    public function getModel(Request $request){
        if (isset($request->type_auto)){
            return response()->json([
                'response' => $this->service->getModel($request)
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

        $this->tecdoc->setType(isset($request->type_auto)?$request->type_auto:'passenger');
        switch ($request->type_mod){
            case 'General':
                $buff = $this->tecdoc->getModifications($request->model_id);
                $search_mod_id = [];
                foreach ($buff as $item){
                    if ($item->attributetype === 'BodyType' && $item->displayvalue === $request->body){
                        array_push($search_mod_id,$item->id);
                    }
                }
                foreach ($buff as $item){
                    if (in_array($item->id,$search_mod_id)){
                        $this->data[] = $item;
                    }
                }
                break;
            case 'Body':
                $buff = $this->tecdoc->getModifications($request->model_id,[['attributegroup' ,'=', '\''.$request->type_mod.'\'']]);
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

    public function modificationInfo(Request $request){
        $this->tecdoc->setType($request->type);
        return response()->json($this->tecdoc->getModificationById($request->mod_id));
    }

    public function callOrder(Request $request){
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()
            ]);
        }

        $call_oder = new CallOrder();
        $call_oder->fill($data);

        if ($call_oder->save()){
            return response()->json(['Заявка принята']);
        } else{
            return response()->json(['Произошла ошибка!Попробуйте позже']);
        }

    }
}
