<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminPermissionSystem;

class CreateAdminRolePermissionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role_permission_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_role_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->text("remarks")->nullable();
            $table->longText("invoked_permissions");
            $table->longText("revoked_permissions");

            $table->timestamps();
        });

        Schema::table('admin_role_permission_histories', function (Blueprint $table) {

            $table->foreign("admin_role_id")->references("admin_role_id")->on("admin_roles");
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on("admin_permission_systems");

            $table->index("admin_role_id");
            $table->index("admin_perm_system_id");

            $table->unsignedInteger("created_by");
            $table->index("created_by");
            $table->foreign("created_by")->references("admin_id")->on("admins");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role_permission_histories');
    }
}
