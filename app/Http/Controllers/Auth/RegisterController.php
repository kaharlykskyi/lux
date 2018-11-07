<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'sername' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'country' => 'required',
            'city' => 'required',
            'role' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (isset($data['country'])){
            $country = $this->parseCountry($data['country']);
        }
        if (isset($data['city']) && isset($country)){
            $city = $this->parseCity($data['city'],$country->id);
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'sername' => $data['sername'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'country' => $country->id,
            'city' => $city->id,
            'role' => (integer)$data['role']
        ]);
    }

    public function showRegistrationForm()
    {
        $roles = DB::table('roles')->get();
        return view('auth.register',compact('roles'));
    }

    public function parseCountry($country){
        $buff = explode(' ',$country,2);
        $country_name = $buff[0];
        $buff = explode('/',str_replace(['(',')'],'',$buff[1]));
        $iso = isset($buff[0])?$buff[0]:null;
        $iso3 = isset($buff[1])?$buff[1]:null;
        $flag = isset($iso)? "https://www.countryflags.io/{$iso}/flat/64.png":null;
        if (DB::table('country')->where('name','=',$country_name)->exists()){
            return DB::table('country')->where('name','=',$country_name)->first();
        } else {
            DB::table('country')->insert([
                'name' => $country_name,
                'flag' => $flag,
                'alpha2' => $iso,
                'alpha3' => $iso3
            ]);
            return DB::table('country')->where('name','=',$country_name)->first();
        }
    }

    public function parseCity($city,$id_country){
        if (DB::table('city')->where('name','=',$city)->exists()){
            return DB::table('city')->where('name','=',$city)->first();
        } else {
            DB::table('city')->insert([
                'name' => $city,
                'id_country' => $id_country,
            ]);
            return DB::table('city')->where('name','=',$city)->first();
        }
    }
}
