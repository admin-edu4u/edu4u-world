<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFullCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('full_countries', function (Blueprint $table) {
            $table->increments('country_id');
            $table->string('country_code', 191)->unique();
            $table->string('country_name');
            $table->string('country_nicename');
            $table->string('country_rep')->nullable();
            $table->string('country_no')->nullable();
            $table->string('phonecode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('full_countries');
    }
}
