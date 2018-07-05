<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->date('date');
            $table->integer('open_bal');
            $table->integer('purchases');
            $table->integer('used');
            $table->integer('close_bal');
            $table->integer('updated_by');
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
        Schema::dropIfExists('token_statements');
    }
}
