<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;

class AdminPermissionGroupRepository extends BaseRepository
{
    public function display_permissions_as()
    {
        $permissionUrl = URL::to("/admin/admin_system_permission/");
        return view("admin::admin_perm_group.datatable.perms_ui", compact('permissionUrl'));
    }
}
