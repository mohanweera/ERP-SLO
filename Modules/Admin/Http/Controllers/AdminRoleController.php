<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminRole;
use Modules\Admin\Repositories\AdminPermissionSystemRepository;
use Modules\Admin\Repositories\AdminRoleRepository;

class AdminRoleController extends Controller
{
    private $repository = null;
    private $trash = false;

    public function __construct()
    {
        $this->repository = new AdminRoleRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("Administrator Roles");

        $this->repository->initDatatable(new AdminRole());
        $this->repository->viewData->tableTitle = "Administrator Roles";

        $this->repository->viewData->enableExport = true;

        $this->repository->setColumns("id", "role_name", "description", "role_status", "created_at")
            ->setColumnLabel("role_status", "Status")
            ->setColumnDisplay("role_status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnFilterMethod("role_name", "text")
            ->setColumnFilterMethod("role_status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])

            ->setColumnSearchability("role_status", false)
            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false);

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->setTableTitle("Administrator Roles | Trashed")
                ->enableViewData("list", "restore", "export")
                ->disableViewData("edit", "delete");
        }
        else
        {
            $query = $this->repository->model::query();

            $this->repository->setTableTitle("Administrator Roles")
                ->enableViewData("view", "trashList", "trash", "export");
        }

        return $this->repository->render("admin::layouts.master")->index($query);
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function trash()
    {
        $this->trash = true;
        return $this->index();
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create()
    {
        $this->repository->setPageTitle("Administrator Roles | Add New");

        $model = new AdminRole();
        $record = $model;
        $model->allowedRoles = [];

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        $urls = [];
        $urls["listUrl"]=URL::to("/admin/admin_role");

        $this->repository->setPageUrls($urls);

        $systems = AdminPermissionSystem::query()->where("system_status", "=", "1")->get();

        $systemPermissions = [];
        if(count($systems)>0)
        {
            $adminPermSysRepo = new AdminPermissionSystemRepository();
            foreach ($systems as $key => $system)
            {
                $systemModules = $system->permissionModules()->get()->toArray();
                $systemModules = $adminPermSysRepo->getSystemPermissionModules($systemModules);

                $system["modules"] = $systemModules;
                $system["curr_permissions"] = [];

                $systems->$key = $system;
            }

            $systemPermissions = $systems->toArray();
        }

        return view('admin::admin_role.create', compact('formMode', 'formSubmitUrl', 'record', 'systemPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new AdminRole();

        $model = $this->repository->getValidatedData($model, [
            "role_name" => "required|min:3",
            "description" => "",
            "role_status" => "required|digits:1",
            "allowed_roles" => "array"
        ]);

        $dataResponse = $this->repository->saveModel($model);

        if($dataResponse["notify"]["status"] == "success")
        {
            AdminRoleRepository::updatePermission($model->id);
        }

        return $this->repository->handleResponse($dataResponse);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Factory|View
     */
    public function show($id)
    {
        $model = AdminRole::find($id);

        if($model)
        {
            $this->repository->setPageTitle("Administrator Role | ".$model["role_name"]);

            $record = $model->toArray();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_role/create");
            $urls["editUrl"]=URL::to("/admin/admin_role/edit/");
            $urls["listUrl"]=URL::to("/admin/admin_role");
            $urls["historyUrl"]=URL::to("/admin/admin_role_permission_history/");

            $this->repository->setPageUrls($urls);

            $systems = AdminPermissionSystem::query()->where("system_status", "=", "1")->get();

            $systemPermissions = [];
            if(count($systems)>0)
            {
                $adminPermSysRepo = new AdminPermissionSystemRepository();
                $allSystemCurrPerms = AdminRoleRepository::getAllSystemPermissionData($id);
                foreach ($systems as $key => $system)
                {
                    $systemModules = $system->permissionModules()->get()->toArray();
                    $systemModules = $adminPermSysRepo->getSystemPermissionModules($systemModules);

                    $system["modules"] = $systemModules;
                    $system["curr_permissions"] = [];
                    if(isset($allSystemCurrPerms[$system->id]))
                    {
                        $system["curr_permissions"] = $allSystemCurrPerms[$system->id];
                    }

                    $systems->$key = $system;
                }

                $systemPermissions = $systems->toArray();
            }

            return view('admin::admin_role.view', compact( 'record', 'systemPermissions'));
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
        $model = AdminRole::find($id);

        if($model)
        {
            $record = $model->toArray();
            $formMode = "edit";
            $formSubmitUrl = "/".request()->path();

            $urls = [];
            $urls["addUrl"]=URL::to("/admin/admin_role/create");
            $urls["listUrl"]=URL::to("/admin/admin_role");
            $urls["historyUrl"]=URL::to("/admin/history/");

            $this->repository->setPageUrls($urls);

            $systems = AdminPermissionSystem::query()->where("system_status", "=", "1")->get();

            $systemPermissions = [];
            if(count($systems)>0)
            {
                $adminPermSysRepo = new AdminPermissionSystemRepository();
                $allSystemCurrPerms = AdminRoleRepository::getAllSystemPermissionData($id);
                foreach ($systems as $key => $system)
                {
                    $systemModules = $system->permissionModules()->get()->toArray();
                    $systemModules = $adminPermSysRepo->getSystemPermissionModules($systemModules);

                    $system["modules"] = $systemModules;
                    $system["curr_permissions"] = [];
                    if(isset($allSystemCurrPerms[$system->id]))
                    {
                        $system["curr_permissions"] = $allSystemCurrPerms[$system->id];
                    }

                    $systems->$key = $system;
                }

                $systemPermissions = $systems->toArray();
            }

            return view('admin::admin_role.create', compact('formMode', 'formSubmitUrl', 'record', 'systemPermissions'));
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
        $model = AdminRole::find($id);

        if($model)
        {
            $model = $this->repository->getValidatedData($model, [
                "role_name" => "required|min:3",
                "description" => "",
                "role_status" => "required|digits:1",
                "allowed_roles" => "array"
            ]);

            if($this->repository->isValidData)
            {
                $response = $this->repository->saveModel($model);

                if($response["notify"]["status"] == "success")
                {
                    AdminRoleRepository::updatePermission($model->id);
                }
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
        $model = AdminRole::find($id);

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
     * Move the record to trash
     * @param int $id
     * @return JsonResponse|RedirectResponse
     */
    public function restore($id)
    {
        $model = AdminRole::withTrashed()->find($id);

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
     * Move the record to trash
     * @param Request $request
     * @return JsonResponse
     */
    public function searchData(Request $request)
    {
        if($request->expectsJson())
        {
            $searchText = $request->post("query");
            $idNot = $request->post("idNot");

            $query = AdminRole::query()
                ->select("admin_role_id", "role_name")
                ->where("role_status", "=", "1")
                ->orderBy("role_name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("role_name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("admin_role_id", [$idNot]);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }
}
