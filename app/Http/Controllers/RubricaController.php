<?php

namespace App\Http\Controllers;

use App\AllCategoryTree;
use App\HomeCategoryGroup;
use App\Services\Rubric;
use Illuminate\Http\Request;

class RubricaController extends Controller
{
    protected $service;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->service = new Rubric();
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
}
