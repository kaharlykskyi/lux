<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllCategoryTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('all_category_trees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hurl')->unique();
            $table->unsignedInteger('parent_category')->nullable();
            $table->unsignedInteger('tecdoc_id')->nullable();
            $table->string('tecdoc_name')->nullable();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedTinyInteger('show');
            $table->unsignedTinyInteger('level')->default(0);

            $table->foreign('parent_category')->references('id')->on('all_category_trees')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_category_trees');
    }
}
