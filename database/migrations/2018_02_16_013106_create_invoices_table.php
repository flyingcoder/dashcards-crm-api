<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                  ->unsigned()
                  ->index()
                  ->foreign()
                  ->references("id")
                  ->on("users")
                  ->onDelete("cascade");
            $table->string('name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('phone');
            $table->string('email');
            $table->string('title');
            $table->string('description')->nullable();
            $table->integer('rate');
            $table->integer('tax');
            $table->integer('quantity');
            $table->string('notes')->nullable();
            $table->string('other_info')->nullable();
            $table->date('billed_date');
            $table->date('due_date');
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
