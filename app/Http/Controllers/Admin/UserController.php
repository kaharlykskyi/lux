<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request){
        $roles = DB::table('roles')->get();

        if ($request->isMethod('post')){
            $search = trim($request->post('search'));
            $users = User::where('name','LIKE',"%{$search}%")->paginate(30);
            return view('admin.users.index',compact('users','roles','search'));
        }

        $users = User::paginate(30);
        return view('admin.users.index',compact('users','roles'));
    }

    public function permission(Request $request){

        User::where('id',(int)$request->post('id'))->update(['permission' => $request->post('permission')]);

        return response()->json([
            'response' => 'Данные обновлены'
        ]);
    }
}
