<?php

namespace App\Http\Controllers;

use App\{AppTrait\GEO, User, UserCar};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,DB,Hash,Validator};

class ProfileController extends Controller
{
    use GEO;

    public function index(){
        $roles = DB::table('roles')->get();
        $user_cars = DB::table('user_cars')->where('user_id', Auth::user()->id)->get();
        $user_country = DB::table('country')->where('id', Auth::user()->country)->first();
        $city = DB::table('city')->where('id', Auth::user()->city)->first();

        return view('profile.index',compact('roles','user_cars','user_country','city'));
    }

    public function addCar(Request $request){
        $data = $request->post();

        $validate = Validator::make($data,[
            'vin_code' => 'required',
            'mark' => 'required',
            'year' => 'required|min:4',
            'type_motor' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ]);
        }

        $data['user_id'] = Auth::user()->id;

        $userCar = new UserCar();
        $userCar->fill($data);

        if ($userCar->save()){
            return response()->json([
                'response' => $userCar
            ]);
        } else {
            return response()->json([
                'errors' => 'Ошибка, попробуйте позже!'
            ]);
        }
    }

    public function changePassword(Request $request){
        $data = $request->post();

        User::where('id',Auth::user()->id)->update([
            'password' => Hash::make($data['new_password'])
        ]);

        return response()->json([
            'response' => 'Пароль был обновлен'
        ]);

    }

    public function changeUserInfo(Request $request){
        $data = $request->post();


    }

    public function shopInfo(Request $request){

    }
}
