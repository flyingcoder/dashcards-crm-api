<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_user', function (Blueprint $table) {
            $table->integer('user_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");
            $table->integer('activity_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("activity_log")
                  ->onDelete("cascade");
            $table->datetime('read_at')->nullable();
            $table->primary(['user_id', 'activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_user');
    }
}
