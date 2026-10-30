<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
						$table->string('contact_code')->nullable();
            $table->string('contact_no');
						
						$table->string('mlm_id')->nullable();
            $table->string('email')->nullable();
						$table->string('username')->nullable();
						$table->string('nickname')->nullable();
						$table->string('fullname')->nullable();
            $table->text('password')->nullable();
						
						$table->longtext('desc')->nullable();
						$table->string('live_in')->nullable();
						$table->integer('nationality')->default(0);
						$table->integer('personality')->default(0);
						
						$table->string('addr1')->nullable();
						$table->string('city')->nullable();
						$table->string('postcode')->nullable();
						$table->string('state')->nullable();
						$table->integer('country')->default(0);
						
            $table->integer('isActive')->default(0);
            $table->integer('isAdmin')->default(0);
						$table->string('referral')->nullable();
						$table->integer('member_type')->default(0);
						
						$table->integer('dob_day')->default(0);
						$table->integer('dob_month')->default(0);
						$table->integer('dob_year')->default(0);
						
						$table->string('weight')->nullable();
						$table->string('height')->nullable();
						
						$table->string('ic')->nullable();;
						$table->integer('gender')->default(0);
						$table->string('body1')->nullable();
						$table->string('body2')->nullable();
						$table->string('body3')->nullable();
											
						$table->string('profile_pic')->nullable();
						$table->string('side_img1')->nullable();
						$table->string('side_img2')->nullable();
						$table->string('side_img3')->nullable();
						$table->string('side_img4')->nullable();
						$table->string('side_img5')->nullable();
						$table->string('side_img6')->nullable();
						$table->string('side_img7')->nullable();
						$table->string('side_img8')->nullable();
						$table->string('cover_img')->nullable();
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
        Schema::dropIfExists('users');
    }
}
