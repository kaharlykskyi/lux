<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request){
        $page = Page::where('alias',$request->alias)->first();

        return view('page.index',compact('page'));
    }
}
