<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ColumnProjectIdTypeConversationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mc_conversations', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->integer('project_id')
                  ->nullable()
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("projects")
                  ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mc_conversations', function (Blueprint $table) {
            //
        });
    }
}
