<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMutualSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutual_settlements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('type_operation')->default(1);
            $table->unsignedInteger('user_id');
            $table->char('currency',32)->default('UAH');
            $table->decimal('change')->default(0.00);
            $table->decimal('balance')->default(0.00);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutual_settlements');
    }
}
