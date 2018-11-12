<?php
/**
 * Created by PhpStorm.
 * User: Seliv
 * Date: 09.11.2018
 * Time: 19:12
 */

namespace App\AppTrait;


use Illuminate\Support\Facades\DB;

trait GEO
{
    public function parseCountry($country){
        $buff = explode(' ',$country,2);
        $country_name = $buff[0];
        $buff = isset($buff[1]) ? explode('/',str_replace(['(',')'],'',$buff[1])) : null;
        $iso = isset($buff[0])?$buff[0]:null;
        $iso3 = isset($buff[1])?$buff[1]:null;
        $flag = isset($iso)? "https://www.countryflags.io/{$iso}/flat/64.png":null;
        if (DB::table('country')->where('name','=',$country_name)->exists()){
            return DB::table('country')->where('name','=',$country_name)->first();
        } else {
            DB::table('country')->insert([
                'name' => $country_name,
                'flag' => $flag,
                'alpha2' => $iso,
                'alpha3' => $iso3
            ]);
            return DB::table('country')->where('name','=',$country_name)->first();
        }
    }

    public function parseCity($city,$id_country){
        if (DB::table('city')->where('name','=',$city)->exists()){
            return DB::table('city')->where('name','=',$city)->first();
        } else {
            DB::table('city')->insert([
                'name' => $city,
                'id_country' => $id_country,
            ]);
            return DB::table('city')->where('name','=',$city)->first();
        }
    }
}