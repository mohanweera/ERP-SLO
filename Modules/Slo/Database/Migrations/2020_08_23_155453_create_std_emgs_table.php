<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStdEmgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('std_emgs', function (Blueprint $table) {
            $table->bigIncrements('std_emg_id');
            $table->string('emg_name')->nullable();
            $table->string('address')->nullable();
            $table->string('emg_tel_residence')->nullable();
            $table->string('emg_tel_work')->nullable();
            $table->string('emg_tel_mobile1')->nullable();
            $table->string('emg_tel_mobile2')->nullable();
            $table->string('relationship')->nullable();



            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();
            $table->foreignId('student_id')->constrained('students','student_id');
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
        Schema::dropIfExists('std_emgs');
    }
}
