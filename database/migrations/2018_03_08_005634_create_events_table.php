<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("calendars")
                  ->onDelete("cascade");
            $table->string('title');
            $table->integer('all_day');
            $table->date('start');
            $table->date('end');
            $table->string('description')->nullable();
            $table->string('properties')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('events');
    }
}
