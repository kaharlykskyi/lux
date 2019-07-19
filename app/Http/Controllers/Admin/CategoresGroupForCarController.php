<?php

namespace App\Http\Controllers\Admin;

use App\{CategoresGroupForCar, Category, Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoresGroupForCarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car_category = CategoresGroupForCar::paginate(40);
        return view('admin.car_categories.index',compact('car_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_category = DB::connection('mysql_tecdoc')->table('prd')->select('assemblygroupdescription')->distinct()->get();
        return view('admin.car_categories.create',compact('all_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'title' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }
        $data['categories'] = json_encode(explode('@',$data['categories']));
        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/images/catalog/',$file_name);
        }
        $data['logo'] = isset($file_name)?$file_name:null;

        $car_group_category = new CategoresGroupForCar();
        $car_group_category->fill($data);
        $car_group_category->save();
        return redirect()->route('admin.car_categories.index')->with('status','Данные сохранены');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car_categories = CategoresGroupForCar::find((int)$id);
        $all_category = DB::connection('mysql_tecdoc')->table('prd')->select('assemblygroupdescription')->distinct()->get();
        return view('admin.car_categories.edit',compact('car_categories','all_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except(['_method','_token']);
        $car_categories = CategoresGroupForCar::find((int)$id);

        $data['categories'] = json_encode(explode('@',$data['categories']));

        if ($request->hasFile('logo')){

            if (isset($homeCategoryGroup->img) && file_exists(public_path() . '/images/catalog/' . $car_categories->logo)){
                unlink(public_path() . '/images/catalog/' . $car_categories->logo);
            }

            $file = $request->file('logo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/images/catalog/',$file_name);
        }

        if (isset($file_name)){
            $data['logo'] = $file_name;
        }else{
            unset($data['logo']);
        }


        $car_categories->fill($data);
        $car_categories->update();

        return back()->with('status','Данные сохранены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CategoresGroupForCar::where('id',(int)$id)->delete();
        return back()->with('status','Категория удалена');
    }
}
