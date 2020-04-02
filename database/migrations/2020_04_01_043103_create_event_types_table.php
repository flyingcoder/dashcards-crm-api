<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('event_types')) {
            Schema::create('event_types', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->text('name');
                $table->integer('company_id')->unsigned()->nullable();
                $table->integer('created_by')->unsigned()->nullable();
                $table->json('properties');
                $table->tinyInteger('is_public');
                $table->timestamps();

                $table->foreign('company_id')
                      ->references('id')->on('companies')
                      ->onDelete('SET NULL');

                $table->foreign('created_by')
                      ->references('id')->on('users')
                      ->onDelete('SET NULL');
            });
        }

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_types');
    }
}
