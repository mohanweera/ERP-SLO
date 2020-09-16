<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->json('edu_req')->nullable();
            //$table->string('edu_res',255)->nullable();

            $table->json('pro_req')->nullable();
            // $table->string('pro_res',255)->nullable();

            $table->json('work_req')->nullable();
            // $table->string('work_res',255)->nullable();

            $table->json('ref_req')->nullable();
            //$table->string('ref_res',255)->nullable();

            $table->dateTime("updated_on")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();
            $table->softDeletes();
            $table->foreignId('course_id')->unique()->constrained('courses','course_id');
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
        Schema::dropIfExists('course_requirements');
    }
}
