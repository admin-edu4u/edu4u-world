<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('confs', function (Blueprint $table) {
        $table->increments('conf_id');
				$table->string('conf_call')->unique();
				$table->string('conf_name');
				$table->string("conf_enum");
				$table->string('conf_batch')->nullable();//use to link a group of configuration into same category
				$table->double("conf_value");
			
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
        Schema::dropIfExists('confs');
    }
}
