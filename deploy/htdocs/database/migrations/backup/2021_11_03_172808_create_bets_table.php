<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->increments('bet_id');
            $table->string('bet_ref_id');
            $table->integer('bet_user_id');
            $table->longtext('bet_country_indices');
            $table->double('bet_ttl_amount');
            $table->timestamp('bet_date');
            $table->integer('bet_status')->default(0);
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
        Schema::dropIfExists('bets');
    }
}
