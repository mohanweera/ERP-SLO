<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlqfVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slqf_versions', function (Blueprint $table) {
            $table->integerIncrements('slqf_version_id');
            $table->unsignedInteger('slqf_id');
            $table->string('version_name', 255);
            $table->tinyInteger('version');
            $table->string('slqf_file_name', 255);
            $table->date('version_date');
            $table->unsignedTinyInteger('version_status')->default(0);
            $table->unsignedTinyInteger('default_status')->default(0);

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('slqf_versions', function (Blueprint $table) {

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
        Schema::dropIfExists('slqf_versions');
    }
}
