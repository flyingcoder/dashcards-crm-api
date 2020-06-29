<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterServicesCampaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        DB::statement( "UPDATE projects SET type = 'campaign' WHERE type = 'service'");

        Schema::table('projects', function (Blueprint $table) {
            $table->integer('service_id')->unsigned()->nullable()->index();
            $table->foreign('service_id')->references('id')->on('services');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->json('props')->nullable();
        });


       Schema::table('services', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('name');
            $table->text('icon')->nullable();
            $table->string('status')->default('active');//active,inactive, paused
            $table->integer('company_id')->unsigned()->nullable()->index();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->json('props')->nullable()->after('name');
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

        DB::statement( "UPDATE projects SET type = 'service' WHERE type = 'campaign'");

        Schema::table('projects', function (Blueprint $table) {
            if(Schema::hasColumn('projects', 'service_id')) {
                $table->dropForeign('projects_service_id_foreign');
                $table->dropColumn('service_id');
            }
        });

        Schema::table('tasks', function (Blueprint $table) {
            if(Schema::hasColumn('tasks', 'props'))  $table->dropColumn('props');
        });

       Schema::table('services', function (Blueprint $table) {
            if(Schema::hasColumn('services', 'company_id')) {
                $table->dropForeign('services_company_id_foreign');
                $table->dropColumn('company_id');
            }
            if(Schema::hasColumn('services', 'description'))  $table->dropColumn('description');
            if(Schema::hasColumn('services', 'icon'))  $table->dropColumn('icon');
            if(Schema::hasColumn('services', 'status'))  $table->dropColumn('status');
            if(Schema::hasColumn('services', 'props'))  $table->dropColumn('props');
        });

        Schema::enableForeignKeyConstraints();
    }
}
