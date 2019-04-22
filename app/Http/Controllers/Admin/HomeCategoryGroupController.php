<?php

namespace App\Http\Controllers\Admin;

use App\AllCategoryTree;
use App\HomeCategoryGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class HomeCategoryGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $home_category = HomeCategoryGroup::paginate(40);
        return view('admin.home_category.index',compact('home_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $all_category = AllCategoryTree::where('parent_category',null)->get();
        return view('admin.home_category.create',compact('all_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        $data['hurl'] = isset($data['hurl'])?$data['hurl']:$this->transliterateRU($data['name']);

        $validate = Validator::make($data,[
            'name' => 'required|string|max:255',
            'hurl' => 'string|max:255|unique:home_category_groups',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        if ($request->hasFile('logo')){
            $file = $request->file('logo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/images/catalog/',$file_name);
        }
        $data['img'] = isset($file_name)?$file_name:null;

        $home_category = new HomeCategoryGroup();
        $home_category->fill($data);
        $home_category->save();
        return redirect()->route('admin.home_category.index')->with('status','Данные сохранены');
    }

    /**
     * Display the specified resource.
     *
     * @param HomeCategoryGroup $homeCategoryGroup
     * @return Response
     */
    public function show(HomeCategoryGroup $homeCategoryGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request)
    {
        $homeCategoryGroup = HomeCategoryGroup::find((int)$request->home_category);
        $all_category = AllCategoryTree::where('parent_category',null)->get();
        return view('admin.home_category.edit',compact('homeCategoryGroup','all_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->except(['_method','_token']);
        $homeCategoryGroup = HomeCategoryGroup::find((int)$request->home_category);

        if ($request->hasFile('logo')){

            if (isset($homeCategoryGroup->img) && file_exists(public_path() . '/images/catalog/' . $homeCategoryGroup->img)){
                unlink(public_path() . '/images/catalog/' . $homeCategoryGroup->img);
            }

            $file = $request->file('logo');
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/images/catalog/',$file_name);
        }

        if (isset($file_name)){
            $data['img'] = $file_name;
        }

        unset($data['logo']);

        HomeCategoryGroup::where('id',(int)$request->home_category)->update($data);

        return back()->with('status','Данные сохранены');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        HomeCategoryGroup::where('id',(int)$request->home_category)->delete();
        return back()->with('status','Категория удалена');
    }
}
