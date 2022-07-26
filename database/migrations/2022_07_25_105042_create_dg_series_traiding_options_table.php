<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgSeriesTraidingOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dg_series_traiding_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dg_series_id');
            $table->unsignedBigInteger('dg_version_id');
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
        Schema::dropIfExists('dg_series_traiding_options');
    }
}
