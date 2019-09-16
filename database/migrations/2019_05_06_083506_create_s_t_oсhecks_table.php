<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSTOсhecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_t_o_сhecks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sto_clint_id');
            $table->string('acceptor')->nullable();
            $table->date('application_date')->nullable();
            $table->date('date_compilation')->nullable();
            $table->string('place')->nullable();
            $table->text('info_for_user')->nullable();
            $table->string('price_abc')->nullable();
            $table->decimal('sum',9,2)->default(0);
            $table->string('mileage')->default(0);
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
        Schema::dropIfExists('s_t_oсhecks');
    }
}
