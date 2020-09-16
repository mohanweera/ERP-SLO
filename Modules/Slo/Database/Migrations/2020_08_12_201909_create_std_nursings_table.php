<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdNursingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_nursings', function (Blueprint $table) {
            $table->bigIncrements('std_nursing_id');
            $table->string('ward', 100);
            $table->string('nts', 100);
            $table->softDeletes();
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('hospital_id')->constrained('gen_hospitals', 'gen_hospital_id');


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
        Schema::dropIfExists('std_nursings');
    }
}
