<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_indices', function (Blueprint $table) {
            $table->increments('ai_id');
            $table->string('ai_index_id')->nullable();
            $table->string('ai_index_name')->nullable();
            $table->string('ai_close_current')->nullable();
            $table->string('ai_high')->nullable();
            $table->string('ai_low')->nullable();
            $table->string('ai_change')->nullable();
            $table->string('ai_chg_percent')->nullable();
            $table->string('ai_country_name')->nullable();
            $table->string('ai_country_id')->nullable();
            $table->timestamp('ai_last_chg_dt')->nullable();
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
        Schema::dropIfExists('api_indices');
    }
}
