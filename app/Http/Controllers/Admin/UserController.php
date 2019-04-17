<?php

namespace App\Http\Controllers\Admin;

use App\CartProduct;
use App\Discount;
use App\MutualSettlement;
use App\TecDoc\Tecdoc;
use App\User;
use App\UserBalance;
use App\UserCar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $tecdoc;

    public function __construct()
    {
        $this->tecdoc = new Tecdoc('mysql_tecdoc');
    }

    public function index(Request $request){
        $roles = DB::table('roles')->get();

        if (isset($request->user_fio)){
            $user_fio = explode(' ',$request->user_fio);
        }

        $users = User::with('cars')->where('email','LIKE',isset($request->user_email)?"%{$request->user_email}%":'%%')
            ->where('phone','LIKE',isset($request->user_phone)?"%{$request->user_phone}%":'%%')
            ->where([
                ['sername','LIKE',isset($user_fio[0])?"%{$user_fio[0]}%":'%%'],
                ['name','LIKE',isset($user_fio[1])?"%{$user_fio[1]}%":'%%'],
                ['last_name','LIKE',isset($user_fio[2])?"%{$user_fio[2]}%":'%%'],
            ])
            ->paginate(50);
        $discount = Discount::get();
        return view('admin.users.index',compact('users','roles','discount'));
    }

    public function show(User $user){
        $dop_phone = $user->userPhones;
        $balance = $user->balance;
        $balance_history = $user->historyBalance;
        $mutual_settelement = $user->mutualSettlements;
        $location = DB::table('city')
            ->where('city.id',$user->city)
            ->join('country','country.id','=','city.id_country')
            ->select('city.name AS city','country.name AS country','country.flag')
            ->first();
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

    public function userCart(Request $request){
        if ($request->has('delete_product')){
            CartProduct::destroy((int)$request->delete_product);
            return back()->with('status','Товар удалён');
        }

        $users_cart_product = CartProduct::with(['cart' => function($query){
            $query->with(['client' =>
                function($query){
                    $query->with(['type_user','deliveryInfo','userCity']);
                }]);
            },'product'])
            ->join('carts','carts.id','=','cart_products.cart_id')
            ->join('products','products.id','=','cart_products.product_id')
            ->where('carts.oder_status','=',1)
            ->where('cart_products.cart_id',isset($request->cart_id)?'=':'<>',isset($request->cart_id)?$request->cart_id:null)
            ->where('carts.user_id',isset($request->client_id)?'=':'<>',isset($request->client_id)?(int)$request->client_id !== 0?$request->client_id:null:null)
            ->where('products.name','LIKE',isset($request->name_product)?"%{$request->name_product}%":'%%')
            ->where('cart_products.created_at',isset($request->date_add_start)?'>=':'<>',isset($request->date_add_start)?$request->date_add_start:null)
            ->where('cart_products.created_at',isset($request->date_add_end)?'<=':'<>',isset($request->date_add_end)?$request->date_add_end:null)
            ->select('cart_products.*')
            ->orderByDesc('cart_products.created_at')
            ->paginate(50);
        $clients = User::all();
        return view('admin.users.users_cart',compact('users_cart_product','clients'));
    }
}
