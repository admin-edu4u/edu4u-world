<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('d_id');
            $table->string('d_ref_no')->nullable();
            $table->integer('d_user_id');
            $table->double('d_amount');
            $table->double('d_exc_rate');
            $table->double('d_credit');
            $table->integer('d_pay_method')->default(1);
            $table->string('d_remark')->nullable();
            $table->string('d_receipt_1')->nullable();
            $table->string('d_receipt_2')->nullable();
            $table->string('d_receipt_3')->nullable();
            $table->integer('d_status')->default(1);
            $table->timestamp('d_approved_at');
            $table->timestamp('d_rejected_at');
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
        Schema::dropIfExists('deposits');
    }
}
