<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionModule;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Repositories\AdminPermissionModuleRepository;

class AdminPermissionModuleController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminPermissionModuleRepository();
    }

    /**
     * Display a listing of the resource.
     * @param $admin_perm_system_id
     * @return Response
     */
    public function index($admin_perm_system_id)
    {
        $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

        if($permissionSystem)
        {
            $pageTitle = "System : ".$permissionSystem["system_name"]." | "." Permission Modules";
            $tableTitle = "System : ".$permissionSystem["system_name"]." <span class='fa fa-long-arrow-alt-right'></span> "." Permission Modules";

            $this->repository->setPageTitle($pageTitle);

            $this->repository->initDatatable(new AdminPermissionModule());

            $this->repository->setColumns("id", "module_name", "groups", "module_status", "created_at")
                ->setColumnLabel("module_name", "Module Name")
                ->setColumnLabel("groups", "Permission Groups")
                ->setColumnLabel("module_status", "Status")
                ->setColumnDisplay("module_status", array($this->repository, 'display_status_as'))
                ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))
                ->setColumnDisplay("groups", array($this->repository, 'display_groups_as'))

                ->setColumnFilterMethod("module_name", "text")
                ->setColumnFilterMethod("module_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

                ->setColumnSearchability("created_at", false)
                ->setColumnSearchability("updated_at", false)

                ->setColumnDBField("groups", $this->repository->primaryKey)
                ->setColumnSearchability("groups", false);

            if($this->trash)
            {
                $query = $this->repository->model::onlyTrashed();

                $this->repository->setTableTitle($tableTitle." | Trashed")
                    ->enableViewData("list", "restore", "export")
                    ->disableViewData("view", "edit", "delete")
                    ->setUrl("list",$this->repository->getUrl("list")."/".$admin_perm_system_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_system_id);
            }
            else
            {
                $query = $this->repository->model::query();

                $this->repository->setTableTitle($tableTitle)
                    ->enableViewData("trashList", "trash", "export")
                    ->setUrl("trashList",$this->repository->getUrl("trashList")."/".$admin_perm_system_id)
                    ->setUrl("add",$this->repository->getUrl("add")."/".$admin_perm_system_id);
            }

            $query = $query->with(["permissionSystem"]);

            $query->where("admin_perm_system_id", "=", $admin_perm_system_id);

            return $this->repository->render("admin::layouts.master")->index($query);
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Display a listing of the resource.
     * @param $admin_perm_system_id
     * @return Response
     */
    public function trash($admin_perm_system_id)
    {
        $this->trash = true;
        return $this->index($admin_perm_system_id);
    }

    /**
     * Show the form for creating a new resource.
     * @param int $admin_perm_system_id
     * @return Factory|View
     */
    public function create($admin_perm_system_id)
    {
        $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

        if($permissionSystem)
        {
            $this->repository->setPageTitle("Admin Permission Modules | Add New");

            $model = new AdminPermissionModule();
            $model->permissionSystem = $permissionSystem;

            $record = $model;

            $formMode = "add";
            $formSubmitUrl = request()->getPathInfo();

            $urls = [];
            $urls["listUrl"]=URL::to("/admin/admin_permission_module/".$admin_perm_system_id);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_module.create', compact('formMode', 'formSubmitUrl', 'record'));
        }
        else
        {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param AdminPermissionSystem $admin_perm_system_id
     * @return JsonResponse
     */
    public function store($admin_perm_system_id)
    {
        $permissionSystem = AdminPermissionSystem::find($admin_perm_system_id);

        if($permissionSystem)
        {
            $model = new AdminPermissionModule();

            $model = $this->repository->getValidatedData($model, [
                "module_name" => "required|min:3",
                "module_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["module_name" => "Module name"]);

            if($this->repository->isValidData)
            {
                $model->admin_perm_system_id = $admin_perm_system_id;
                $response = $this->repository->saveModel($model);
            }
            else
            {
                $response = $model;
            }

            return $this->repository->handleResponse($response);
        }
        else
        {
            $response["notify"]["status"]="failed";
            $response["notify"]["notify"][]="Selected permission system does not exist.";
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
        $this->repository->setPageTitle("Admin Permission Modules | View");

        $model = AdminPermissionModule::with(["permissionSystem"])->find($id);

        if($model)
        {
            $record = $model->toArray();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_permission_module/create/".$model["admin_perm_system_id"]);
            $urls["listUrl"]=URL::to("/admin/admin_permission_module/".$model["admin_perm_system_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_module.view', compact( 'record'));
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
        $this->repository->setPageTitle("Admin Permission Modules | Edit");

        $model = AdminPermissionModule::with(["permissionSystem"])->find($id);

        if($model)
        {
            $record = $model;

            $formMode = "edit";
            $formSubmitUrl = request()->getPathInfo();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_permission_module/create/".$model["admin_perm_system_id"]);
            $urls["listUrl"]=URL::to("/admin/admin_permission_module/".$model["admin_perm_system_id"]);

            $this->repository->setPageUrls($urls);

            return view('admin::admin_perm_module.create', compact('formMode', 'formSubmitUrl', 'record'));
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
        $model = AdminPermissionModule::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "module_name" => "required|min:3",
                "module_status" => "required|digits:1",
                "remarks" => "",
            ], [], ["admin_perm_system_id" => "Permission system", "module_name" => "Module name"]);

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
        $model = AdminPermissionModule::find($id);

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
        $model = AdminPermissionModule::withTrashed()->find($id);

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

            $query = AdminPermissionModule::query()
                ->select("module_id", "module_name")
                ->where("module_status", "=", "1")
                ->orderBy("module_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("module_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("module_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
