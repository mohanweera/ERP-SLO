<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStdExtraDetailsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('std_extra_details', function (Blueprint $table) {
            $table->dropColumn('host');
            $table->dropColumn('locker_key');
            $table->dropColumn('slipper_size');
            $table->dropColumn('preferred_hand');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
