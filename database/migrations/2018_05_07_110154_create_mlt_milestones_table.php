<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMltMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mlt_milestones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('days');
            $table->integer('percentage');
            $table->integer('milestone_template_id')->unsigned()->foreign()->references("id")->on("milestone_templates")->onDelete("cascade");
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
        Schema::dropIfExists('mlt_milestones');
    }
}
