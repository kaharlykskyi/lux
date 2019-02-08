<?php

namespace App\Http\Controllers\Admin;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ShowBrandController extends Controller
{
    public function index(Request $request){
        $tecdok = new Tecdoc('mysql_tecdoc');

        if ($request->isMethod('post')){
            $data = $request->except('_token');
            DB::table('show_brand')->truncate();
            foreach ($data as $k => $item){
                $brand_data = explode('_',$k,3);
                DB::table('show_brand')->insert([
                    'brand_id' => (int)$brand_data[1],
                    'description' => $item,
                    'matchcode' => $brand_data[2],
                    'ispassengercar' => ($brand_data[0] === 'passenger')?'true':'false',
                    'iscommercialvehicle' => ($brand_data[0] === 'commercial')?'true':'false',
                ]);
            }
            return back()->with('status','Данные сохранены');
        }

        $tecdok->setType('passenger');
        $passenger_brands = $tecdok->getBrands();
        $tecdok->setType('commercial');
        $commercial_brands = $tecdok->getBrands();

        $passenger_brands_show = DB::table('show_brand')->where('ispassengercar','=','true')->get();
        $commercial_brands_show = DB::table('show_brand')->where('iscommercialvehicle','=','true')->get();


        return view('admin.brands.index',compact('passenger_brands','commercial_brands','passenger_brands_show','commercial_brands_show'));
    }
}
