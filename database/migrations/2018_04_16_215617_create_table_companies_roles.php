<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompaniesRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned()->index()->foreign()->references("id")->on("companies")->onDelete("cascade");
            $table->integer('role_id')->unsigned()->index()->foreign()->references("id")->on("roles")->onDelete("cascade");
            $table->timestamps();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_role');
    }
}
