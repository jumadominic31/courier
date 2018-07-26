<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNuinvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nuinvoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_num');
            $table->integer('company_id');
            $table->integer('parent_company_id');
            $table->date('month');
            $table->integer('contract_id');
            $table->integer('min_txns');
            $table->integer('total_txns');
            $table->float('min_charge', 10, 2);
            $table->float('extra_charge', 10, 2);
            $table->float('subtotal_charge', 10, 2);
            $table->float('discount', 10, 2);
            $table->float('total_charge', 10, 2);
            $table->float('vat', 10, 2);
            $table->float('paid', 10, 2);
            $table->float('bal', 10, 2);
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
        Schema::dropIfExists('nuinvoices');
    }
}
