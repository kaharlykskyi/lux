<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_category');
            $table->unsignedInteger('tecdoc_category');

            $table->foreign('user_category')->references('id')->on('categores_group_for_cars')->onDelete('CASCADE');
            $table->foreign('tecdoc_category')->references('id')->on('all_category_trees')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_categories');
    }
}
