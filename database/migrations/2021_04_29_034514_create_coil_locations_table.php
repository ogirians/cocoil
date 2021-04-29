<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoilLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coil_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('coil_id')->nullable();
            $table->unsignedBigInteger('gudang_id')->nullable();
            $table->unsignedBigInteger('blok_id')->nullable();
            $table->char('coordinate');
            $table->timestamps();

            $table->foreign('coil_id')->references('id')->on('coil_details');
            $table->foreign('gudang_id')->references('id')->on('gudangs');
            $table->foreign('blok_id')->references('id')->on('bloks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coil_locations');
    }
}
