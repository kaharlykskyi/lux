<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = null;
        $filter = [['parentid',0]];

        if (isset($request->comercial)){

            if (!Cache::has('categories.comercial')){
                $categories = DB::connection('mysql_tecdoc')
                    ->table('commercial_vehicle_trees')
                    ->where($filter)
                    ->select('id','description')
                    ->groupBy('id','description')
                    ->distinct()
                    ->get();
                Cache::forever('categories.comercial',$categories);
            } else {
                $categories = Cache::get('categories.comercial');
            }
        } else {

            if (!Cache::has('categories.passanger')){
                $categories = DB::connection('mysql_tecdoc')
                    ->table('passanger_car_trees')
                    ->where($filter)
                    ->select('id','description')
                    ->groupBy('id','description')
                    ->distinct()
                    ->get();
                Cache::forever('categories.passanger',$categories);
            } else {
                $categories = Cache::get('categories.passanger');
            }
        }
        $categories = $this->arrayPaginator($categories->toArray(),$request,20);

        return view('admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return void
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
