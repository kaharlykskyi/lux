<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('articles',60);
            $table->unsignedInteger('brand');
            $table->string('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->decimal('price',9,2);
            $table->unsignedInteger('provider_id')->nullable();
            $table->string('count')->default(0);
            $table->decimal('old_price',9,2)->nullable();
            $table->unsignedTinyInteger('delivery_time')->default(1);
            $table->decimal('provider_price',9,2)->nullable();
            $table->string('provider_currency')->default('UAH');
            $table->json('stocks')->nullable();
            $table->unsignedTinyInteger('original')->default(0);

            $table->timestamps();
            $table->index('articles');
            $table->index('brand');
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
        Schema::dropIfExists('products');
    }
}
