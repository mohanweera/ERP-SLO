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
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminRolePermission;
use Modules\Admin\Repositories\AdminPermissionSystemRepository;
use Modules\Admin\Repositories\AdminRepository;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Repositories\AdminRoleRepository;
use Modules\Admin\Services\Permission;

class AdminController extends Controller
{
    private $repository = null;
    private $trash = false;
    private $invRevStatus = 1;

    public function __construct()
    {
        $this->repository = new AdminRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index()
    {
        $this->repository->setPageTitle("System Administrators");

        $this->repository->initDatatable(new Admin());

        $this->repository->setColumns("id", "name", "email", "admin_role", "status", "created_at", "updated_at")
            ->setColumnLabel("name", "Admin")
            ->setColumnLabel("status", "Status")
            ->setColumnDisplay("status", array($this->repository, 'display_status_as'))
            ->setColumnDisplay("admin_role", array($this->repository, 'display_admin_role_as'))
            ->setColumnDisplay("created_at", array($this->repository, 'display_created_at_as'))

            ->setColumnVisibility("updated_at", false)

            ->setColumnFilterMethod("name", "text")
            ->setColumnFilterMethod("status", "select", [["id" =>"1", "name" =>"Enabled"], ["id" =>"0", "name" =>"Disabled"]])
            ->setColumnFilterMethod("admin_role", "select", URL::to("/admin/admin_role/search_data"))

            ->setColumnSearchability("created_at", false)
            ->setColumnSearchability("updated_at", false)

            ->setColumnDBField("admin_role", "admin_role_id")
            ->setColumnFKeyField("admin_role", "admin_role_id")
            ->setColumnRelation("admin_role", "adminRole", "role_name");

        if($this->trash)
        {
            $query = $this->repository->model::onlyTrashed();

            $this->repository->setTableTitle("System Administrators | Trashed")
                ->enableViewData("list", "restore", "export")
                ->disableViewData("view", "edit", "delete");
        }
        else
        {
            $query = $this->repository->model::query();

            $this->repository->setTableTitle("System Administrators")
                ->enableViewData("view", "trashList", "trash", "export");
        }

        $query = $query->with(["adminRole"]);

        $defaultAdmin = request()->session()->get("default_admin");

        if(!$defaultAdmin)
        {
            $allowed_roles = request()->session()->get("allowed_roles");
            $query = $query->whereIn("admin_role_id", $allowed_roles);
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
        $model = new Admin();
        $record = $model;

        $formMode = "add";
        $formSubmitUrl = "/".request()->path();

        $urls = [];
        $urls["listUrl"]=URL::to("/admin/admin");

        $this->repository->setPageUrls($urls);

        return view('admin::admin.create', compact('formMode', 'formSubmitUrl', 'record'));
    }

    /**
     * Store a newly created resource in storage.
     * @return JsonResponse
     */
    public function store()
    {
        $model = new Admin();

        $model = $this->repository->getValidatedData($model, [
            "admin_role_id" => "required|exists:admin_roles,admin_role_id",
            "name" => "required|min:3",
            "email" => "unique:Modules\Admin\Entities\Admin,email",
            "password" => "required",
            "status" => "required|digits:1",
            "allowed_roles" => "array",
            "disallowed_roles" => "array",
            "disabled_reason" => [Rule::requiredIf(function () use ($model) { return $model->status == "0";})],
        ], [], ["admin_role_id" => "Administrator Role", "name" => "Administrator name"]);

        if($this->repository->isValidData)
        {
            $superUser = request()->session()->get("super_user");
            if($superUser)
            {
                $model->super_user = request()->post("super_user");
            }
            $response = $this->repository->saveModel($model);
        }
        else
        {
            $response = $model;
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
        $model = Admin::find($id);

        if($model)
        {
            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($model->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
            {
                $this->repository->setPageTitle("Administrator | ".$model["name"]);

                $record = $model->toArray();

                $urls = [];
                $urls["addUrl"]=URL::to("/admin/admin/create");
                $urls["editUrl"]=URL::to("/admin/admin/edit/");
                $urls["listUrl"]=URL::to("/admin/admin");
                $urls["historyUrl"]=URL::to("/admin/admin_permission_history/");

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

                        $systemCurrPerms = Permission::getPermissions($id, $record["admin_role_id"], $system->id);

                        $system["modules"] = $systemModules;
                        $system["currPermissions"] = $systemCurrPerms;

                        $systems->$key = $system;
                    }

                    $systemPermissions = $systems->toArray();
                }

                return view('admin::admin.view', compact('record', 'systemPermissions'));
            }
            else
            {
                abort(403, "You don't have permission perform this operation.");
            }
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
        $model = Admin::with(["adminRole"])->find($id);

        if($model)
        {
            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($model->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
            {
                $record = $model->toArray();
                $formMode = "edit";
                $formSubmitUrl = "/".request()->path();

                $urls = [];
                $urls["addUrl"]=URL::to("/admin/admin/create");
                $urls["listUrl"]=URL::to("/admin/admin");

                $this->repository->setPageUrls($urls);

                return view('admin::admin.create', compact('formMode', 'formSubmitUrl', 'record'));
            }
            else
            {
                abort(403, "You don't have permission perform this operation.");
            }
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
        $model = Admin::find($id);

        if($model)
        {
            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($model->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
            {
                $model = $this->repository->getValidatedData($model, [
                    "admin_role_id" => "required|exists:admin_roles,admin_role_id",
                    "name" => "required|min:3",
                    "email" => [Rule::unique(Admin::class, "email")->ignore($model->admin_id, $model->getKeyName())],
                    "status" => "required|digits:1",
                    "allowed_roles" => "array",
                    "disallowed_roles" => "array",
                    "disabled_reason" => [Rule::requiredIf(function () use ($model) { return $model->status == "0";})],
                ], [], ["admin_role_id" => "Administrator Role", "name" => "Administrator name"]);

                if($this->repository->isValidData)
                {
                    $superUser = request()->session()->get("super_user");
                    if($superUser)
                    {
                        $model->super_user = request()->post("super_user");
                    }
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
                $notify["notify"][]="You don't have permission perform this operation.";

                $response["notify"]=$notify;
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
        $model = Admin::find($id);

        if($model)
        {
            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($model->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
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
                abort(403, "You don't have permission perform this operation.");
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
        $model = Admin::withTrashed()->find($id);

        if($model)
        {
            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($model->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
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
                abort(403, "You don't have permission perform this operation.");
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

            $query = Admin::query()
                ->select("admin_id", "name")
                ->where("status", "=", "1")->where("default_admin", "!=", "1")
                ->orderBy("name")
                ->limit(10);

            if($searchText != "")
            {
                $query = $query->where("name", "LIKE", $searchText."%");
            }

            if($idNot != "")
            {
                $query = $query->whereNotIn("admin_id", [$idNot]);
            }

            $defaultAdmin = request()->session()->get("default_admin");

            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");
                $query = $query->whereIn("admin_role_id", $allowed_roles);
            }

            $data = $query->get();

            return response()->json($data, 201);
        }

        abort("403", "You are not allowed to access this data");
    }

    /**
     * @param string $adminId
     * @param string $systemId
     * @return Factory|View
     */
    public function grantPermissions($adminId="", $systemId="")
    {
        $formSubmitUrl = "/".request()->path();

        if($adminId != "" && $systemId != "")
        {
            $admin = Admin::find($adminId);

            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($admin->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
            {
                $permissionSystem = AdminPermissionSystem::find($systemId);

                if($permissionSystem)
                {
                    $invRevStatus = $this->invRevStatus;

                    $aPSRepo = new AdminPermissionSystemRepository();
                    $permissionModules = $permissionSystem->permissionModules()->get()->toArray();
                    $systemPermissions = $aPSRepo->getSystemPermissionModules($permissionModules);

                    $permissionSystem = $permissionSystem->toArray();
                    $permissionSystem["modules"] = $systemPermissions;

                    $adminPermissions = AdminRepository::getPermissionData($adminId, $systemId);
                    $adminPermissions = AdminRepository::getPermissionDataExtract($adminPermissions, $invRevStatus);
                    $adminRolePermissions = AdminRoleRepository::getPermissionData($admin->admin_role_id, $systemId);

                    return view("admin::admin.grant", compact('formSubmitUrl', 'admin', 'permissionSystem', 'adminPermissions', 'adminRolePermissions', 'invRevStatus'));
                }
                else
                {
                    $response["status"]="failed";
                    $response["notify"][]="Please select both admin & system to proceed with the permission management.";

                    $this->repository->handleResponse($response, false);

                    $del = "/";
                    $formSubmitUrl = explode($del, $formSubmitUrl);
                    array_pop($formSubmitUrl); //removes $systemId
                    array_pop($formSubmitUrl); //removes $adminId
                    $formSubmitUrl = implode($del, $formSubmitUrl);

                    $permSystems = AdminPermissionSystem::query()->get()->toArray();
                    return view("admin::admin.permission_select", compact('formSubmitUrl', 'admin', 'permSystems'));
                }
            }
            else
            {
                abort(403, "You don't have permission perform this operation.");
            }
        }
        else
        {
            $permSystems = AdminPermissionSystem::query()->get()->toArray();
            return view("admin::admin.permission_select", compact('formSubmitUrl', 'permSystems'));
        }
    }

    /**
     * @param string $adminId
     * @param string $systemId
     * @return Factory|View
     */
    public function revokePermissions($adminId="", $systemId="")
    {
        $this->invRevStatus = 0;
        return $this->grantPermissions($adminId, $systemId);
    }

    /**
     * @param string $adminId
     * @param string $systemId
     * @return Factory|View
     */
    public function grantRevokeSubmit($adminId="", $systemId="")
    {
        if($adminId != "" && $systemId != "")
        {
            $admin = Admin::find($adminId);

            $defaultAdmin = request()->session()->get("default_admin");

            $allowed = true;
            if(!$defaultAdmin)
            {
                $allowed_roles = request()->session()->get("allowed_roles");

                if(!in_array($admin->admin_role_id, $allowed_roles))
                {
                    $allowed = false;
                }
            }

            if($allowed)
            {
                $permissionSystem = AdminPermissionSystem::find($systemId);

                if($admin && $permissionSystem)
                {
                    $invRevStatus = request()->post("inv_rev_status");
                    $response = $this->repository->updatePermission($adminId, $admin->admin_role_id, $systemId, $invRevStatus);
                }
                else
                {
                    $response["status"]="failed";
                    $response["notify"][]="Please select a system before import.";
                }
            }
            else
            {
                abort(403, "You don't have permission perform this operation.");
            }
        }
        else
        {
            $response["status"]="failed";
            $response["notify"][]="Please select a system before import.";
        }

        return $this->repository->handleResponse($response);
    }
}
