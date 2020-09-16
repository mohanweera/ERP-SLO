<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Entities\AdminRolePermissionHistory;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Repositories\AdminPermissionSystemRepository;
use Modules\Admin\Repositories\AdminRolePermissionHistoryRepository;
use Modules\Admin\Repositories\AdminRoleRepository;

class AdminRolePermissionHistoryController extends Controller
{
    private $repository = null;

    public function __construct()
    {
        $this->repository = new AdminRolePermissionHistoryRepository();
    }

    /**
     * Display a listing of the resource.
     * @param int $adminRoleId
     * @return Factory|View
     */
    public function index($adminRoleId)
    {
        $adminRole = AdminRole::find($adminRoleId);

        if($adminRole)
        {
            $this->repository->setPageTitle($adminRole["role_name"]." | Permission Change History");

            $this->repository->initDatatable(new AdminRolePermissionHistory());
            $this->repository->viewData->tableTitle = $adminRole["role_name"]." | Permission Change History";

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

            $query = $query->with(["permissionSystem"])->where(["admin_role_id" => $adminRoleId]);
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
        $model = AdminRolePermissionHistory::with(["permissionSystem"])->find($id);

        if($model)
        {
            $model = $model->toArray();
            $adminRole = AdminRole::find($model["admin_role_id"]);

            $this->repository->setPageTitle($adminRole["role_name"]." | Changed Permissions");

            $record = $model;

            $urls = [];
            $urls["listUrl"]=URL::to("admin/admin_role_permission_history/".$record["admin_role_id"]);

            $this->repository->setPageUrls($urls);

            $invokedPermissions = AdminSystemPermission::query()->with(["permissionGroup"])->whereIn("system_perm_id", $record["invoked_permissions"])->get()->toArray();
            $revokedPermissions = AdminSystemPermission::query()->with(["permissionGroup"])->whereIn("system_perm_id", $record["revoked_permissions"])->get()->toArray();

            return view('admin::admin_role.permission_history.view', compact( 'record', 'adminRole', 'invokedPermissions', 'revokedPermissions'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }
}
