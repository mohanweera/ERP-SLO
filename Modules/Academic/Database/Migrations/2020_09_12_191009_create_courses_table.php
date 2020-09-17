<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->integerIncrements('course_id');
            $table->unsignedInteger('dept_id');
            $table->unsignedInteger('slqf_id');
            $table->unsignedSmallInteger('course_category_id');
            $table->string('course_code', 255);
            $table->string('course_name', 255);
            $table->string('abbreviation', 255);
            $table->unsignedTinyInteger('course_du_years');
            $table->unsignedTinyInteger('course_du_months');
            $table->unsignedTinyInteger('course_du_dates');
            $table->unsignedTinyInteger('supplementary_status');
            $table->unsignedTinyInteger('course_du_years_ex');
            $table->unsignedTinyInteger('course_du_months_ex');
            $table->unsignedTinyInteger('course_du_dates_ex');
            $table->unsignedTinyInteger('course_status');

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {

            $table->foreign("created_by")->references("admin_id")->on("admins");
            $table->foreign("updated_by")->references("admin_id")->on("admins");
            $table->foreign("deleted_by")->references("admin_id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
