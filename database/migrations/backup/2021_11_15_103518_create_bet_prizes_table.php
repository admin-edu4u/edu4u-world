<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBetPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bet_prizes', function (Blueprint $table) {
            $table->increments('bp_id');
            $table->integer('bp_user_id');
            $table->integer('bp_ai_id'); // Api Index Id
            $table->integer('bp_bet_id'); // Bet Id
            $table->integer('bp_bi_id'); // Bet Index Id
            $table->integer('bp_bic_id'); // Bet Index Comb Id
            $table->integer('bp_win_ci_id'); // Country Index Id
            $table->integer('bp_win_index_code'); // Index Code
            $table->double('bp_win_multiplier'); // Multiplier
            $table->double('bp_win_amount'); // Amount
            $table->integer('bp_status')->default(1);
            $table->timestamp('bp_ci_last_chg');
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
        Schema::dropIfExists('bet_prizes');
    }
}
