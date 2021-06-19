<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->date('date');
            $table->integer('exercise_rate');
            $table->integer('sport1_type')->default(0);
            $table->integer('sport1_time')->default(0);
            $table->integer('sport2_type')->default(0);
            $table->integer('sport2_time')->default(0);
            $table->integer('sport3_type')->default(0);
            $table->integer('sport3_time')->default(0);
            $table->float('height');
            $table->float('weight');
            $table->float('neck');
            $table->float('waist');
            $table->float('thigh');
            $table->float('weekly_goal');
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
        Schema::dropIfExists('settings');
    }
}
