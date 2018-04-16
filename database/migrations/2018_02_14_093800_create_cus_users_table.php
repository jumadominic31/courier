<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCusUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cus_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('fullname');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->integer('cus_company_id');
            $table->integer('company_id');
            $table->string('usertype');
            $table->boolean('status');
            $table->integer('updated_by');
            $table->rememberToken();
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
        Schema::dropIfExists('cus_users');
    }
}
