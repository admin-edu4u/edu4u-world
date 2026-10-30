<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAddrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addrs', function (Blueprint $table) {
            $table->increments('ua_id');
            $table->integer('ua_user');
            $table->integer('ua_type');
            $table->string('ua_location');
            $table->string('ua_lat');
            $table->string('ua_lon');
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
        Schema::dropIfExists('user_addrs');
    }
}
