<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminSystemPermission;

class CreateAdminRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_role_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->longText("permissions");
        });

        Schema::table('admin_role_permissions', function (Blueprint $table) {

            $table->foreign("admin_role_id")->references("admin_role_id")->on("admin_roles");
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on("admin_permission_systems");

            $table->index("admin_role_id");
            $table->index("admin_perm_system_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_permissions');
    }
}
