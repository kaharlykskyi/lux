<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cart_products')->insert([
            'cart_id' => 1,
            'product_id' => 1,
            'count' => 2,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
        DB::table('cart_products')->insert([
            'cart_id' => 1,
            'product_id' => 2,
            'count' => 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
