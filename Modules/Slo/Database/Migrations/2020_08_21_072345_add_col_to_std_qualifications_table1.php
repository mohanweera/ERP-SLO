<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToStdQualificationsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('std_qualifications', function (Blueprint $table) {

            $table->integer('year')->nullable();
            $table->string('school')->nullable();
            $table->string('qualification')->nullable();
            $table->string('results')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('std_qualifications', function (Blueprint $table) {

        });
    }
}
