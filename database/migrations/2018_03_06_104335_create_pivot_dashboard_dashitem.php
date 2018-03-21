<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotDashboardDashitem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_dashitem', function (Blueprint $table) {
            $table->integer('dashboard_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("dashboards")
                  ->onDelete("cascade");
            $table->integer('dashitem_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("dashitems")
                  ->onDelete("cascade");
            $table->integer('order');
            $table->primary(['dashboard_id', 'dashitem_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_dashitem');
    }
}
