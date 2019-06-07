<?php

namespace App\Http\Controllers\Admin;

use App\{CallOrder, Cart, FastBuy, OrderPay, ProductComment, Services\Admin\Dashboard, StoreSettings, User};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    protected $statistic_new_uses = null;

    protected $service;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->service = new Dashboard();
    }

    public function index(){
        $users_count = User::count();
        $statistic_new_uses = $this->service->getShopStat('users');
        $orders_count = Cart::whereNotIn('oder_status',[1,5])->count();
        $statistic_orders = $this->service->getShopStat('carts',[['oder_status','NOT IN',[1,5]]]);
        $statistic_fast_buy = $this->service->getShopStat('fast_buy');
        $fast_buy_count = FastBuy::count();

        return view('admin.dashboard.index',compact(
            'users_count',
            'statistic_new_uses',
            'orders_count',
            'statistic_orders',
            'statistic_fast_buy',
            'fast_buy_count'
        ));
    }

    public function importHistory(){
        $history_imports = DB::table('history_imports')->orderBy('created_at','DESC')->paginate(40);

        return view('admin.dashboard.import_history',compact('history_imports'));
    }

    public function productComment(Request $request){
        if ($request->isMethod('post')){
            if (!empty($request->comment_id)) {
                ProductComment::where('id',(int)$request->comment_id)->delete();
                return back()->with('status','Коментарий удалён');
            }
        }

        $comments = ProductComment::with(['user','product'])->orderByDesc('created_at')->paginate(30);
        return view('admin.dashboard.comment',compact('comments'));
    }

    public function shippingPayment(Request $request){
        if ($request->isMethod('post')){
            Storage::put('shipping_payment.txt',$request->content_file);
            return back()->with('status','Данные сохранены');
        }

        $info = '';
        if (file_exists(storage_path('app') . '/shipping_payment.txt')){
            $info = Storage::get('shipping_payment.txt');
        }
        return view('admin.dashboard.shipping_payment',compact('info'));
    }

    public function advertising(Request $request){
        if ($request->isMethod('post')){
            Storage::put('advertising_code.txt',$request->content_file);
            return back()->with('status','Данные сохранены');
        }

        $info = '';
        if (file_exists(storage_path('app') . '/advertising_code.txt')){
            $info = Storage::get('advertising_code.txt');
        }
        return view('admin.dashboard.advertising',compact('info'));
    }

    public function setFilterSettings(Request $request){
        if ($request->isMethod('post')){
            if ($request->status === 'all'){
                $data = $request->except('_token');
                $desc = explode(' ',$data['desc']);
                $hurl = '';
                foreach ($desc as $val){
                    $hurl .= str_replace(['[',']'],'_',$this->transliterateRU($val));
                }
                DB::table('filter_settings')->insert([
                    'filter_id' => $data['id'],
                    'description' => $data['desc'],
                    'hurl' => $hurl
                ]);

                return response()->json([
                    'save' => true
                ]);
            }
            if ($request->status === 'use'){
                $data = $request->except('_token');
                $insert_data = [];
                foreach ($data as $k => $item){
                    $buff = explode('_', $k);
                    $insert_data[(int)$buff[1]][$buff[0]] = $item;
                }

                foreach ($insert_data as $k => $item){
                    DB::table('filter_settings')->where('id',(int)$k)->update([
                        'hurl' => $item['hurl'],
                        'use' => isset($item['use'])?1:0
                    ]);
                }

                return back()->with('status','Данные сохранены');
            }
        }

        $all_filter_settings = null;
        if ($request->status === 'all'){
            if (Cache::has('all_filter_settings')){
                $all_filter_settings = Cache::get('all_filter_settings');
            } else{
                $all_filter_settings = DB::connection('mysql_tecdoc')
                    ->table('article_attributes')
                    ->select('id','description')
                    ->groupBy('id','description')
                    ->distinct()
                    ->get();
                Cache::forever('all_filter_settings',$all_filter_settings);
            }
            $all_filter_settings = $this->arrayPaginator($all_filter_settings->toArray(),$request,50);
        }

        $status = $request->status;
        $use_filters = DB::table('filter_settings')->distinct()->get();
        return view('admin.dashboard.filter_setting',compact('use_filters','status','all_filter_settings'));
    }

    public function callOrder(Request $request){
        CallOrder::where('id',(int)$request->id)->update(['status' => (int)$request->status === 0?1:0]);
        if ($request->isMethod('post')){
            return response()->json('Данные обновлены');
        }

        $call_orders = CallOrder::orderBy('status')->orderBy('created_at','desc')->paginate(50);
        return view('admin.dashboard.call_oder',compact('call_orders'));
    }

    public function payMass(Request $request){
        $pay_mass = OrderPay::with(['user' => function($query){
            $query->with(['type_user','deliveryInfo','userCity']);
        }])
            ->where('success_pay','true')
            ->where('cart_id',isset($request->oder_id)?'=':'<>',isset($request->oder_id)?$request->oder_id:null)
            ->where('created_at',isset($request->date_pay_start)?'>=':'<>',isset($request->date_pay_start)?$request->date_pay_start:null)
            ->where('created_at',isset($request->date_pay_end)?'<=':'<>',isset($request->date_pay_end)?$request->date_pay_end:null)
            ->where('price_pay',isset($request->date_price_start)?'>=':'<>',isset($request->date_price_start)?$request->date_price_start:null)
            ->where('price_pay',isset($request->date_price_end)?'<=':'<>',isset($request->date_price_end)?$request->date_price_end:null)
            ->paginate(50);

        OrderPay::where('seen',0)->update(['seen' => 1]);
        return view('admin.dashboard.pay_mass',compact('pay_mass'));
    }

    public function companySettings(Request $request){
        $type = $request->type;
        $data = $request->except(['type','_token']);
        if (StoreSettings::where('type',$type)->exists()){
            StoreSettings::where('type',$type)->update([
                'settings' => json_encode($data)
            ]);
        }else{
            $settings = new StoreSettings();
            $settings->fill([
                'type' => $type,
                'settings' => json_encode($data)
            ]);
            $settings->save();
        }
        return response()->json(true);
    }
}
