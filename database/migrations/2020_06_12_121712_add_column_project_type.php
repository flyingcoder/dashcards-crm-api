<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnProjectType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('projects', function (Blueprint $table) {
            $table->string('type')->default('project')->after('status');
            $table->json('props')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            if(Schema::hasColumn('projects', 'type')) $table->dropColumn('type');
            if(Schema::hasColumn('projects', 'props')) $table->dropColumn('props');
        });
    }
}
