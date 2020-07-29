<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCompanyIdToForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('forms')->truncate();

        Schema::table('forms', function (Blueprint $table) {
            $table->integer('company_id')->unsigned()->nullable()->index();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->json('props')->nullable()->after('slug');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('forms', function (Blueprint $table) {
            if(Schema::hasColumn('forms', 'company_id')) {
                $table->dropForeign('forms_company_id_foreign');
                $table->dropColumn('company_id');
            }
            $table->dropColumn('props');
        });
        Schema::enableForeignKeyConstraints();
    }
}
