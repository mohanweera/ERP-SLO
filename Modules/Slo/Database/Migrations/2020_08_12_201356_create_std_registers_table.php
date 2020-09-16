<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_registers', function (Blueprint $table) {
            $table->bigIncrements('std_register_id');
            $table->foreignId('batch_id')->constrained('batches', 'batch_id');
            $table->foreignId('student_id')->constrained('students','student_id');
            $table->integer('reg_no')->nullable();
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
        Schema::dropIfExists('std_registers');
    }
}
