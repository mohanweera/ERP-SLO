<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\Admin;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->integerIncrements('admin_id');
            $table->unsignedInteger("admin_role_id")->nullable();
            $table->unsignedInteger("employee_id")->nullable();
            $table->unsignedInteger("lecturer_id")->nullable();
            $table->string("name", 255);
            $table->string("email")->unique();
            $table->string("password", 60);
            $table->string("image")->nullable();
            $table->unsignedTinyInteger("default_admin")->default('0');
            $table->unsignedTinyInteger("super_user")->default('0');
            $table->longText("allowed_roles")->nullable(true)->comment("Allowed user roles to create by this user. JSON array of values.");
            $table->longText("disallowed_roles")->nullable(true)->comment("Disallowed user roles to create by this user. JSON array of values.");
            $table->unsignedTinyInteger("status")->default("1");
            $table->text("disabled_reason")->nullable(true)->comment("If user has been disabled, then reason for that. This value will be displayed to the user when he is trying to sign in");
            $table->rememberToken();

            $table->unsignedInteger("created_by")->nullable();
            $table->unsignedInteger("updated_by")->nullable();
            $table->unsignedInteger("deleted_by")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('admins', function (Blueprint $table) {

            /*$table->foreign("employee_id")->references("employee_id")->on(Employee::class);
            $table->index("employee_id");

            $table->foreign("lecturer_id")->references("lecturer_id")->on(Lecturer::class);
            $table->index("lecturer_id");*/

            /*$table->foreign("admin_role_id")->references("admin_role_id")->on("admin_roles");;
            $table->index("admin_role_id");*/

            $table->foreign("created_by")->references("admin_id")->on("admins");
            $table->foreign("updated_by")->references("admin_id")->on("admins");
            $table->foreign("deleted_by")->references("admin_id")->on("admins");

            $table->index("created_by");
            $table->index("updated_by");
            $table->index("deleted_by");
        });

        //add default system administrator to the system
        Admin::create([
            'name' => "Default System Admin",
            'email' => "test@test.com",
            'password' => "test@123",
            'admin_role_id' => 0,
            'default_admin' => "1",
            'status' => "1",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
