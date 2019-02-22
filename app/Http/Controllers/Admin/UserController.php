<?php

namespace App\Http\Controllers\Admin;

use App\Discount;
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
        $discount = Discount::get();
        return view('admin.users.index',compact('users','roles','discount'));
    }

    public function permission(Request $request,User $user){

        $user->update(['permission',$request->permission]);
        if ($user->save()){
            return response()->json([
                'response' => 'Данные обновлены'
            ]);
        }else{
            return response()->json([
                'response' => 'Error'
            ]);
        }
    }

    public function setDiscount(Request $request,User $user){

        $user->update(['discount_id' => ($request->discount_id !== 'null')?$request->discount_id:null]);
        if ($user->save()){
            return response()->json([
                'response' => 'Скидка назначена'
            ]);
        }else{
            return response()->json([
                'response' => 'Error'
            ]);
        }
    }
}
