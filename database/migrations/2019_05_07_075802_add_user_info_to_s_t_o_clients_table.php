<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserInfoToSTOClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('s_t_o_clients', function (Blueprint $table) {
            $table->text('info_for_user')->nullable();
            $table->string('price_abc')->nullable();
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
            $table->dropColumn(['info_for_user','price_abc']);
        });
    }
}
