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
        $type = 'passanger';

        if (isset($request->comercial)){
            $type = 'comercial';
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

        foreach ($categories as $k => $category){
            $categories[$k]->image_data = Category::where('tecdoc_id',$category->id)->first();
        }

        return view('admin.category.index',compact('categories','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        $table = ($request->type === 'passanger')?'passanger_car_trees':'commercial_vehicle_trees';
        $type = $request->type;
        $category = Category::where([
            ['tecdoc_id',$request->category],
            ['type',($type === 'passanger')?'passanger':'commercial']
        ])->first();
        $tecdoc_category = DB::connection('mysql_tecdoc')
            ->table($table)
            ->where([
                ['parentid',0],
                ['id',$request->category]
            ])->first();

        return view('admin.category.edit',compact('category','tecdoc_category','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $category = Category::where([
            ['tecdoc_id',$request->category],
            ['type',($request->type === 'passanger')?'passanger':'commercial']
        ])->first();
        if ($request->hasFile('logo')){

            if (isset($category) && file_exists(public_path() . '/images/catalog/' . $category->logo)){
                unlink(public_path() . '/images/catalog/' . $category->logo);
            }

            $file = $request->file('logo');
            $file_name = $request->category . $file->getClientOriginalName();
            $file->move(public_path() . '/images/catalog/',$file_name);
        }

        $data = [
            'tecdoc_id' => (int)$request->category,
            'name' => $request->name,
            'type' => $request->type,
            'logo' => isset($file_name)?$file_name:null
        ];

        if (isset($category)){
            Category::where([
                ['tecdoc_id',$request->category],
                ['type',($request->type === 'passanger')?'passanger':'commercial']
            ])->update($data);
        } else {
            $new_category = new Category();
            $new_category->fill($data);
            $new_category->save();
        }

        return back();
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
