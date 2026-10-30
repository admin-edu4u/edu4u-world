<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_indices', function (Blueprint $table) {
            $table->increments('bi_id');
            $table->integer('bi_bet_id');
            $table->integer('bi_country_index');
            $table->integer('bi_code');
            $table->double('bi_amount');
            $table->timestamp('bi_date');
            $table->integer('bi_status');
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
        Schema::dropIfExists('bet_indices');
    }
}
