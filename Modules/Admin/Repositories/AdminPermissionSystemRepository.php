<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionGroup;
use Modules\Admin\Entities\AdminPermissionModule;
use Modules\Admin\Entities\AdminPermissionSystem;
use Modules\Admin\Entities\AdminSystemPermission;
use Modules\Admin\Services\Permission;

class AdminPermissionSystemRepository extends BaseRepository
{
    /**
     * Return column UI for the datatable of the model
     * @return Factory|View
     */
    public function display_modules_as()
    {
        $moduleUrl = URL::to("/admin/admin_permission_module/");
        return view("admin::admin_perm_system.datatable.modules_ui", compact('moduleUrl'));
    }

    /**
     * Get admin permission system id using system slug
     * @param string $system
     * @return bool|int
     */
    public function getSystemId($system)
    {
        $model = new AdminPermissionSystem();
        $permissionSystem = $model::query()->where("system_slug", '=', $system)->first();

        if($permissionSystem)
        {
            $primaryKey = $model->getKeyName();
            return $permissionSystem->$primaryKey;
        }

        return false;
    }

    /**
     * @param $permissionSystem
     * @return array
     */
    public function getSystemPermissionModules($permissionModules)
    {
        $currModules = [];
        if($permissionModules)
        {
            //get module ids list
            $moduleIds = [];
            $modules = [];

            $moduleModel = new AdminPermissionModule();
            $modulePriKey = $moduleModel->getKeyName();

            if(is_array($permissionModules) && count($permissionModules)>0)
            {
                foreach ($permissionModules as $module)
                {
                    $moduleId = $module[$modulePriKey];
                    $moduleIds[]=$moduleId;

                    $module["module_id"]=$moduleId;
                    $module["name"]=$module["module_name"];
                    $module["slug"]=$module["module_slug"];

                    $module["groups"]=[];
                    $modules[$moduleId]=$module;
                }
            }

            //get groups ids list of above module ids list
            $groupModel = new AdminPermissionGroup();
            $groupPriKey = $groupModel->getKeyName();

            $groupIds = [];
            $groups = [];
            $permissionGroups = $groupModel::query()->whereIn("admin_perm_module_id", $moduleIds)->get()->toArray();

            if(is_array($permissionGroups) && count($permissionGroups)>0)
            {
                foreach ($permissionGroups as $group)
                {
                    $groupId = $group[$groupPriKey];
                    $groupIds[]=$groupId;

                    $group["group_id"]=$groupId;
                    $group["name"]=$group["group_name"];
                    $group["slug"]=$group["group_slug"];

                    $group["perms"]=[];
                    $groups[$groupId]=$group;
                }
            }

            //get permissions of above group ids list
            $permModel = new AdminSystemPermission();
            $permPriKey = $permModel->getKeyName();

            $systemPerms = $permModel::query()->whereIn("admin_perm_group_id", $groupIds)->get()->toArray();
            if(is_array($systemPerms) && count($systemPerms)>0)
            {
                foreach ($systemPerms as $perm)
                {
                    $permId = $perm[$permPriKey];
                    $groupId = $perm["admin_perm_group_id"];
                    $group = $groups[$groupId];

                    $moduleId = $group["admin_perm_module_id"];
                    $module = $modules[$moduleId];

                    if(isset($module["groups"][$groupId]))
                    {
                        $group = $module["groups"][$groupId];
                    }

                    $perm["perm_id"]=$permId;
                    $perm["name"]=$perm["permission_title"];
                    $perm["action"]=$perm["permission_action"];
                    $perm["hash"]=$perm["permission_key"];

                    $group["permissions"][]=$perm;
                    $module["groups"][$groupId]= $group;

                    $modules[$moduleId]=$module;
                }
            }

            $currModules = $modules;

            unset($moduleIds);
            unset($groupIds);
            unset($modules);
            unset($groups);
            unset($permissionModules);
            unset($permissionGroups);
            unset($systemPerms);
        }

        return $currModules;
    }

    /**
     * @param $repository
     * @return mixed
     */
    public function importPermissions($repository)
    {
        $admin_perm_system_id = request()->post("admin_perm_system_id");

        if($admin_perm_system_id != "")
        {
            $modules = Permission::getImportingFormData($repository);
            if(is_array($modules) && count($modules)>0)
            {
                DB::beginTransaction();

                $saved = false;
                try {
                    $adminPermMod = new AdminPermissionModule();
                    $modPriKey = $adminPermMod->getKeyName();

                    $adminPermGroup = new AdminPermissionGroup();
                    $groupPriKey = $adminPermGroup->getKeyName();

                    $adminSysPerm = new AdminSystemPermission();

                    foreach($modules as $module)
                    {
                        $module["admin_perm_system_id"]=$admin_perm_system_id;
                        $module["module_status"]=1;

                        $groups = $module["groups"];
                        $moduleId = $module["module_id"];

                        unset($module["groups"]);
                        unset($module["module_id"]);

                        if($moduleId != "")
                        {
                            $adminPermMod::query()->where($modPriKey, "=", $moduleId)->update($module);
                        }
                        else
                        {
                            $module = $adminPermMod::updateOrCreate(["admin_perm_system_id" => $admin_perm_system_id, "module_slug" => $module["module_slug"]], $module);
                            $moduleId = $module->$modPriKey;
                        }

                        if(count($groups)>0)
                        {
                            foreach ($groups as $group)
                            {
                                $group[$modPriKey]=$moduleId;
                                $group["group_status"]=1;

                                $perms = $group["perms"];
                                $groupId = $group["group_id"];

                                unset($group["perms"]);
                                unset($group["group_id"]);

                                if($groupId != "")
                                {
                                    $adminPermGroup::query()->where($groupPriKey, "=", $groupId)->update($group);
                                }
                                else
                                {
                                    $group = $adminPermGroup::updateOrCreate(["admin_perm_module_id" => $moduleId, "group_slug" => $group["group_slug"]], $group);
                                    $groupId = $group->$groupPriKey;
                                }

                                if(count($perms)>0)
                                {
                                    foreach ($perms as $perm)
                                    {
                                        $perm[$groupPriKey]=$groupId;
                                        $perm["permission_status"]=1;

                                        $adminSysPerm::updateOrCreate(["admin_perm_group_id" => $groupId, "permission_key" => $perm["permission_key"]], $perm);
                                    }
                                }
                            }
                        }
                    }
                    DB::commit();

                    $saved = true;
                }
                catch (Exception $error)
                {
                    DB::rollBack();
                }

                if($saved)
                {
                    $response["status"]="success";
                    $response["notify"][]="Successfully imported the permissions.";
                }
                else
                {
                    $response["status"]="failed";
                    $response["notify"][]="Error occurred while importing permissions.";
                }
            }
            else
            {
                $response["status"]="failed";
                $response["notify"][]="Please select permissions before import.";
            }
        }
        else
        {
            $response["status"]="failed";
            $response["notify"][]="Please select system before import.";
        }

        return $response;
    }
}
