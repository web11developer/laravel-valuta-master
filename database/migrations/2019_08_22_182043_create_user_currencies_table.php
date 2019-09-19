<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//      Schema::table('cash_currencies', function(Blueprint $table) {
//            $table->integerIncrements('currency_id')->change();
//        });
//
//        Schema::create('user_currencies', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->unsignedInteger('user_id');
//            $table->unsignedInteger('currency_id');
//            $table->timestamps();
//
//        });
//
//        Schema::table('user_currencies', function(Blueprint $table) {
//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->onDelete('cascade');
//
//            $table->foreign('currency_id')
//                ->references('currency_id')
//                ->on('cash_currencies')
//                ->onDelete('cascade');
//        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_currencies');
    }
}
