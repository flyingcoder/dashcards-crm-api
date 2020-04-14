<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTimerChangePropertiesToJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('timers', function (Blueprint $table) {
            if(Schema::hasColumn('timers', 'properties')) $table->dropColumn('properties');
        });

        Schema::table('timers', function (Blueprint $table) {
            $table->json('properties')->nullable()->after('causer_type');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timers', function (Blueprint $table) {
            $table->text('properties')->nullable()->change();
        });
    }
}
