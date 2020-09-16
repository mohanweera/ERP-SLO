<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Entities\AdminPermissionHistory;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Repositories\AdminPermissionHistoryRepository;

class AdminPermissionHistoryController extends Controller
{
    private $repository = null;

    public function __construct()
    {
        $this->repository = new AdminPermissionHistoryRepository();
    }

    /**
     * Display a listing of the resource.
     * @param int $adminId
     * @return Factory|View
     */
    public function index($adminId)
    {
        $admin = Admin::find($adminId);

        if($admin)
        {
            $this->repository->setPageTitle($admin["name"]." | Permission Change History");

            $this->repository->initDatatable(new AdminPermissionHistory());
            $this->repository->viewData->tableTitle = $admin["name"]." | Permission Change History";

            $this->repository->viewData->enableExport = true;

            $this->repository->setColumns("id", "permission_system", "remarks", "created_at")
                ->setColumnDisplay("permission_system", array($this->repository, 'display_permission_system_as'))
                ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

                ->setColumnSearchability("created_at", false)

                ->setColumnDBField("permission_system", "admin_perm_system_id")
                ->setColumnFKeyField("permission_system", "admin_perm_system_id")
                ->setColumnRelation("permission_system", "permissionSystem", "system_name");

            $query = $this->repository->model::query();

            $this->repository->enableViewData("view", "export")
                 ->disableViewData("add","edit", "delete")
                 ->setUrlLabel("view", "View Changed Permissions");

            $query = $query->with(["permissionSystem"])->where(["admin_id" => $adminId]);
            return $this->repository->render("admin::layouts.master")->index($query);
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $model = AdminPermissionHistory::with(["permissionSystem"])->find($id);

        if($model)
        {
            $model = $model->toArray();
            $admin = Admin::find($model["admin_id"]);

            $this->repository->setPageTitle($admin["name"]." | Changed Permissions");

            $record = $model;

            $urls = [];
            $urls["listUrl"]=URL::to("admin/admin_permission_history/".$record["admin_id"]);

            $this->repository->setPageUrls($urls);

            $invokedPermissions = AdminSystemPermission::query()->with(["permissionGroup"])->whereIn("system_perm_id", $record["invoked_permissions"])->get()->toArray();
            $revokedPermissions = AdminSystemPermission::query()->with(["permissionGroup"])->whereIn("system_perm_id", $record["revoked_permissions"])->get()->toArray();

            return view('admin::admin.permission_history.view', compact( 'record', 'admin', 'invokedPermissions', 'revokedPermissions'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }
}
