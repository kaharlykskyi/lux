<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('country')->insert([
            'name' => 'Украина',
            'flag' => 'https://www.countryflags.io/UA/flat/64.png',
            'alpha2' => 'UA',
            'alpha3' => 'UKR',
        ]);
    }
}
