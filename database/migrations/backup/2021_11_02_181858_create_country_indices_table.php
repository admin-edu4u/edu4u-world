<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_indices', function (Blueprint $table) {
            $table->increments('ci_id');
            $table->string('ci_name');
            $table->integer('ci_country_id');
            $table->integer('ci_api_index_id');
            $table->integer('ci_type')->default(0);
            $table->string('ci_status')->default(1);
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
        Schema::dropIfExists('country_indices');
    }
}
