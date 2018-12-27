<?php

namespace App\Http\Controllers;

use App\TecDoc\Tecdoc;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }

    public function subcategory(Request $request){
        $tecdoc = new Tecdoc('mysql_tecdoc');
        return response()->json([
            'subCategory' => $tecdoc->getPrd(4,$request->category)
        ]);
    }
}
