<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestPickupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_pickup', function (Blueprint $table) {
            $table->id();
            $table->string('passenger_id');
            $table->string('current_latitude');
            $table->string('current_longitude');
            $table->string('to_latitude');
            $table->string('to_longitude');
            $table->string('from_address');
            $table->string('to_address');
            $table->string('status');
            $table->string('amount');
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
        Schema::dropIfExists('request_pickup');
    }
}
