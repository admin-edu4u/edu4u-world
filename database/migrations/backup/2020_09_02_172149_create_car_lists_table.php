<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_lists', function (Blueprint $table) {
            $table->increments('cl_id');
            $table->integer('cl_user');
            $table->integer('cl_brand');
            $table->integer('cl_model');
            $table->string('cl_colour')->nullable();
            $table->string('cl_plate_no');
            $table->string('cl_pic')->nullable();
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
        Schema::dropIfExists('car_lists');
    }
}
