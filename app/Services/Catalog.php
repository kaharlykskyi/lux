<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 07.03.2019
 * Time: 18:25
 */

namespace App\Services;


use Illuminate\Support\Facades\DB;

class Catalog
{
    public function getMinPrice($level,$param){
        switch ($level){
            case 'search_str':
                $min = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.articles'),'LIKE',"%{$param['str']}%")
                    ->orWhere(DB::raw('p.name'),'LIKE',"%{$param['str']}%")
                    ->select(DB::raw(' MIN(p.price) AS min'))
                    ->get();
                return round($min[0]->min,2);
                break;
        }
    }

    public function getMaxPrice($level,$param){
        switch ($level){
            case 'search_str':
                $max = DB::table(DB::raw(config('database.connections.mysql.database').'.products AS p'))
                    ->where(DB::raw('p.articles'),'LIKE',"%{$param['str']}%")
                    ->orWhere(DB::raw('p.name'),'LIKE',"%{$param['str']}%")
                    ->select(DB::raw(' MAX(p.price) AS max'))
                    ->get();
                return round($max[0]->max,2);
                break;
        }
    }

    public function getBrands($level,$param){

    }

}