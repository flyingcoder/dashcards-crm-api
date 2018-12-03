<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('invoices');

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')
                  ->nullable()
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("projects")
                  ->onDelete("cascade");
            
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
        Schema::dropIfExists('invoices');
    }
}
