<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveSheetToProFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pro_files', function (Blueprint $table) {
            $table->unsignedTinyInteger('active_sheet')->default(1);
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
            $table->dropColumn(['active_sheet']);
        });
    }
}
