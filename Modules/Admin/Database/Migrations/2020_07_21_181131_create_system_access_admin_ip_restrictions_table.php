<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\Admin;

class CreateSystemAccessAdminIpRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_access_admin_ip_restrictions', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger("admin_id");
            $table->string("ip_location", 255);
            $table->string("ip_address", 50);
            $table->string("ip_address_key", 32);
            $table->text("remarks");
            $table->unsignedTinyInteger("access_status");

            $table->unsignedInteger("created_by");
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('system_access_admin_ip_restrictions', function (Blueprint $table) {

            $table->foreign("admin_id")->references("admin_id")->on("admins");
            $table->index("admin_id");

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
        Schema::dropIfExists('system_access_admin_ip_restrictions');
    }
}
