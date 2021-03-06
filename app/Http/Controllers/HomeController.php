<?php

namespace App\Http\Controllers;

use App\{AllCategoryTree,
    Banner,
    CallOrder,
    CategoresGroupForCar,
    Services\Home,
    TecDoc\Tecdoc,
    UserCar};
use Illuminate\Http\Request;
use Illuminate\Support\{Facades\Auth, Facades\Cache, Facades\Cookie, Facades\DB, Facades\Validator};

class HomeController extends Controller
{

    protected $service;

    protected $tecdoc;

    protected $data = [];

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
        $this->service = new Home();
    }

    public function index(Request $request)
    {
        $search_cars = $this->service->getSearchCars($request);

        if (empty($search_cars)){
            $this->tecdoc->setType('passenger');
            $brands = DB::table('show_brand')
                ->where('ispassengercar','=','true')
                ->select('brand_id AS id','description')->limit(20)->get();

            $popular_products = $this->service->getPopularProduct();
        }else{
            $brands = [];
            $popular_products = [];
        }

        $slides = Banner::all();

        if ($request->has('reload')){
            header('Expires: Sat, 03 Aug 2013 00:00:00 GMT');
            header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }

        return view('home.index',compact('search_cars','brands','popular_products','slides'));
    }

    public function allBrands(Request $request){

        if (isset($request->rootcategory)){
            $root = CategoresGroupForCar::with('childCategories')->findOrFail($request->rootcategory);

            if (isset($root->categories)){
                $sub = json_decode($root->categories);
                $sub_categories = [];
                foreach ($sub as $item){
                    if (!empty($item)){
                        $custom_data = Cache::remember('tecdoc_category_info_'.$item, 60*24*7*365, function () use ($item) {
                            return AllCategoryTree::with('subCategory')->where('id',$item)->first();
                        });
                        $sub_categories[] = $custom_data;
                        if (isset($custom_data->tecdoc_id)) $all_ids[] = $custom_data->tecdoc_id;
                        if (isset($custom_data->subCategory)){
                            foreach ($custom_data->subCategory as $subCategory){
                                $all_ids[] = $subCategory->tecdoc_id;
                            }
                        }
                    }
                }
                $root->sub_categories = $sub_categories;
            }

            $modification = $request->modification_auto;
            $all_count = Controller::getCountProductForCategory(null,$modification);
            return view('home.root_category',compact('root','modification','all_count'));
        }

        if (isset($request->brand) && isset($request->model)){
            $brand = $this->tecdoc->getBrandById($request->brand);
            $model = $this->tecdoc->getModelById($request->model);
            $modification = $this->tecdoc->getModifications($request->model);

            return view('home.modifications',compact('brand','model','modification'));
        }

        if (isset($request->modification_auto)){
            $categories = $this->tecdoc->getSections($request->modification_auto);
            foreach ($categories as $category){
                $category->subCategories = $this->tecdoc->getSections($request->modification_auto,$category->id,null,true);
            }
            return view('home.modif_category',['categories' => $categories,'modification' => $this->tecdoc->getModificationById($request->modification_auto)]);
        }

        if (isset($request->brand)){
            $brand = $this->tecdoc->getBrandById($request->brand);
            $brand[0]->models = $this->tecdoc->getModels($brand[0]->id);

            return view('home.brand')->with(['brand' => $brand[0]]);
        }

        $brands = DB::table('show_brand')
            ->where('ispassengercar','=','true')
            ->select('brand_id AS id','description')->get();
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
        $this->data = $this->tecdoc->getModifications($request->model_id);
        return response()->json([
            'response' => $this->data
        ]);
    }

    public function getSectionParts(Request $request){
        $data = $request->except('_token');

        if (isset($data['fo_category'])){
            $fo_category = $data['fo_category'];
            unset($data['fo_category']);
        }

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

        if (isset($fo_category)){
            return response()->json([
                'fo_category' => true,
                'link' => route('catalog',$fo_category).'?car='.$data['modification_auto']
            ])->withCookie($cookies);
        }

        $category = CategoresGroupForCar::with('childCategories')
            ->whereNull('parent_id')->orderByDesc(DB::raw('-`range`'))->get();

        return response()->json([
            'response' => $category,
            'modification_auto' => $data['modification_auto'],
            'type_auto' => $data['type_auto']
        ])->withCookie($cookies);
    }

    public function delGarageCar(Request $request){
        if (Auth::check()){
            UserCar::where([['modification_auto',$request->mod],['user_id',Auth::id()]])->delete();
        }
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

    public function defaultCar(Request $request){
        return redirect()
            ->route('home',['reload' => true])
            ->withCookie('defaultCar', $request->modification, 60*24*7);
    }
}
