<?php

namespace App\Http\Controllers;

use App\Category;
use App\Services\Rubric;
use Illuminate\Http\Request;

class RubricaController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new Rubric();
    }

    public function index(Request $request){
        $category = $to_category = Category::where('tecdoc_id',$request->category)->first();
        $sub_category = $this->service->getSubCategory($request->category);
        return view('rubrics.index',compact('category','sub_category'));
    }
}
