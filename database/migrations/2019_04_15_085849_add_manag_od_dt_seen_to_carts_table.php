<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagOdDtSeenToCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedInteger('manager_id')->nullable();
            $table->timestamp('oder_dt')->nullable();
            $table->unsignedTinyInteger('seen')->default(0);

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('manager_id');
            $table->dropColumn(['manager_id','oder_dt','seen']);
        });
    }
}
