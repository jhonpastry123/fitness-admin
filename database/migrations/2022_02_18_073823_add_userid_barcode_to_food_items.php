<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUseridBarcodeToFoodItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_items', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('barcode', 150);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_items', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('barcode');
        });
    }
}
