<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterColumnPropertyEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement( "ALTER TABLE events MODIFY COLUMN properties json");
        DB::statement( "ALTER TABLE calendars MODIFY COLUMN properties json");
        DB::statement( "ALTER TABLE mc_conversations MODIFY COLUMN data json");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement( "ALTER TABLE events MODIFY COLUMN properties varchar(255)");
        DB::statement( "ALTER TABLE calendars MODIFY COLUMN properties varchar(255)");
        DB::statement( "ALTER TABLE mc_conversations MODIFY COLUMN data text");
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
