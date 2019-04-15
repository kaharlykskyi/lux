<?php

namespace App\Http\Controllers\Admin;

use App\Discount;
use App\MutualSettlement;
use App\User;
use App\UserBalance;
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

    public function show(User $user){
        $dop_phone = $user->userPhones;
        $balance = $user->balance;
        $balance_history = $user->historyBalance;
        $mutual_settelement = $user->mutualSettlements;
        return view('admin.users.show',compact('user','dop_phone','balance','balance_history','mutual_settelement'));
    }

    public function userBalance(Request $request){
        $data = $request->post();
        $data['change'] = round((float)$data['change'],2);
        $data['type_operation'] = (int)$data['type_operation'];
        $data['user_id'] = (int)$data['user_id'];

        $user = User::find((int)$request->user_id);
        $balance = $user->balance;
        DB::transaction(function () use ($data,$balance) {
            $mutual_settelement = new MutualSettlement();
            $mutual_settelement->fill($data);
            $balance_val = isset($balance->balance)?round((float)$balance->balance,2):0 + ($mutual_settelement->change);
            $mutual_settelement->balance = $balance_val;
            $mutual_settelement->save();
            UserBalance::updateOrInsert(
                ['user_id' => (int)$data['user_id']],
                ['balance' => $balance_val]
            );
        },5);

        return back()->with('status','Операция выполнена');
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
