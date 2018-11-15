<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OderStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oder_status_codes')->insert(['name'=>'формируеться']);
        DB::table('oder_status_codes')->insert(['name'=>'создан']);
        DB::table('oder_status_codes')->insert(['name'=>'обработан менеджером']);
        DB::table('oder_status_codes')->insert(['name'=>'отправлен']);
        DB::table('oder_status_codes')->insert(['name'=>'отменен']);
        DB::table('oder_status_codes')->insert(['name'=>'выполнен']);
        DB::table('oder_status_codes')->insert(['name'=>'оплачен']);
    }
}
