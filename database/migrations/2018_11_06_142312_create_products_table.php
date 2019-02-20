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
            $table->char('brand', 60);
            $table->string('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->decimal('price',9,2);
            $table->string('company')->nullable();
            $table->string('count')->default(0);
            $table->decimal('old_price',9,2)->nullable();

            $table->timestamps();
            $table->index('articles');
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
