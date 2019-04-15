<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OderStatusTableSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oder_status_codes')->insert(['name'=>'Приостановлен']);
        DB::table('oder_status_codes')->insert(['name'=>'Отказано']);
    }
}
