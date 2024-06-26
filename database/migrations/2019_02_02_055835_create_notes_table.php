<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->mediumtext('content');
            $table->datetime('remind_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('note_user', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('note_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("notes")
                  ->onDelete("cascade");

            $table->integer('user_id')
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");

            $table->timestamps();

            $table->boolean('is_pinned')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_user');
        Schema::dropIfExists('notes');
    }
}
