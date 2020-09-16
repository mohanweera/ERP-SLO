<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_uploads', function (Blueprint $table) {
            $table->bigIncrements('std_upload_id');
            $table->string('file')->nullable();
            $table->string('file_size')->nullable();
            $table->foreignId('student')->constrained('students','student_id');
            $table->foreignId('category')->constrained('upload_categories','upload_cat_id');

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
        Schema::dropIfExists('student_uploads');
    }
}
