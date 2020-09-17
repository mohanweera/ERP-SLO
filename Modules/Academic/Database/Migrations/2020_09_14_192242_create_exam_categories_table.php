<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_categories', function (Blueprint $table) {
            $table->integerIncrements('exam_category_id');
            $table->string('exam_category', 255);
            $table->unsignedTinyInteger('category_status')->default(0);

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('exam_categories', function (Blueprint $table) {

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
        Schema::dropIfExists('exam_categories');
    }
}
