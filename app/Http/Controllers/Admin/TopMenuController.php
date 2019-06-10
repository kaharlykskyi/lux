<?php

namespace App\Http\Controllers\Admin;

use App\TopMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
        return view('admin.menu.create');
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
        }

        $top_menu = new TopMenu();
        $top_menu->fill($data);
        if ($top_menu->save()){
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function tecdocCategory(Request $request){
        $data = DB::connection('mysql_tecdoc')
            ->table('prd')
            ->where('normalizeddescription','LIKE',"{$request->category}%")
            ->select('id','normalizeddescription','usagedescription')
            ->distinct()
            ->get();
        return response()->json($data);
    }
}
