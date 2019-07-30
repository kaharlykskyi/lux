<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortFieldToCategoresGroupForCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categores_group_for_cars', function (Blueprint $table) {
            $table->unsignedTinyInteger('range')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categores_group_for_cars', function (Blueprint $table) {
            $table->dropColumn('range');
        });
    }
}
