<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_codes', function (Blueprint $table) {
            $table->increments('ic_id');
            $table->integer('ic_ci_id');
            $table->integer('ic_code');
            $table->double('ic_win_multiplier');
            $table->integer('ic_daily_limit');
            $table->double('ic_min_purc');
            $table->double('ic_max_purc');
            $table->integer('ic_status')->default(1);
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
        Schema::dropIfExists('index_codes');
    }
}
