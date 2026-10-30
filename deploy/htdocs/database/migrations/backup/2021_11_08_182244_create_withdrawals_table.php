<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->increments('w_id');
            $table->string('w_ref_no')->nullable();
            $table->integer('w_user_id');
            $table->double('w_amount');
            $table->longtext('w_bet_ids');
            $table->string('w_remark')->nullable();
            $table->integer('w_status')->default(1);
            $table->timestamp('w_approved_at');
            $table->timestamp('w_rejected_at');
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
        Schema::dropIfExists('withdrawals');
    }
}
