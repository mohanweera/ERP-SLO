<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionGroup;
use Modules\Admin\Entities\AdminPermissionModule;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Repositories\AdminSystemPermissionRepository;

class AdminSystemPermissionController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminSystemPermissionRepository();
    }

    /**
     * Display a listing of the resource.
     * @param int $admin_perm_group_id
     * @return Factory|View
     */
    public function index($admin_perm_group_id)
    {
        $permissionGroup = AdminPermissionGroup::find($admin_perm_group_id);

        if($permissionGroup)
        {
            $permissionModule = AdminPermissionModule::find($permissionGroup["admin_perm_module_id"]);
            $permissionSystem = AdminPermissionSystem::find($permissionModule["admin_perm_system_id"]);

            $sysModUrl = URL::to("/admin/admin_permission_module/".$permissionModule["admin_perm_system_id"]);
            $modGroupUrl = URL::to("/admin/admin_permission_group/".$permissionGroup["admin_perm_module_id"]);

            $this->repository->setButton($sysModUrl, "System Modules", "btn-info", "fa fa-list")
                             ->setButton($modGroupUrl, "Modules Groups", "btn-info", "fa fa-list");

            $pageTitle = "System : ".$permissionSystem["system_name"]." | "." Module : ".$permissionModule["module_name"]." | Group : ".$permissionGroup["group_name"]." | System Permissions";
            $tableTitle = "System : ".$permissionSystem["system_name"]." &nbsp;<span class='fa fa-long-arrow-alt-right'></span>&nbsp; "." Module : ".$permissionModule["module_name"]." &nbsp;<span class='fa fa-long-arrow-alt-right'></span>&nbsp; Group : ".$permissionGroup["group_name"]." &nbsp;<span class='fa fa-long-arrow-alt-right'></span>&nbsp; System Permissions";

            $this->repository->setPageTitle($pageTitle);

            $this->repository->initDatatable(new AdminSystemPermission());
            $this->repository->viewData->tableTitle = "Admin System Permissions";

            $this->repository->viewData->enableExport = true;

            $this->repository->setColumns("id", "permission_title", "permission_action", "permission_status", "disabled_reason", "created_at")
                ->setColumnLabel("permission_status", "Status")
                ->setColumnDisplay("permission_status", array($this->repository, 'display_status_as'))
                ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

                ->setColumnFilterMethod("permission_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

                ->setColumnSearchability("created_at", false)
                ->setColumnSearchability("updated_at", false);

            if($this->trash)
            {
                $query = $this->repository->model::onlyTrashed();

                $this->repository->setTableTitle($tableTitle." | Trashed")
                    ->enableViewData("list", "restore", "export")
                    ->disableViewData("view", "edit", "delete")
                    ->setUrl("list",$this->repository->getUrl("list")."/".$admin_perm_group_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_group_id);
            }
            else
            {
                $query = $this->repository->model::query();

                $this->repository->setTableTitle($tableTitle)
                    ->enableViewData("trashList", "trash", "export")
                    ->setUrl("trashList",$this->repository->getUrl("trashList")."/".$admin_perm_group_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_group_id);
            }

            $query = $query->with([]);
            $query->where("admin_perm_group_id", "=", $admin_perm_group_id);

            return $this->repository->render("admin::layouts.master")->index($query);
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     * @param $admin_perm_group_id
     * @return Factory|View
     */
    public function trash($admin_perm_group_id)
    {
        $this->trash = true;
        return $this->index($admin_perm_group_id);
    }

    /**
     * Show the form for creating a new resource.
     * @param int $admin_perm_group_id
     * @return Factory|View
     */
    public function create($admin_perm_group_id)
    {
        $permissionGroup = AdminPermissionGroup::find($admin_perm_group_id);

        if($permissionGroup)
        {
            $this->repository->setPageTitle("Admin System Permissions | Add New");

            $permissionModule = AdminPermissionModule::find($permissionGroup["admin_perm_module_id"]);

            $admin_perm_system_id = $permissionModule["admin_perm_system_id"];
            $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

            $model = new AdminSystemPermission();
            $model->permissionGroup = $permissionGroup;
            $model->permissionModule = $permissionModule;
            $model->permissionSystem = $permissionSystem;

            $record = $model;

            $formMode = "add";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["listUrl"]=URL::to("/admin/admin_system_permission/".$admin_perm_group_id);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_system_perm.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param int $admin_perm_group_id
     * @return JsonResponse
     */
    public function store($admin_perm_group_id)
    {
        $model = new AdminSystemPermission();

        $model = $this->repository->getValidatedData($model, [
            "permission_title" => "required|min:3",
            "permission_action" => "required|min:3",
            "permission_status" => "required|digits:1",
            "disabled_reason" => [Rule::requiredIf(function () use ($model) { return $model->permission_status == "0";})],
        ], [], ["permission_title" => "Permission title", "permission_action" => "Permission action", "disabled_reason" => "Disabled reason"]);

        if($this->repository->isValidData)
        {
            $model->admin_perm_group_id = $admin_perm_group_id;
            $model->permission_key = $this->repository->generatePermissionHash($model->permission_action);
            $response = $this->repository->saveModel($model);
        }
        else
        {
            $response = $model;
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $model = AdminSystemPermission::with(["permissionGroup"])->find($id);

        if($model)
        {
            $this->repository->setPageTitle("Admin System Permissions | Edit");

            $permissionModule = AdminPermissionModule::find($model->permissionGroup["admin_perm_module_id"]);

            $admin_perm_system_id = $permissionModule["admin_perm_system_id"];
            $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

            $model->permissionModule = $permissionModule;
            $model->permissionSystem = $permissionSystem;

            $record = $model;

            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_system_permission/create/".$model["admin_perm_group_id"]);
            $urls["listUrl"]=URL::to("/admin/admin_system_permission/".$model["admin_perm_group_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_system_perm.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @return JsonResponse
     */
    public function update($id)
    {
        $model = AdminSystemPermission::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "permission_title" => "required|min:3",
                "permission_action" => "required|min:3",
                "permission_status" => "required|digits:1",
                "disabled_reason" => [Rule::requiredIf(function () use ($model) { return $model->permission_status == "0";})],
            ], [], ["permission_title" => "Permission title", "permission_action" => "Permission action", "disabled_reason" => "Disabled reason"]);

            if($this->repository->isValidData)
            {
                $model->permission_key = $this->repository->generatePermissionHash($model->permission_action);
                $response = $this->repository->saveModel($model);
            }
            else
            {
                $response = $model;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details saving was failed. Requested record does not exist.";

            $response["notify"]=$notify;
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Move the record to trash
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function delete($id)
    {
        $model = AdminSystemPermission::find($id);

        if($model)
        {
            if($model->delete())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully moved the record to trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details moving to trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details moving to trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Restore record
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function restore($id)
    {
        $model = AdminSystemPermission::withTrashed()->find($id);

        if($model)
        {
            if($model->restore())
            {
                $notify = array();
                $notify["status"]="success";
                $notify["notify"][]="Successfully restored the record from trash.";

                $dataResponse["notify"]=$notify;
            }
            else
            {
                $notify = array();
                $notify["status"]="failed";
                $notify["notify"][]="Details restoring from trash was failed. Unknown error occurred.";

                $dataResponse["notify"]=$notify;
            }
        }
        else
        {
            $notify = array();
            $notify["status"]="failed";
            $notify["notify"][]="Details restoring from trash was failed. Requested record does not exist.";

            $dataResponse["notify"]=$notify;
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Search records
     * @param Request $request
     * @return JsonResponse
     */
    public function searchData(Request $request)
    {
        if($request->expectsJson())
        {
            $searchText = $request->post("query");
            $idNot = $request->post("idNot");

            $query = AdminSystemPermission::query()
                ->select("group_id", "permission_title")
                ->where("permission_status", "=", "1")
                ->orderBy("permission_title")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("permission_title", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("group_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
