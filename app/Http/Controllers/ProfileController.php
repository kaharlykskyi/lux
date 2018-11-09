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
        $delivery_info = DB::table('delivery_info')->where('user_id', Auth::user()->id)->first();
        $oders = DB::table('payments')->where('user_id', Auth::user()->id)->get();

        return view('profile.index',compact('roles','user_cars','delivery_info','oders'));
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
        $data = $request->except('_token');

        $validate = Validator::make($data,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|' . ((Auth::user()->email !== $data['email']) ? 'unique:users':''),
            'sername' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'country' => 'required',
            'city' => 'required',
            'role' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ]);
        }

        if (Auth::user()->country !== $data['country']){
            $country = $this->parseCountry($data['country']);
            $data['country'] = $country->id;

            if (Auth::user()->city !== $data['city']){
                $city = $this->parseCity($data['city'],$country->id);
                $data['city'] = $city->name;
            }
        } else {
            if (Auth::user()->city !== $data['city']){
                $country = DB::table('country')->where('name','=',Auth::user()->country)->first();
                $city = $this->parseCity($data['city'],$country->id);
                $data['city'] = $city->name;
            }
        }

        DB::table('users')->where('id',Auth::user()->id)->update($data);

        return response()->json([
            'response' => 'Данные обновлены'
        ]);
    }

    public function deliveryInfo(Request $request){
        $data = $request->except('_token');
        $delivery_info = DB::table('delivery_info')->where('user_id', Auth::user()->id)->first();

        if($delivery_info->delivery_country !== $data['delivery_country']){
            $country = $this->parseCountry($data['delivery_country']);
            $data['delivery_country'] = $country->name;
            $city = $this->parseCity($data['delivery_city'],$country->id);
            $data['delivery_city'] = $city->name;
        } else {
            if($delivery_info->delivery_city !== $data['delivery_city']){
                $country = DB::table('country')->where('name', Auth::user()->country)->first();
                $city = $this->parseCity($data['delivery_city'],$country->id);
                $data['delivery_city'] = $city->name;
            }
        }


        if(DB::table('delivery_info')->where('user_id',Auth::user()->id)->exists()){
            DB::table('delivery_info')->where('user_id',Auth::user()->id)->update($data);
        } else {
            $data['user_id'] = Auth::user()->id;
            DB::table('delivery_info')->insert($data);
        }

        return response()->json([
            'response' => 'Данные сохранены'
        ]);
    }
}
