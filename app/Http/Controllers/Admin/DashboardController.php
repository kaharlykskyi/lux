<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        return view('admin.dashboard.index');
    }

    public function importHistory(){
        $history_imports = DB::table('history_imports')->orderBy('created_at')->paginate(40);

        return view('admin.dashboard.import_history',compact('history_imports'));
    }
}
