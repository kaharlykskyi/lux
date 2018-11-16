<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'sername'=>str_random(10),
            'name'=>'admin',
            'last_name'=>str_random(10),
            'email'=>'admin@mail.com',
            'phone'=>'380988950264',
            'country'=>1,
            'city'=>1,
            'password' => Hash::make('123456'),
            'role'=>1,
            'permission'=>'admin',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
