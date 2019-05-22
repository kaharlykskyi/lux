<?php

namespace App\Http\Controllers;

use App\AppTrait\GEO;
use App\{Cart,
    CartProduct,
    DeliveryInfo,
    Http\Controllers\Auth\LoginController,
    MutualSettlement,
    OrderPay,
    User,
    UserBalance};
use Illuminate\Foundation\Auth\{RegistersUsers};
use Illuminate\Http\Request;
use Illuminate\Support\{Carbon, Facades\Auth, Facades\DB, Facades\Hash, Facades\Validator};
use Illuminate\Validation\ValidationException;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckoutController extends Controller
{
    use RegistersUsers, GEO;

    protected $products = [];

    public function index(Request $request){
        $cart = $this->getCart($request);

        if (isset($cart)){
            $this->products = CartProduct::with('product')->where('cart_id',$cart->id)->get();
        }

        if (Auth::check()){
            $user = User::with(['discount','deliveryInfo','balance'])->find(Auth::id());
        }

        return view('checkout.index')->with([
            'cart' => $cart,
            'products' => $this->products,
            'user' => isset($user)?$user:null
        ]);
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
            $data['country'] = "{$country->name} ({$country->alpha2})";
        }
        if (isset($data['city']) && isset($country)){
            $city = $this->parseCity($data['city'],$country->id);
            $data['city'] = $city->name;
        }

        $user = new User();
        $user->fill($data);
        if($user->save()){
            $deliveryInf = new DeliveryInfo();
            $deliveryInf->fill([
                'user_id' => $user->id,
                'delivery_country' => $data['country'],
                'delivery_city' =>  $data['city'],
                'phone' => $data['phone'],
                'delivery_service' => $data['delivery_service'],
                'delivery_department' => $data['delivery_department']
            ]);

            $deliveryInf->save();

            Cart::where('session_id',$request->cookie('cart_session_id'))->update([
                'user_id' => $user->id,
                'oder_status' => 2,
                'session_id' => null,
                'oder_dt' => Carbon::now()
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

        User::where('id',Auth::id())->update([
            'sername' => $data['sername'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        $country = $this->parseCountry($data['country']);
        $city= $this->parseCity($data['city'],$country->id);

        $delivery_inf_data = [
            'phone' => $data['phone'],
            'delivery_country' => $country->name,
            'delivery_city' => $city->name,
            'delivery_service' => $data['delivery_service'],
            'delivery_department' => $data['delivery_department'],
            'user_id' => Auth::id()
        ];

        if (DeliveryInfo::where('user_id',Auth::id())->exists()){
            DeliveryInfo::where('user_id',Auth::id())->update($delivery_inf_data);
        }else{
            $delivery_inf = new DeliveryInfo();
            $delivery_inf->fill($delivery_inf_data)->save();
        }

        $cart = Cart::find($data['order_id']);

        if (isset($cart)){
            $products = $cart->cartProduct()->get();
            $user = User::find(Auth::id());
            $user_balance = isset($user->balance)?$user->balance->balance:null;
            $discount = $user->discount;
            $sum = 0;

            foreach ($products as $product){
                $sum += (double)$product->price * (integer)$product['pivot']['count'];
            }

            if (isset($discount)){
                $sum = $sum - ($sum * (int)$discount->percent / 100);
            }

            if ($user_balance >= $sum && $sum > 0){
                $pay_order = new OrderPay();
                $pay_order->fill([
                    'cart_id' => $data['order_id'],
                    'user_id' => Auth::id(),
                    'success_pay' => 'false',
                    'price_pay' => $sum
                ]);

                if ($pay_order->save()){
                    DB::transaction(function () use ($pay_order, $sum, $user_balance) {
                        UserBalance::where('user_id',Auth::id())->update([
                            'balance' => $user_balance - $sum,
                        ]);

                        $mutual_settelement = new MutualSettlement();
                        $mutual_settelement->fill([
                            'description' => 'Оплата заказа №' . $pay_order->id,
                            'type_operation' => 4,
                            'user_id' => Auth::id(),
                            'currency' => 'UAH',
                            'change' => -$sum,
                            'balance' => $user_balance - $sum
                        ]);
                        $mutual_settelement->save();
                        $pay_order->update(['success_pay' => 'true']);
                    },5);
                }
            }

            Cart::where([
                ['user_id',Auth::id()],
                ['oder_status',1]
            ])->update(['oder_status' => 2,'session_id' => null,'oder_dt' => Carbon::now()]);

            try{
                $this->sendTelegramNotification($products,$user,$sum,$data['order_id']);
            }catch (\Exception $e){
                report($e);
            }
        }

        return redirect()->route('profile');
    }

    private function sendTelegramNotification($products,$user,$sum,$oder_id){
        Telegram::sendMessage([
            'chat_id' => config('app.telegram_channel_id'),
            'parse_mode' => 'HTML',
            'text' => $this->makeTemplateMassage($products,$user,$sum,$oder_id)
        ]);
    }

    private function makeTemplateMassage($products, $user, $sum, $oder_id){
        $template = "<b>Новый заказ:</b> {$oder_id} \n<b>Заказчик:</b> {$user->email}\n<b>ФИО:</b> {$user->sername} {$user->name} {$user->last_name}\n<b>Телефон:</b> {$user->phone}\n<b>Заказанные товары</b>:\n";
        foreach ($products as $k => $product){
            $template .= $k + 1 .") {$product->articles}| {$product->name} | {$product->price}грн.\n";
        }

        $template .= "<b>Сумма:</b> {$sum}грн.";
        return $template;
    }
}
