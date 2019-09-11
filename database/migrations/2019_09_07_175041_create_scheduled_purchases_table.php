<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('cost');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('isIncome');
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
        Schema::dropIfExists('scheduled_purchases');
    }
}
