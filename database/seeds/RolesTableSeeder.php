<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(['name'=>'СТО']);
        DB::table('roles')->insert(['name'=>'Интрнет магазин']);
        DB::table('roles')->insert(['name'=>'Частное лицо']);
        DB::table('roles')->insert(['name'=>'Опт клиент']);
    }
}
