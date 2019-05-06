<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSTOWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_t_o_works', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sto_clint_id');
            $table->string('article_operation');
            $table->string('name');
            $table->unsignedTinyInteger('count')->default(1);
            $table->decimal('price',9,2)->default(0);
            $table->decimal('price_discount',9,2)->default(0);
            $table->timestamps();

            $table->foreign('sto_clint_id')->references('id')->on('s_t_o_clients')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_t_o_works');
    }
}
