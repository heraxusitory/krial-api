<?php

use App\Models\Catalog\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('categoriable_type')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_root')->default(false);
            $table->unsignedBigInteger('sort')->default(100);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_h1')->nullable();
            $table->json('compilations')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
