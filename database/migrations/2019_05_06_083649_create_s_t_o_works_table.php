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
            $table->unsignedInteger('sto_check_id');
            $table->string('article_operation');
            $table->string('name');
            $table->unsignedTinyInteger('count')->default(1);
            $table->decimal('price',9,2)->default(0);
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('sto_check_id')->references('id')->on('s_t_o_сhecks')->onDelete('CASCADE');
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
