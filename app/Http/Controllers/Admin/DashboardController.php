<?php

namespace App\Http\Controllers\Admin;

use App\{Cart, FastBuy, User};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $statistic_new_uses = null;

    public function index(){
        $users_count = User::count();
        $statistic_new_uses = $this->getShopStat('users');
        $orders_count = Cart::whereNotIn('oder_status',[1,5])->count();
        $statistic_orders = $this->getShopStat('carts',[['oder_status','NOT IN',[1,5]]]);
        $statistic_fast_buy = $this->getShopStat('fast_buy');
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

    /**
     * @param $table
     * @param null $option -> array [['col','filter',[param]]]
     * @return array|null
     */
    protected function getShopStat($table, $option = null){
        $data = null;
        $option_str = "";


        if (isset($option)){
            foreach ($option as $item){
                $option_str .= " AND `{$item[0]}` {$item[1]} (";
                foreach ($item[2] as $k => $i) {
                    if (count($item[2]) !== $k + 1) {
                        $option_str .= " {$i},";
                    } else {
                        $option_str .= " {$i})";
                    }

                }
            }
        }
        for($i = 0;$i < 6;$i++){
            $res = DB::select("SELECT count(*) as count_iteam, date_format(date_add(now(), interval -{$i} month), '%M') as `data_int`  FROM `{$table}`
                                WHERE  date_format(`created_at`, '%Y%m') = date_format(date_add(now(), interval -{$i} month), '%Y%m')"
                                .$option_str);
            $data[] = [
                'count' => $res[0]->count_iteam,
                'data' => $res[0]->data_int
            ];
        }

        return $data;
    }
}
