<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('b_id');
            $table->integer('b_user');
            $table->integer('b_driver');
            $table->string('b_depart_place');
            $table->string('b_depart_lat');
            $table->string('b_depart_lon');
            $table->string('b_dest_place');
            $table->string('b_dest_lat');
            $table->string('b_dest_lon');
            $table->string('b_dest_desc')->nullable();
            $table->double('b_base_fees');
            $table->double('b_distance');
            $table->double('b_distance_cost');
            $table->integer('b_insurance')->nullable()->default(0);
            $table->integer('b_camera')->nullable()->default(0);
            $table->integer('b_cl_id');
            $table->double('b_total_amt');
            $table->integer('b_status')->nullable()->default(0);
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
