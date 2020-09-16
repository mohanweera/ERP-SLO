<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('student_id');
            $table->integer('gen_student_id')->unique();
            $table->string('std_title', 10);
            $table->string('name_initials', 100);
            $table->string('full_name', 255);
            $table->date('date_of_birth');
            $table->string('gender', 10);
            $table->text('per_address')->nullable();
            $table->string('per_city', 100)->nullable();
            $table->string('per_country', 100)->nullable();
            $table->string('per_postal_code', 25)->nullable();
            $table->string('tel_residence', 20)->nullable();
            $table->string('tel_work', 20)->nullable();
            $table->string('tel_mobile1', 20)->nullable();
            $table->string('tel_mobile2', 20)->nullable();
            $table->string('email1', 255)->nullable();
            $table->string('email2', 255)->nullable();
            $table->string('kiu_mail', 150)->nullable();
            $table->string('nationality', 11)->nullable();
            $table->string('nic_passport', 100)->nullable();
            $table->text('special')->nullable();

            $table->string('payment_plan', 255)->nullable();
            $table->double('discount')->nullable();
            $table->string('grace_period')->nullable();

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
        Schema::dropIfExists('students');
    }
}
