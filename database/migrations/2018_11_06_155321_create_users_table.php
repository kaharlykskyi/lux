<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sername');
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->unsignedInteger('country')->nullable();
            $table->unsignedInteger('city')->nullable();
            $table->unsignedInteger('role')->nullable();
            $table->string('logo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role')->references('id')->on('roles')->onDelete('SET NULL');
            $table->foreign('country')->references('id')->on('country')->onDelete('SET NULL');
            $table->foreign('city')->references('id')->on('city')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
