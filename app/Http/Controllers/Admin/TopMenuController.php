<?php

namespace App\Http\Controllers\Admin;

use App\{AllCategoryTree, TopMenu, Http\Controllers\Controller};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TopMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top_menu = TopMenu::paginate(30);
        return view('admin.menu.index',compact('top_menu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_category = AllCategoryTree::where('level',1)->get();
        return view('admin.menu.create',compact('all_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token','search_category']);

        if (isset($data['show_menu'])){
            $data['show_menu'] = (int)$data['show_menu'];
        }else{
            $data['show_menu'] = 0;
        }

        $top_menu = new TopMenu();
        $top_menu->fill($data);
        if ($top_menu->save()){
            Cache::forget('top_menu');
            return redirect()->route('admin.top_menu.index')->with('status','Данные сохранены');
        } else{
            return redirect()->back()->with('status','Проверте форму на коректность данных');
        }
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
        $top_menu = TopMenu::findOrFail($id);
        $all_category = AllCategoryTree::where('level',1)->get();
        return view('admin.menu.edit',compact('top_menu','all_category'));
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
        $data = $request->except(['_token','search_category']);

        if (isset($data['show_menu'])){
            $data['show_menu'] = (int)$data['show_menu'];
        }else{
            $data['show_menu'] = 0;
        }
        $top_menu = TopMenu::findOrFail($id);
        $top_menu->fill($data);
        if ($top_menu->update()){
            Cache::forget('top_menu');
            return redirect()->back()->with('status','Данные сохранены');
        }else{
            return redirect()->back()->with('status','Ошибка');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Cache::forget('top_menu');
        TopMenu::destroy($id);
        return back()->with('status','Удаление успешно');
    }
}
