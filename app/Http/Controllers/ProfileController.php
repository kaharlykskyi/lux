<?php

namespace App\Http\Controllers;

use App\{AppTrait\GEO, DeliveryInfo, Services\Profile, User, UserCar};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,DB,Hash,Validator};

class ProfileController extends Controller
{
    use GEO;

    protected $service;

    public function __construct()
    {
        $this->service = new Profile();
    }

    public function index(){
        $roles = DB::table('roles')->get();
        $user = User::find(Auth::id());
        $user_cars = $user->cars;
        $delivery_info = $user->deliveryInfo;
        $balance = $user->balance;
        $balance_history = $user->historyBalance;
        $orders = $this->service->getOrders(Auth::id());
        $user_phones = $user->userPhones;
        $mutual_settelement = $user->mutualSettlements;

        return view('profile.index',compact(
            'roles',
            'user_cars',
            'delivery_info',
            'orders',
            'balance',
            'balance_history',
            'user_phones',
            'mutual_settelement'
        ));
    }

    public function addCar(Request $request){
        $data = $request->post();
        return response()->json($this->service->setCar($data,Auth::id()));
    }

    public function deleteCar(Request $request){
        if (!empty($request->post('id'))){
            UserCar::where([
                ['id',$request->post('id')],
                ['user_id',Auth::id()]
            ])->delete();
        }
    }

    public function changePassword(Request $request){
        User::where('id',Auth::id())->update(['password' => Hash::make($request->new_password)]);
        return response()->json([
            'response' => 'Пароль был обновлен'
        ]);
    }

    public function changeUserInfo(Request $request){
        $data = $request->except('_token');
        return response()->json($this->service->setUserInfo(Auth::user(),$data));
    }

    public function deliveryInfo(Request $request){
        $data = $request->except('_token');
        return response()->json($this->service->serDeliveryInfo($data,Auth::id()));
    }

    public function trackOrder(Request $request){
        return response()->json($this->service->getTrackOrder($request->id,Auth::id()));
    }

    public function dopUserPhone(Request $request){
        if ($request->isMethod('post')){
            return response()->json($this->service->setDopUserPhone($request->phone,Auth::id()));
        }
        if (isset($request->del_phone)){
            return response()->json($this->service->delDopUserPhone($request->del_phone,Auth::id()));
        }
        return back();
    }

}
