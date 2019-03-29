<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilterSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('filter_id');
            $table->string('description');
            $table->string('hurl');
            $table->unsignedTinyInteger('use')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filter_settings');
    }
}
