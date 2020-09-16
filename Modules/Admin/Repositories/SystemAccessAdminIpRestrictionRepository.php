<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;

class SystemAccessAdminIpRestrictionRepository extends BaseRepository
{
    public function generateIPHash($ip)
    {
        $sAIRRepo = new SystemAccessIpRestrictionRepository();
        return $sAIRRepo->generateIPHash($ip);
    }

    public function display_admin_as()
    {
        $adminUrl = URL::to("/admin/admin/view/");
        return view("admin::system_access_admin_ip_restriction.datatable.admin_ui", compact('adminUrl'));
    }
}
