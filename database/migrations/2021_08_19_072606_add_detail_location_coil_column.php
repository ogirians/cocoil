<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailLocationCoilColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coil_locations', function($table) {
            $table->char('height');
            $table->char('width');
            $table->char('nameRect');
            $table->char('x');
            $table->char('y');
            $table->char('scaleX');
            $table->char('scaleY');
            $table->char('rotation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coil_locations', function($table) {
          
            $table->dropColumn('width');
            $table->dropColumn('nameRect');
            $table->dropColumn('x');
            $table->dropColumn('y');
            $table->dropColumn('scaleX');
            $table->dropColumn('scaleY');
            $table->dropColumn('rotation');
        });
    }
}
