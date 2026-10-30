<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_logs', function (Blueprint $table) {
            $table->increments('wl_id');
            $table->string('wl_ref_id')->nullable();
            $table->integer('wl_user_id');
            $table->integer('wl_wallet');
            $table->double('wl_amount');
            $table->integer('wl_link_id')->default(0);
            $table->integer('wl_type');
            $table->longtext('wl_remarks')->nullable();
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
        Schema::dropIfExists('wallet_logs');
    }
}
