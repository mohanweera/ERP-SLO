<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateAdminPermissionSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_systems', function (Blueprint $table) {
            $table->smallIncrements('admin_perm_system_id');
            $table->string("system_name", 255);
            $table->string("system_slug", 255)->unique();
            $table->unsignedTinyInteger("system_status");
            $table->text("remarks")->nullable();

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('admin_permission_systems', function (Blueprint $table) {

            $table->foreign("created_by")->references("admin_id")->on("admins");
            $table->foreign("updated_by")->references("admin_id")->on("admins");
            $table->foreign("deleted_by")->references("admin_id")->on("admins");

            $table->index("created_by");
            $table->index("updated_by");
            $table->index("deleted_by");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permission_systems');
    }
}
