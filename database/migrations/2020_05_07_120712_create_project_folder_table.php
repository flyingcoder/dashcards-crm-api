<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_folders', function (Blueprint $table) {
            $table->increments('id');
            $table->text('folder_id');
            $table->string('name')->nullable();
            $table->string('source')->nullable()->default('google-drive');
            $table->json('properties')->nullable();
            $table->integer('project_id')->unsigned()->nullable()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_folders');
    }
}
