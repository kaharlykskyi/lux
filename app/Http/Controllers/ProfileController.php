<?php

namespace App\Http\Controllers;

use App\{AppTrait\GEO, Cart, Services\Profile, User, UserCar};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,DB,Hash};

class ProfileController extends Controller
{
    use GEO;

    protected $service;

    public function __construct(Request $request)
    {
        parent::__construct($request);
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
        $back_order = Cart::with('cartProduct')
            ->where('user_id',Auth::id())
            ->where('oder_status',5)->get();

        foreach ($user_cars as $k => $data){
            $user_cars[$k] = $this->service->getCarInfo($data);
        }

        return view('profile.index',compact(
            'roles',
            'user_cars',
            'delivery_info',
            'orders',
            'balance',
            'balance_history',
            'user_phones',
            'mutual_settelement',
            'back_order'
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
