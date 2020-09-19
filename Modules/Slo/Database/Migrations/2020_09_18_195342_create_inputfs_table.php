<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputfs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fname')->nullable();
            $table->integer('fid');
            $table->integer('course_id');
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
        Schema::dropIfExists('inputfs');
    }
}
