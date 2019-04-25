<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsServicePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_service', function (Blueprint $table) {
            $table->integer('service_id')
                  ->nullable()
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("services")
                  ->onDelete("cascade");
            $table->integer('form_id')
                  ->nullable()
                  ->unsigned()
                  ->foreign()
                  ->references("id")
                  ->on("forms")
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
        //
    }
}
