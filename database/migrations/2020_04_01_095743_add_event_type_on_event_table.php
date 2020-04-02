<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddEventTypeOnEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if(Schema::hasColumn('events', 'start')) $table->dropColumn('start');
            if(Schema::hasColumn('events', 'end')) $table->dropColumn('end');
            if(Schema::hasColumn('events', 'calendar_id')) $table->dropColumn('calendar_id');
            if(Schema::hasColumn('events', 'eventtypes_id')) $table->dropColumn('eventtypes_id');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->bigInteger('eventtypes_id')
                ->after('id')
                ->unsigned()
                ->nullable();

            $table->dateTime('start')->after('all_day');
            $table->dateTime('end')->after('all_day');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreign('eventtypes_id')
                ->references('id')
                ->on('event_types')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_eventtypes_id_foreign');
            if(Schema::hasColumn('events', 'event_type_id')) $table->dropColumn('eventtypes_id');
            if(Schema::hasColumn('events', 'start')) $table->dropColumn('start');
            if(Schema::hasColumn('events', 'end')) $table->dropColumn('end');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
