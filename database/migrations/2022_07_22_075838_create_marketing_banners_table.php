<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_banners', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->bigInteger('sort')->default(100);
            $table->string('type');
            $table->string('header');
            $table->string('description')->nullable();
            $table->string('button_text')->nullable();
            $table->string('url')->nullable();
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
        Schema::dropIfExists('marketing_banners');
    }
}
