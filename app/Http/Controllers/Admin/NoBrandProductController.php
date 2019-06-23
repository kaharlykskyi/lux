<?php

namespace App\Http\Controllers\Admin;

use App\NoBrandProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class NoBrandProductController extends Controller
{
    public function index(Request $request){
        if ($request->isMethod('post')){

        }

        $no_brands = NoBrandProduct::select('brand',DB::raw('COUNT(id) AS count_product'))
            ->groupBy('brand')
            ->paginate(40);
        return view('admin.no_brand.products',compact('no_brands'));
    }
}
