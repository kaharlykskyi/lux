<?php

namespace App\Services\Admin;


use Illuminate\Support\Facades\DB;

class Dashboard
{
    /**
     * @param $table
     * @param null $option -> array [['col','filter',[param]]]
     * @return array|null
     */
    public function getShopStat($table, $option = null){
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
