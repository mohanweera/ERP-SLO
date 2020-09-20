<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groupes', function (Blueprint $table) {
            $table->bigIncrements('GroupID');
            $table->string('GroupName')->nullable();
            $table->integer('BatchID');
            $table->foreign('CourseID')->references('course_id')->on('courses')->onDelete('cascade');
            $table->unsignedBigInteger('CourseID');
            $table->string('semester')->nullable();
            $table->year("year")->nullable();
            $table->string("type")->nullable();
            $table->integer("add_by")->nullable();
            $table->integer("deleted_by")->nullable();
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
        Schema::dropIfExists('groupes');
    }
}
