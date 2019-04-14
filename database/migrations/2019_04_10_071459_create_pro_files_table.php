<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pro_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('provider_id')->nullable();
            $table->string('col_provider')->nullable();
            $table->string('data_row');
            $table->string('articles');
            $table->string('product_name');
            $table->string('brand');
            $table->string('price');
            $table->string('currency')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('stocks')->nullable();
            $table->string('static_name')->nullable();
            $table->string('static_email1')->nullable();
            $table->string('static_email2')->nullable();
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pro_files');
    }
}
