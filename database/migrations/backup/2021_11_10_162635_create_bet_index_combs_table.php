<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetIndexCombsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_index_combs', function (Blueprint $table) {
            $table->increments('bic_id');
            $table->integer('bic_bet_id');
            $table->longtext('bic_index_codes');
            $table->double('bic_amount');
            $table->double('bic_subtotal');
            $table->integer('bic_status');
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
        Schema::dropIfExists('bet_index_combs');
    }
}
