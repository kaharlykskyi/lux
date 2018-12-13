<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            OderStatusTableSeeder::class,
            CountryTableSeeder::class,
            CityTableSeeder::class,
            UsersTableSeeder::class,
            //ProductsTableSeeder::class,
            //CartsTableSeeder::class,
            //CartProductsTableSeeder::class,
            PagesTableSeeder::class,
        ]);
    }
}
