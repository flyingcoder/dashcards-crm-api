<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilestoneTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milestone_template', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('milestone_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("milestones")
                  ->onDelete("cascade");
            $table->integer('template_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("templates")
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
        Schema::dropIfExists('milestone_template');
    }
}
