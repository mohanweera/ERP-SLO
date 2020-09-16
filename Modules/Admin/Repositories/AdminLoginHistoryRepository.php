<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;

class AdminLoginHistoryRepository extends BaseRepository
{
    public function display_admin_as()
    {
        $adminUrl = URL::to("/admin/admin/view/");
        return view("admin::admin_login_history.datatable.admin_ui", compact('adminUrl'));
    }

    public function display_country_as()
    {
        return view("admin::admin_login_history.datatable.country_ui");
    }

    public function display_sign_in_out_as()
    {
        return view("admin::admin_login_history.datatable.sign_in_out_ui");
    }
}
