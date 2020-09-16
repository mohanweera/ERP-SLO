<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;

class AdminRolePermissionHistoryRepository extends BaseRepository
{
    public function display_permission_system_as()
    {
        $systemUrl = URL::to("admin/admin_permission_system/");
        return view("admin::admin_role.permission_history.datatable.permission_system_ui", compact('systemUrl'));
    }
}
