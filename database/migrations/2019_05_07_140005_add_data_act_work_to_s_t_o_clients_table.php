<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataActWorkToSTOClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_t_o_clients', function (Blueprint $table) {
            $table->string('acceptor')->nullable();
            $table->date('application_date')->nullable();
            $table->date('date_compilation')->nullable();
            $table->string('car_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('place')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('s_t_o_clients', function (Blueprint $table) {
            $table->dropColumn(['acceptor','application_date','date_compilation','car_name','phone','place']);
        });
    }
}
