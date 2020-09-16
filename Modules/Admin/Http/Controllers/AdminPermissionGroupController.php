<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionGroup;
use Modules\Admin\Entities\AdminPermissionModule;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Repositories\AdminPermissionGroupRepository;

class AdminPermissionGroupController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminPermissionGroupRepository();
    }

    /**
     * Display a listing of the resource.
     * @param int $admin_perm_module_id
     * @return Factory|View
     */
    public function index($admin_perm_module_id)
    {
        $permissionModule = AdminPermissionModule::find($admin_perm_module_id);

        if($permissionModule)
        {
            $permissionSystem = AdminPermissionSystem::find($permissionModule["admin_perm_system_id"]);

            $sysModUrl = URL::to("/admin/admin_permission_module/".$permissionModule["admin_perm_system_id"]);

            $this->repository->setButton($sysModUrl, "System Modules", "btn-info", "fa fa-list");

            $pageTitle = "System : ".$permissionSystem["system_name"]." | "." Module : ".$permissionModule["module_name"]." | Permission Groups";
            $tableTitle = "System : ".$permissionSystem["system_name"]." &nbsp;<span class='fa fa-long-arrow-alt-right'></span>&nbsp; "." Module : ".$permissionModule["module_name"]." &nbsp;<span class='fa fa-long-arrow-alt-right'></span>&nbsp; Permission Groups";

            $this->repository->setPageTitle($pageTitle);

            $this->repository->initDatatable(new AdminPermissionGroup());

            $this->repository->setColumns("id", "group_name", "permissions", "group_status", "created_at")
                ->setColumnLabel("group_name", "Group Name")
                ->setColumnLabel("permissions", "Group Permissions")
                ->setColumnLabel("group_status", "Status")
                ->setColumnDisplay("group_status", array($this->repository, 'display_status_as'))
                ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))
                ->setColumnDisplay("permissions", array($this->repository, 'display_permissions_as'))

                ->setColumnFilterMethod("group_name", "text")
                ->setColumnFilterMethod("group_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

                ->setColumnSearchability("created_at", false)
                ->setColumnSearchability("updated_at", false)

                ->setColumnDBField("permissions", $this->repository->primaryKey)
                ->setColumnSearchability("permissions", false);

            if($this->trash)
            {
                $query = $this->repository->model::onlyTrashed();

                $this->repository->setTableTitle($tableTitle." | Trashed")
                    ->enableViewData("list", "restore", "export")
                    ->disableViewData("view", "edit", "delete")
                    ->setUrl("list",$this->repository->getUrl("list")."/".$admin_perm_module_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_module_id);
            }
            else
            {
                $query = $this->repository->model::query();

                $this->repository->setTableTitle($tableTitle)
                    ->enableViewData("trashList", "trash", "export")
                    ->setUrl("trashList",$this->repository->getUrl("trashList")."/".$admin_perm_module_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_module_id);
            }

            $query = $query->with(["permissionModule"]);

            $query->where("admin_perm_module_id", "=", $admin_perm_module_id);

            return $this->repository->render("admin::layouts.master")->index($query);
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     * @param $admin_perm_module_id
     * @return Factory|View
     */
    public function trash($admin_perm_module_id)
    {
        $this->trash = true;
        return $this->index($admin_perm_module_id);
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create($admin_perm_module_id)
    {
        $permissionModule = AdminPermissionModule::find($admin_perm_module_id);

        if($permissionModule)
        {
            $this->repository->setPageTitle("Admin Permission Groups | Add New");

            $admin_perm_system_id = $permissionModule["admin_perm_system_id"];
            $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

            $model = new AdminPermissionGroup();
            $model->permissionModule = $permissionModule;
            $model->permissionSystem = $permissionSystem;

            $record = $model;

            $formMode = "add";
            $formSubmitUrl = request()->getPathInfo();

            $urls = [];
            $urls["listUrl"]=URL::to("/admin/admin_permission_group/".$admin_perm_module_id);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_group.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store($admin_perm_module_id)
    {
        $permissionModule = AdminPermissionModule::find($admin_perm_module_id);

        if($permissionModule)
        {
            $model = new AdminPermissionGroup();

            $model = $this->repository->getValidatedData($model, [
                "group_name" => "required|min:3",
                "group_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["group_name" => "Group name"]);

            if($this->repository->isValidData)
            {
                $model->admin_perm_module_id = $admin_perm_module_id;
                $response = $this->repository->saveModel($model);
            }
            else
            {
                $response = $model;
            }
        }
        else
        {
            $response["notify"]["status"]="failed";
            $response["notify"]["notify"][]="Selected permission module does not exist.";
        }

        return $this->repository->handleResponse($response);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $this->repository->setPageTitle("Admin Permission Groups | View");

        $model = AdminPermissionGroup::with(["permissionModule"])->find($id);

        if($model)
        {
            $record = $model;

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_permission_group/create/".$model["admin_perm_module_id"]);
            $urls["listUrl"]=URL::to("/admin/admin_permission_group/".$model["admin_perm_module_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_group.view', compact( 'record'));
        }
        else
        {
            abort(404, "Requested record does not exist.");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function edit($id)
    {
        $model = AdminPermissionGroup::with(["permissionModule"])->find($id);

        if($model)
        {
            $this->repository->setPageTitle("Admin Permission Groups | Edit");

            $model->permissionSystem = AdminPermissionSystem::find($model->permissionModule["admin_perm_system_id"]);

            $record = $model;

            $formMode = "edit";
            $formSubmitUrl = request()->getPathInfo();

            $admin_perm_system_id = $record["permission_module"]["admin_perm_system_id"];
            $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_permission_group/create/".$model->permissionModule["admin_perm_module_id"]);
            $urls["listUrl"]=URL::to("/admin/admin_permission_group/".$model->permissionModule["admin_perm_module_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_group.create', compact('formMode', 'formSubmitUrl', 'record', 'permissionSystem'));
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
        $model = AdminPermissionGroup::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "group_name" => "required|min:3",
                "group_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["group_name" => "Group name"]);

            if($this->repository->isValidData)
            {
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
        $model = AdminPermissionGroup::find($id);

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
        $model = AdminPermissionGroup::withTrashed()->find($id);

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

            $query = AdminPermissionGroup::query()
                ->select("group_id", "group_name")
                ->where("group_status", "=", "1")
                ->orderBy("group_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("group_name", "LIKE", $searchText."%");
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
