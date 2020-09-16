<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;

class AdminPermissionModuleRepository extends BaseRepository
{
    public function display_groups_as()
    {
        $groupUrl = URL::to("/admin/admin_permission_group/");
        return view("admin::admin_perm_module.datatable.groups_ui", compact('groupUrl'));
    }
}
