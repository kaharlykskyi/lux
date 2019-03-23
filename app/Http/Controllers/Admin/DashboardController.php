<?php

namespace App\Http\Controllers\Admin;

use App\{Cart, FastBuy, ProductComment, Services\Admin\Dashboard, User};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    protected $statistic_new_uses = null;

    protected $service;

    public function __construct()
    {
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

        $comments = ProductComment::with('user')->orderByDesc('created_at')->paginate(30);
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
}
