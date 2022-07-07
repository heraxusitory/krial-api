<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDgProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dg_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('second_name')->nullable();
            $table->string('series_id')->nullable();
            $table->bigInteger('sort')->default(100);
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('manufacture_id');
            $table->unsignedBigInteger('engine_manufacture_id')->nullable();
//            $table->json('media')->nullable();
            $table->text('description')->nullable();
            $table->string('internal_vendor_code')->nullable();
            $table->string('allgen_vendor_code')->nullable();
            $table->string('allgen_url')->nullable();
            $table->string('provider_vendor_code')->nullable();
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
        Schema::dropIfExists('dg_products');
    }
}
