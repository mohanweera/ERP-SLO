<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permission_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("admin_id");
            $table->unsignedSmallInteger("admin_perm_system_id");
            $table->text("remarks");
            $table->longText("invoked_permissions");
            $table->longText("revoked_permissions");

            $table->timestamps();
        });

        Schema::table('admin_permission_histories', function (Blueprint $table) {

            $table->foreign("admin_id")->references("admin_id")->on("admins");
            $table->foreign("admin_perm_system_id")->references("admin_perm_system_id")->on("admin_permission_systems");

            $table->index("admin_id");
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
        Schema::dropIfExists('admin_permission_histories');
    }
}
