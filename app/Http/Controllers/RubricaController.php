<?php

namespace App\Http\Controllers;

use App\{AllCategoryTree, HomeCategoryGroup, Services\Rubric, TecDoc\Tecdoc};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RubricaController extends Controller
{
    protected $service;

    protected  $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->service = new Rubric();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
        $this->tecdoc->setType('passenger');
    }

    public function index(Request $request){
        $category = HomeCategoryGroup::where('hurl',$request->category)->first();
        if (!isset($category)){
            $category = AllCategoryTree::with('subCategory')->where('hurl',$request->category)->firstOrFail();
            if ($category->level === 2){
                return redirect()->route('catalog',$category->hurl);
            }
        }else{
            $sub_category_id = explode(',',$category->categories_id);
            $category->subCategory = $this->service->getSubCategory($sub_category_id);
        }

        return view('rubrics.index',compact('category'));
    }

    public function chooseCar(Request $request){
        if (isset($request->category)){
            $brands = null;
            $models = null;
            $modifs = null;

            $category = $this->tecdoc->getPdrForId($request->category);
            $links = [(object)['title' => $category->description]];

            if (!isset($request->model) && !isset($request->brand)){
                $brands = DB::table('show_brand')
                    ->where('ispassengercar','=','true')
                    ->select('brand_id AS id','description')->get();
            }
            if ($request->has('brand') && !isset($request->model)){
                $models = $this->tecdoc->getModels($request->brand);
                $brand_info = $this->tecdoc->getBrandById((int)$request->brand);
                $links = [
                    (object)['title' => $category->description,'link' => route('rubric.choose_car',$category->id)],
                    (object)['title' => $brand_info[0]->name]
                ];
            }
            if ($request->has('model')){
                $modifs = $this->tecdoc->getModifications($request->model);
                $brand_info = $this->tecdoc->getBrandById($request->brand);
                $model_info = $this->tecdoc->getModelById($request->model);
                $links = [
                    (object)['title' => $category->description,'link' => route('rubric.choose_car',$category->id)],
                    (object)['title' => $brand_info[0]->name,'link' => route('rubric.choose_car',['category' => $category->id,'brand' =>$brand_info[0]->id])],
                    (object)['title' => $model_info[0]->name]
                ];
            }

            return view('rubrics.choose_car',compact('category','brands','models','modifs','links'));
        }else{
            return back();
        }
    }
}
