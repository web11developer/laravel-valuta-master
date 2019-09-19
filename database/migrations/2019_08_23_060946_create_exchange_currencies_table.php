<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('exchange_currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('exchange_id');
            $table->integer('currency_id')->unsigned();
            $table->timestamps();

        });



        Schema::table('exchange_currencies', function(Blueprint $table) {
            $table->foreign('exchange_id')
                ->references('id')
                ->on('exchangers')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('currency_id')
                ->on('cash_currencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_currencies');
    }
}
