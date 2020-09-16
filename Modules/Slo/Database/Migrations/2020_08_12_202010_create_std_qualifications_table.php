<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_qualifications', function (Blueprint $table) {
            $table->bigIncrements('std_qualification_id');
            $table->string('type', 10);
            $table->string('school', 255);
            $table->integer('year');
            $table->string('qualification', 255);
            $table->string('results', 255);

            $table->foreignId('student_id')->constrained('students', 'student_id');

            $table->dateTime("updated_on")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();
            $table->softDeletes();

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
        Schema::dropIfExists('std_qualifications');
    }
}
