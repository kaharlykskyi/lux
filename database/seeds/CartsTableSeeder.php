<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('carts')->insert([
            'user_id' => 1,
            'oder_status' => 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);
    }
}
