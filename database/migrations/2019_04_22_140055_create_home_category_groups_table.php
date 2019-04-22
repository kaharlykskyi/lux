<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_category_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('hurl')->unique();
            $table->string('key_words')->nullable();
            $table->string('img')->nullable();
            $table->string('background')->nullable();
            $table->string('categories_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_category_groups');
    }
}
