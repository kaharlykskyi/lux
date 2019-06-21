<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoBrandProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_brand_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('articles',60);
            $table->char('brand', 60);
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

            $table->timestamps();
            $table->index('articles');
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
        Schema::dropIfExists('no_brand_products');
    }
}
