<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoilDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coil_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_category');
            $table->string('item_type');
            $table->string('item_code');
            $table->string('item_description');
            $table->string('serial_Code');
            $table->string('ID_coil');
            $table->integer('balance');
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
        Schema::dropIfExists('coil_details');
    }
}
