<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_informations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->integer('goal');
            $table->float('initial_weight');
            $table->float('weight');
            $table->integer('gender');
            $table->float('height');
            $table->date('birthday');
            $table->integer('gym_type');
            $table->integer('sport_type1')->default(0);
            $table->integer('sport_type2')->default(0);
            $table->integer('sport_type3')->default(0);
            $table->integer('sport_time1')->default(0);
            $table->integer('sport_time2')->default(0);
            $table->integer('sport_time3')->default(0);
            $table->float('goal_weight');
            $table->float('weekly_goal');
            $table->integer('diet_mode');
            $table->integer('water')->default(0);
            $table->integer('fruit')->default(0);
            $table->date('date');
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
        Schema::dropIfExists('customer_information');
    }
}
