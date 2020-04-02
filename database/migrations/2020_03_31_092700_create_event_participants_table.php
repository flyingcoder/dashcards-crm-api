<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('event_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("events")
                  ->onDelete("cascade");

            $table->integer('user_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");

            $table->integer('added_by')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");
                  
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
        Schema::dropIfExists('event_participants');
    }
}
