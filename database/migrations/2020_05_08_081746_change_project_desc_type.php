<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProjectDescType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('projects', function (Blueprint $table) {
            $table->string('description')->change();
            $table->longText('description')->comment('Project Description')->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('description')->change();
            $table->longText('description')->comment('Task Descriptions')->change();
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

        Schema::table('projects', function (Blueprint $table) {
            $table->mediumText('description')->change();
        });
        
        Schema::table('tasks', function (Blueprint $table) {
            $table->mediumText('description')->change();
        });

        Schema::enableForeignKeyConstraints();
    }
}
