<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStdExtraDetailsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('std_extra_details', function (Blueprint $table) {
            $table->string('hostel')->nullable();
            $table->string('locker_key')->nullable();
            $table->string('slipper_size')->nullable();
            $table->string('preferred_hand')->nullable();



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
