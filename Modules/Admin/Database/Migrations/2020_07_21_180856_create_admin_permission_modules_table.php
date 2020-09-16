<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionSystem;

class CreateAdminPermissionModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_modules', function (Blueprint $table) {
            $table->integerIncrements('admin_perm_module_id');
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->string("module_name", 255);
            $table->string("module_slug", 255);
            $table->unsignedTinyInteger("module_status");
            $table->text("remarks")->nullable();

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('admin_permission_modules', function (Blueprint $table) {

            $table->unique(array('admin_perm_system_id', 'module_slug'));
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on("admin_permission_systems");
            $table->foreign("created_by")->references("admin_id")->on("admins");
            $table->foreign("updated_by")->references("admin_id")->on("admins");
            $table->foreign("deleted_by")->references("admin_id")->on("admins");

            $table->index("admin_perm_system_id");
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
        Schema::dropIfExists('admin_permission_modules');
    }
}
