<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinksToExchangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchangers', function (Blueprint $table) {
            $table->string('telegram_link')->nullable();
            $table->string('whatsapp_link')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchangers', function (Blueprint $table) {
            $table->dropColumn('telegram_link');
            $table->dropColumn('whatsapp_link');

        });
    }
}
