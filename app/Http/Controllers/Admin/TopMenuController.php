<?php

namespace App\Http\Controllers\Admin;

use App\TopMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TopMenuController extends Controller
{
    public function index(Request $request){
        DB::statement('DELETE FROM `top_menu`
                         WHERE id NOT IN (SELECT * 
                                            FROM (SELECT MIN(n.id)
                                                    FROM `top_menu` n
                                                GROUP BY n.tecdoc_title) x)');

        $tecdoc_category = cache()->remember('menu_tecdoc_category', 60*24*7, function () {
            return DB::connection('mysql_tecdoc')
                ->table('passanger_car_prd')
                ->select('assemblygroupdescription')
                ->distinct()->get();
        });
        $tecdoc_category = $this->arrayPaginator($tecdoc_category->toArray(),$request,20);
        return view('admin.menu.index',compact('tecdoc_category'));
    }

    public function edit(Request $request){

        if ($request->isMethod('post')){
            $data = $request->except('_token');
            if (isset($data['show_menu'])){
                $data['show_menu'] = 1;
            }else{
                $data['show_menu'] = 0;
            }
            $save_category = TopMenu::where('tecdoc_title',$data['tecdoc_title'])->first();
            if (isset($save_category)){
                TopMenu::where('tecdoc_title',$save_category->tecdoc_title)->update($data);
            }else{
                $top_menu = new TopMenu();
                $top_menu->fill($data);
                $top_menu->save();
            }
            return back()->with('status','Изменено');
        }

        $tecdoc_name = $request->id;
        $save_category = TopMenu::where('tecdoc_title',$tecdoc_name)->first();
        return view('admin.menu.edit',compact('tecdoc_name','save_category'));
    }
}
