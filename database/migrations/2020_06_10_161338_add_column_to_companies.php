<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->text('company_logo')->nullable()->after('long_description');
            $table->json('contact')->nullable()->after('long_description');
            $table->text('address')->nullable()->after('long_description');
            $table->json('others')->nullable()->after('long_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            if(Schema::hasColumn('companies', 'company_logo')) $table->dropColumn('company_logo');
            if(Schema::hasColumn('companies', 'contact')) $table->dropColumn('contact');
            if(Schema::hasColumn('companies', 'address')) $table->dropColumn('address');
            if(Schema::hasColumn('companies', 'others')) $table->dropColumn('others');
        });
    }
}
