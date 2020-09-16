<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_experiences', function (Blueprint $table) {
            $table->bigIncrements('std_experience_id');
            $table->string('organization',255);
            $table->string('position',255);
            $table->string('duration',150);
            $table->string('designation',150);
            $table->string('reason_for_exit',255);

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
        Schema::dropIfExists('std_experiences');
    }
}
