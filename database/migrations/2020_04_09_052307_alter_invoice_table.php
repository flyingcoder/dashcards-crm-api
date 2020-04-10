<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('invoices')->truncate();
        
        Schema::table('invoices', function (Blueprint $table) {
            if(Schema::hasColumn('invoices', 'billed_from')) $table->dropColumn('billed_from');
            if(Schema::hasColumn('invoices', 'billed_to')) $table->dropColumn('billed_to');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('billed_from')->nullable()->unsigned();
            $table->foreign('billed_from')->references('id')->on('users');

            $table->text('terms')->nullable()->change();

            $table->integer('billed_to')->nullable()->unsigned();
            $table->foreign('billed_to')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_billed_from_foreign');
            $table->dropForeign('invoices_billed_to_foreign');
            $table->string('billed_from')->change();
            $table->string('billed_to')->change();
            $table->string('terms')->change();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
