<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgTradingOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dg_trading_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dg_product_id');
            $table->unsignedBigInteger('dg_version_id');
            $table->json('media')->nullable();
            $table->unsignedDouble('price')->nullable();
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
        Schema::dropIfExists('dg_trading_options');
    }
}