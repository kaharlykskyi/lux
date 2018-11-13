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
        DB::table('oder_status_codes')->insert(['name'=>'Не сформирован']);
        DB::table('oder_status_codes')->insert(['name'=>'Сформирован']);
        DB::table('oder_status_codes')->insert(['name'=>'Оплачен']);
    }
}
