<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdExtraDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_extra_details', function (Blueprint $table) {
            $table->bigIncrements('std_extra_detail_id');
            $table->string('preferred_hand', 50);
            $table->integer('slipper_size');
            $table->integer('locker_key');
            $table->integer('host');

            $table->foreignId('student_id')->constrained('students','student_id');
            $table->softDeletes();
            $table->dateTime("updated_on")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();
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
        Schema::dropIfExists('std_extra_details');
    }
}
