<?php

namespace App\Http\Controllers;

use App\AppTrait\GEO;
use App\{Cart, DeliveryInfo, Http\Controllers\Auth\LoginController, User};
use Illuminate\Foundation\Auth\{RegistersUsers};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Validator};
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    use RegistersUsers, GEO;

    public function index(Request $request){
        $cart = $this->getCart($request);
        $products = [];
        if (isset($cart)){
            $products = DB::table('cart_products')
                ->where('cart_products.cart_id',$cart->id)
                ->join('products','products.id','=','cart_products.product_id')
                ->select('products.*','cart_products.count','cart_products.cart_id')
                ->get();
        }

        return view('checkout.index',compact('products'));
    }

    public function newUser(Request $request){
        $data = $request->post();

        $validate = Validator::make($data,[
            'delivery_service' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'sername' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'country' => 'required',
            'city' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => $validate->errors()
            ]);
        }

        $data['permission'] = 'user';
        $data['role'] = 1;
        $data['password'] = Hash::make($data['password']);

        if (isset($data['country'])){
            $country = $this->parseCountry($data['country']);
            $data['country'] = $country->id;
        }
        if (isset($data['city']) && isset($country)){
            $city = $this->parseCity($data['city'],$country->id);
            $data['city'] = $city->id;
        }

        $user = new User();
        $user->fill($data);
        if($user->save()){
            $deliveryInf = new DeliveryInfo();
            $deliveryInf->fill([
                'user_id' => $user->id,
                'delivery_country' => $country->id,
                'delivery_city' =>  $city->id,
                'phone' => $data['phone'],
                'delivery_service' => $data['delivery_service'],
                'delivery_department' => $data['delivery_department']
            ]);

            $deliveryInf->save();

            Cart::where('session_id',$request->cookie('cart_session_id'))->update([
                'user_id' => $user->id,
                'oder_status' => 2
            ]);
        }

        $this->guard()->login($user);

        return response()->json([
           'response' => $request->post()
        ]);
    }

    public function oldUser(Request $request){
        $login = new LoginController();
        try {
            $login->login($request);
        } catch (ValidationException $e) {
            if(config('debug')){
                dump($e);
            }
        }

        return redirect()->route('checkout');
    }

    public function createOder(Request $request){
        $data = $request->post();

        $validate = Validator::make($data,[
            'delivery_service' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'sername' => 'string|max:255',
            'last_name' => 'string|max:255',
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
            'country' => 'required',
            'city' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        User::where('id',Auth::user()->id)->update([
            'sername' => $data['sername'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        $country = $this->parseCountry($data['country']);
        $city= $this->parseCity($data['city'],$country->id);

        DeliveryInfo::where('user_id',Auth::user()->id)->update([
            'phone' => $data['phone'],
            'delivery_country' => $country->id,
            'delivery_city' => $city->id,
            'delivery_service' => $data['delivery_service'],
            'delivery_department' => $data['delivery_department'],
        ]);

        Cart::where('user_id',Auth::user()->id)->update(['oder_status' => 2]);

        return redirect()->route('profile');
    }
}
