<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSTOClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s_t_o_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fio');
            $table->string('num_auto');
            $table->string('brand');
            $table->unsignedInteger('mileage')->default(0);
            $table->string('vin');
            $table->timestamp('data')->useCurrent();
            $table->decimal('sum',9,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('s_t_o_clients');
    }
}
