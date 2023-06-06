<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('csv_export_histories', function (Blueprint $table) {
            $table->string('csv_type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('csv_export_histories', function (Blueprint $table) {
            $table->dropColumn('csv_type');
        });
    }

};
