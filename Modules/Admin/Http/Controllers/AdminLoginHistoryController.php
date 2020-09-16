<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminLoginHistory;
use Modules\Admin\Repositories\AdminLoginHistoryRepository;

class AdminLoginHistoryController extends Controller
{
    private $repository = null;

    public function __construct()
    {
        $this->repository = new AdminLoginHistoryRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Admin Login History");

        $this->repository->initDatatable(new AdminLoginHistory());

        $this->repository->setColumns("id", "admin", "country", "online_status", "sign_in_at", "last_activity_at")
            ->setColumnLabel("country", "Location")
            ->setColumnLabel("sign_in_at", "Sign In/Out")
            ->setColumnLabel("online_status", "Online Status")
            ->setColumnDisplay("online_status", array($this->repository, 'display_status_as'), array([["id" =>"1", "name" =>"Online", "label"=>"success"], ["id" =>"0", "name" =>"Offline", "label"=>"danger"]]))
            ->setColumnDisplay("admin", array($this->repository, 'display_admin_as'))
            ->setColumnDisplay("country", array($this->repository, 'display_country_as'))
            ->setColumnDisplay("sign_in_at", array($this->repository, 'display_sign_in_out_as'))

            ->setColumnFilterMethod("admin", "select", URL::to("/admin/admin/search_data"))
            ->setColumnFilterMethod("country", "select", URL::to("/country/search_data"))
            ->setColumnFilterMethod("sign_in_at", "date_between")
            ->setColumnFilterMethod("online_status", "select", [["id" =>"1", "name" =>"Online"], ["id" =>"0", "name" =>"Offline"]])

            ->setColumnSearchability("online_status", false)
            ->setColumnSearchability("last_activity_at", false)
            ->setColumnSearchability("sign_in_at", false)
            ->setColumnSearchability("sign_out_at", false)
            ->setColumnSearchability("sign_out_type", false)

            ->setColumnDBField("admin", "admin_id")
            ->setColumnFKeyField("admin", "admin_id")
            ->setColumnRelation("admin", "admin", "name")

            ->setColumnDBField("country", "country_id")
            ->setColumnFKeyField("country", "country_id")
            ->setColumnRelation("country", "country", "country_name");

        $query = $this->repository->model->with(["admin", "country"]);

        $this->repository->setTableTitle("Admin Login History")
            ->enableViewData("list", "view", "export")
            ->disableViewData("add", "edit", "trashList", "trash", "delete")
            ->setUrlLabel("view", "View Activities")
            ->setUrl("view", "/admin/admin_activity/");

        return $this->repository->render("admin::layouts.master")->index($query);
    }
}
