<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReviseServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('projects', function (Blueprint $table) {
            if(Schema::hasColumn('projects', 'service_id')) {
                $table->dropForeign('projects_service_id_foreign');
                $table->dropColumn('service_id');
            }
            $table->dateTime('end_at')->nullable()->change();
        });

        // Schema::table('services', function (Blueprint $table) {
        //     $table->text('business_name')->nullable();
        //     $table->longText('description')->nullable()->after('name');
        //     $table->text('icon')->nullable();
        //     $table->string('status')->default('active');//active,inactive, paused
        //     $table->integer('company_id')->unsigned()->nullable()->index();
        //     $table->foreign('company_id')->references('id')->on('companies');
        //     $table->json('props')->nullable()->after('name');
        // });

        Schema::enableForeignKeyConstraints();
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
