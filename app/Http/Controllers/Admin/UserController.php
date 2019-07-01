<?php

namespace App\Http\Controllers\Admin;

use App\CartProduct;
use App\DeliveryInfo;
use App\Discount;
use App\MutualSettlement;
use App\Role;
use App\TecDoc\Tecdoc;
use App\User;
use App\UserBalance;
use App\UserCar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        parent::__construct();
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        $roles = DB::table('roles')->get();

        $filter = [];
        if (isset($request->user_fio) && !empty($request->user_fio)) $filter[] = ['fio','LIKE',"%{$request->user_fio}%"];
        if (isset($request->user_phone) && !empty($request->user_phone)) $filter[] = ['phone','LIKE',"%{$request->user_phone}%"];
        if (isset($request->user_email) && !empty($request->user_email)) $filter[] = ['email','LIKE',"%{$request->user_email}%"];

        $users = User::with('cars')->where($filter)->paginate(50);
        $discount = Discount::get();
        return view('admin.users.index',compact('users','roles','discount'));
    }

    public function show(Request $request,User $user){
        if ($request->isMethod('post')){
            $data = $request->except('_token');
            DeliveryInfo::updateOrInsert(
                ['user_id' => $user->id],
                [
                    'delivery_country' => $data['delivery_country'],
                    'delivery_city' => $data['delivery_city'],
                    'delivery_department' => $data['delivery_department']
                ]
            );
            $user->fill($data);
            $user->update();
            return redirect()->back();
        }

        $dop_phone = $user->userPhones;
        $balance = $user->balance;
        $balance_history = $user->historyBalance;
        $mutual_settelement = $user->mutualSettlements;
        $location = $user->deliveryInfo;
        return view('admin.users.show',compact('user','dop_phone','balance','balance_history','mutual_settelement','location'));
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
            $balance_val = isset($balance->balance)?round((float)$balance->balance,2) + ((float)$mutual_settelement->change):0 + ((float)$mutual_settelement->change);
            $mutual_settelement->balance = $balance_val;
            $mutual_settelement->save();
            UserBalance::updateOrInsert(
                ['user_id' => (int)$data['user_id']],
                ['balance' => $balance_val]
            );
        },5);

        return back()->with('status','Операция выполнена');
    }

    public function permission(Request $request){
        if (!empty($request->user_id) && isset($request->permission)) {
            $user = User::find((int)$request->user_id);
            if ($user->permission === 'admin'){
                return response()->json([
                    'response' => 'Нельзя менять права администратора'
                ]);
            }
            User::where('id',(int)$request->user_id)->update(['permission' => $request->permission]);
            return response()->json([
                'response' => 'Данные обновлены'
            ]);
        }
        return response()->json([
            'response' => 'Error'
        ]);
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

    public function garageShow(Request $request,User $user){
        if ($request->has('delete_car')){
            UserCar::destroy((int)$request->delete_car);
            return back();
        }
        if ($request->has('car')){
            $car = UserCar::find((int)$request->car);
            $this->tecdoc->setType($car->type_auto);
            $marka = $this->tecdoc->getBrandById((int)$car->brand_auto);
            $model = $this->tecdoc->getModelById((int)$car->model_auto);
            $modif = $this->tecdoc->getModificationById((int)$car->modification_auto);
            return view('admin.users.car_info',compact('user','marka','model','modif','car'));
        }
        $cars = $user->cars;
        return view('admin.users.garage',compact('user','cars'));
    }

    public function garageAdd(Request $request,User $user){
        if ($request->isMethod('post')){
            $data = $request->except('_token');
            $user_car = new UserCar();
            $user_car->fill($data);
            if ($user_car->save()){
                return redirect()->route('admin.user.garage',$user->id);
            }else{
                return redirect()->back()->withInput();
            }

        }

        return view('admin.users.add_car',compact('user'));
    }

    public function updateCar(Request $request,User $user){
        $data = $request->except('_token');
        $car = UserCar::findOrFail($data['id']);
        $car->fill($data);
        $car->update();
        return redirect()->back();
    }

    public function userCart(Request $request){
        if ($request->has('delete_product')){
            CartProduct::destroy((int)$request->delete_product);
            return back()->with('status','Товар удалён');
        }

        $filters = [];
        if (isset($request->cart_id) && !empty($request->cart_id)) $filters[] = ['cart_products.cart_id','=',$request->cart_id];
        if (isset($request->client_id) && !empty($request->client_id)) $filters[] = ['carts.user_id','=',(int)$request->client_id === 0?null:$request->client_id];
        if (isset($request->name_product) && !empty($request->name_product)) $filters[] = ['products.name','LIKE',"%{$request->name_product}%"];
        if (isset($request->date_add_start) && !empty($request->date_add_start)) $filters[] = ['cart_products.created_at','>=',$request->date_add_start];
        if (isset($request->date_add_end) && !empty($request->date_add_end)) $filters[] = ['cart_products.created_at','<=',$request->date_add_end];

        $users_cart_product = CartProduct::with(['cart' => function($query){
            $query->with(['client' =>
                function($query){
                    $query->with(['type_user','deliveryInfo']);
                }]);
            },'product'])
            ->join('carts','carts.id','=','cart_products.cart_id')
            ->join('products','products.id','=','cart_products.product_id')
            ->where('carts.oder_status','=',1)
            ->where($filters)
            ->select('cart_products.*')
            ->orderByDesc('cart_products.created_at')
            ->paginate(50);
        $clients = User::all();
        return view('admin.users.users_cart',compact('users_cart_product','clients'));
    }

    public function createUser(Request $request){
        if (Auth::user()->permission !== 'admin'){
            return back()->with('status','Недостаточно прав для даного действия');
        }

        if ($request->isMethod('post')){

            $data = $request->except('_token');

            $validate = Validator::make($data, [
                'fio' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/i',
                'role' => 'required'
            ]);

            if ($validate->fails()) {
                return redirect()->back()
                    ->withErrors($validate)
                    ->withInput();
            }

            $data['password'] = Hash::make($data['password']);
            $data['permission'] = 'user';


            $user = new User();
            $user->fill($data);

            if ($user->save()){
                return redirect()->route('admin.users')->with('status','Пользователь добавлен');
            } else{
                return back()->withInput();
            }
        }

        $roles = Role::all();
        return view('admin.users.create',compact('roles'));
    }
}
