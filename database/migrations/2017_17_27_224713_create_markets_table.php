<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exchenge_id');
            $table->integer('currency_id_from');
            $table->integer('currency_id_to');
            $table->string('high');
            $table->string('low');
            $table->string('bid');
            $table->string('ask');
            $table->string('volume');
            $table->string('last');
            $table->string('previous');
            $table->double('change');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('exchenge_id')->references('id')->on('exchenges');
            $table->foreign('currency_id_from')->references('id')->on('currencies');
            $table->foreign('currency_id_to')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('markets');
    }
}
