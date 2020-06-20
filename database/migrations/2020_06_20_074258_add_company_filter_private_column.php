<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyFilterPrivateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('is_private')->default(0)->after('name');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->json('props')->nullable()->after('telephone');
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
            $table->dropColumn('is_private');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('props');
        });
    }
}
