<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEventAddUtcEquivalent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('utc_start')->nullable()->after('start');
            $table->dateTime('utc_end')->nullable()->after('end');
            $table->string('timezone')->default('UTC')->after('all_day');
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
        Schema::disableForeignKeyConstraints();
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('utc_start_date');
            $table->dropColumn('utc_end_date');
            $table->dropColumn('timezone');
        });
        Schema::enableForeignKeyConstraints();
    }
}
