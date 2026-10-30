<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_results', function (Blueprint $table) {
            $table->increments('cr_id');
            $table->integer('cr_ci_id');
            $table->integer('cr_api_index_id');
            $table->integer('cr_batch_no');
            $table->double('cr_close_current');
            $table->string('cr_code');
            $table->timestamp('cr_result_dt');
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
        Schema::dropIfExists('country_results');
    }
}
