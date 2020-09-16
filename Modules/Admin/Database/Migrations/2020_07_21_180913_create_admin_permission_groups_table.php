<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionModule;

class CreateAdminPermissionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_groups', function (Blueprint $table) {
            $table->integerIncrements('admin_perm_group_id');
            $table->unsignedInteger("admin_perm_module_id");
            $table->string("group_name", 255);
            $table->string("group_slug", 255);
            $table->unsignedTinyInteger("group_status");
            $table->text("remarks")->nullable();

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('admin_permission_groups', function (Blueprint $table) {

            $table->unique(array('admin_perm_module_id', 'group_slug'));
            $table->foreign("admin_perm_module_id")->references("admin_perm_module_id")->on("admin_permission_modules");
            $table->foreign("created_by")->references("admin_id")->on("admins");
            $table->foreign("updated_by")->references("admin_id")->on("admins");
            $table->foreign("deleted_by")->references("admin_id")->on("admins");

            $table->index("admin_perm_module_id");
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
        Schema::dropIfExists('admin_permission_groups');
    }
}
