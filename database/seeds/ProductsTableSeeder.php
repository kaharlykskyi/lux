<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'test_products',
            'alias' => 'test-products',
            'short_description' => 'Короткое описание',
            'full_description' => 'Полное описание',
            'price' => 200.00,
            'old_price' => 300.00,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
        DB::table('products')->insert([
            'name' => 'test_products-2',
            'alias' => 'test-products-2',
            'short_description' => 'Короткое описание',
            'full_description' => 'Полное описание',
            'price' => 500.00,
            'old_price' => 800.00,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
