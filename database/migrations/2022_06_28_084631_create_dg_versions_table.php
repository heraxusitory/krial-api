<?php

use App\Models\References\DGVersion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDgVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dg_versions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->json('default_media')->nullable();
        });

        $versions = [
            DGVersion::VERSION_OPEN() => 'Открытый',
            DGVersion::VERSION_IN_CASE() => 'В кожухе',
            DGVersion::VERSION_IN_CONTAINER() => 'В контейнере',
            DGVersion::VERSION_IN_CASE_ON_CHASSIS() => 'В кожухе на шасси',
            DGVersion::VERSION_IN_CONTAINER_ON_CHASSIS() => 'В контейнере на шасси',
        ];

        foreach ($versions as $code => $name) {
            DB::table('dg_versions')->insert(['code' => $code, 'name' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dg_versions');
    }
}
