<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('project_id')->unsigned()->nullable()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->integer('milestone_id')->unsigned()->nullable()->change();
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
        Schema::table('tasks', function (Blueprint $table) {
             if(Schema::hasColumn('projects', 'project_id')) {
                $table->dropForeign('projects_project_id_foreign');
                $table->dropColumn('project_id');
            }
        });
        Schema::enableForeignKeyConstraints();
    }
}
