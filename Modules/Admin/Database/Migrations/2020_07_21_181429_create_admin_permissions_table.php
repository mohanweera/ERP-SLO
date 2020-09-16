<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Entities\AdminPermissionChangeRemark;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->unsignedInteger("system_perm_id");
            $table->unsignedTinyInteger("inv_rev_status")->comment("Invoked or revoked status; Invoked:1, Revoked:0");
            $table->date("valid_from")->nullable();
            $table->date("valid_till")->nullable();
        });

        Schema::table('admin_permissions', function (Blueprint $table) {

            $table->foreign("admin_id")->references("admin_id")->on("admins");
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on("admin_permission_systems");
            $table->foreign("system_perm_id")->references("system_perm_id")->on("admin_system_permissions");

            $table->index("admin_id");
            $table->index("admin_perm_system_id");
            $table->index("system_perm_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permissions');
    }
}
