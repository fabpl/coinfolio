<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('market_id');
            $table->integer('price_id');
            $table->integer('user_id');
            $table->string('quantity');
            $table->string('last');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('market_id')->references('id')->on('markets');
            $table->foreign('price_id')->references('id')->on('prices');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
