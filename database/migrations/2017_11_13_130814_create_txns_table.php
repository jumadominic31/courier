<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTxnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('txns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('awb_num');
            $table->integer('clerk_id');
            $table->integer('origin_id');
            $table->integer('dest_id');
            $table->integer('company_id');
            $table->integer('parcel_status_id');
            $table->integer('parcel_type_id');
            $table->float('price', 10, 2);
            $table->string('sender_name');
            $table->string('sender_id_num');
            $table->string('sender_phone');
            $table->string('receiver_name');
            $table->string('receiver_id_num');
            $table->string('receiver_phone');
            $table->integer('driver_id');
            $table->integer('vehicle_id');
            $table->integer('update_by');
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
        Schema::dropIfExists('txns');
    }
}
