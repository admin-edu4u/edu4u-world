<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiCountryIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_country_indices', function (Blueprint $table) {
            $table->increments('aci_id');
            $table->integer('aci_index_id');
            $table->string('aci_name');
            $table->string('aci_full_name');
            $table->integer('aci_country');
            $table->integer('aci_country_id');
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
        Schema::dropIfExists('api_country_indices');
    }
}
