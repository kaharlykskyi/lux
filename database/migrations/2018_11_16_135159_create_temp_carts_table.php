<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_carts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session_id');
            $table->unsignedInteger('product_id');
            $table->unsignedTinyInteger('count')->default(1);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_carts');
    }
}
