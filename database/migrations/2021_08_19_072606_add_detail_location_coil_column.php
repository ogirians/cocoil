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
            $table->char('slot_id');
            $table->char('slot_name');
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
            
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('nameRect');
            $table->dropColumn('x');
            $table->dropColumn('y');
            $table->dropColumn('scaleX');
            $table->dropColumn('scaleY');
            $table->dropColumn('rotation');
            $table->dropColumn('slot_id');
            $table->dropColumn('slot_name');
        });
    }
}
