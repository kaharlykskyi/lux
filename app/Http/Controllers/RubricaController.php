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

            $category = $this->tecdoc->getPdrForId($request->category);
            $links = [(object)['title' => $category->description]];

            return view('rubrics.choose_car',compact('category','links'));
        }else{
            return back();
        }
    }
}
