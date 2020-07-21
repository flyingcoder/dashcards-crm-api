<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTaskHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_task_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('interval_type');
            $table->string('interval_at')->nullable();
            $table->json('props')->nullable();
            $table->dateTime('run_at')->nullable();
            $table->bigInteger('schedule_task_id')->unsigned()->nullable()->index();
            $table->foreign('schedule_task_id')->references('id')->on('schedule_tasks');
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
        Schema::dropIfExists('schedule_task_histories');
    }
}
