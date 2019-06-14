<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarkupExchangeRateToProFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pro_files', function (Blueprint $table) {
            $table->unsignedInteger('exchange_range')->nullable();
            $table->json('markup')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pro_files', function (Blueprint $table) {
            $table->dropColumn(['exchange_range','markup']);
        });
    }
}
