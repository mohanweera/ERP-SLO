<?php

namespace Modules\Admin\Services;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Repositories\AdminPermissionSystemRepository;
use Modules\Admin\Repositories\AdminRepository;
use Modules\Admin\Repositories\AdminRoleRepository;
use Modules\Admin\Repositories\AdminSystemPermissionRepository;
use Nwidart\Modules\Facades\Module;

class Permission
{
    /**
     * Get all the permission handling system slugs
     * @return array
     */
    public static function getPermSystemSlugs()
    {
        $systems = self::getPermSystems();

        return array_keys($systems);
    }

    /**
     * Get all the permission handling systems
     * @return array
     */
    public static function getPermSystems()
    {
        $systems = config('admin.permission_systems');

        if(!is_array($systems))
        {
            $systems = array();
        }

        return $systems;
    }

    /**
     * Get system name from slug
     * @param string $slug
     * @return array
     */
    public static function getSystemBySlug($slug)
    {
        $systems = self::getPermSystems();

        $name = "";
        if(is_array($systems) && count($systems)>0)
        {
            foreach ($systems as $system => $systemName)
            {
                if($slug == $system)
                {
                    $name = $systemName;
                    break;
                }
            }
        }

        return $name;
    }

    /**
     * Load the permissions of all systems
     * @param bool $exclude
     * @param array $currPermissions
     * @return array
     */
    public static function getAllSystemPermissions($currPermissions=[], $exclude=false)
    {
        $systems = self::getPermSystems();
        $systemPermissions = array();

        if(is_array($systems) && count($systems)>0)
        {
            foreach ($systems as $slug=> $system)
            {
                $systemPermission = array();
                $systemPermission["name"] = $system;
                $systemPermission["slug"] = $slug;
                $systemPermission["modules"] = self::getSingleSystemPermissions($system, $currPermissions, $exclude);

                $systemPermissions[]=$systemPermission;
            }
        }

        return $systemPermissions;
    }

    /**
     * Load the permission of a single system
     * @param string $system
     * @param array $currPermissions
     * @param bool $exclude
     * @return array
     */
    public static function getSingleSystemPermissions($system, $currPermissions=[], $exclude=false)
    {
        if($system == "default")
        {
            $permModules = self::getDefaultSystemPermissions();
        }
        else
        {
            $permModules = array();

            $filePath = Module::getModulePath("admin")."Config/permissions/".$system.".php";
            if(file_exists($filePath))
            {
                $configArray=include($filePath);
                if(isset($configArray["modules"]))
                {
                    $permModules = $configArray["modules"];
                }
            }
        }

        $permModules = self::prepareModules($permModules, $currPermissions, $exclude);

        $permissions = array();
        if(count($permModules)>0)
        {
            $permissions = $permModules;
        }

        return $permissions;
    }

    /**
     * Load the permission for a single module
     * @return array
     */
    public static function getDefaultSystemPermissions()
    {
        //get module permissions first
        $modules = Module::allEnabled();

        $permModules = [];
        if(is_array($modules) && count($modules)>0)
        {
            foreach ($modules as $module)
            {
                $filePath = Module::getModulePath($module)."Config/permissions.php";

                if(file_exists($filePath))
                {
                    $permModule=include($filePath);

                    if($permModule)
                    {
                        $permModules[] = $permModule;
                    }
                }
            }
        }

        //load default config path permissions
        $permissionModule=config('permissions.module');
        $permissionModuleName=config('permissions.name');
        $permissionModuleGroups=config('permissions.groups');

        if($permissionModule != "" && $permissionModuleName !="")
        {
            $module = [
                "slug" => $permissionModule,
                "name" => $permissionModuleName,
                "groups" => $permissionModuleGroups["groups"]
            ];

            $permModules[] = $module;
        }

        return $permModules;
    }

    /**
     * @param array $modules
     * @param array $currModules
     * @param bool $exclude
     * @return array
     */
    public static function prepareModules($modules=array(), $currModules=[], $exclude=false)
    {
        $prepared = [];

        if(is_array($modules) && count($modules)>0)
        {
            //prepare current modules slug wise
            $slugModules = [];
            if(count($currModules)>0)
            {
                foreach ($currModules as $key => $slugModule)
                {
                    $slugModules[$slugModule["module_slug"]]=$slugModule;
                }
            }

            foreach ($modules as $module)
            {
                if(isset($module["slug"]) && isset($module["name"]) && isset($module["groups"]))
                {
                    $slug = $module["slug"];
                    $groups = $module["groups"];

                    $currGroups = [];
                    if(isset($slugModules[$slug]))
                    {
                        $module = $slugModules[$slug];
                        $currGroups = $module["groups"];
                    }
                    else
                    {
                        $module["module_id"] = "";
                    }

                    $groups = self::prepareGroups($groups, $currGroups, $exclude);
                    if(count($groups)>0)
                    {
                        $module["groups"] = $groups;
                        $prepared[]=$module;
                    }
                }
            }
        }

        return $prepared;
    }

    /**
     * @param array $groups
     * @param array $currGroups
     * @param bool $exclude
     * @return array
     */
    public static function prepareGroups($groups=array(), $currGroups=[], $exclude=false)
    {
        $prepared = [];

        if(is_array($groups) && count($groups)>0)
        {
            //prepare current groups slug wise
            $slugGroups = [];
            if(count($currGroups)>0)
            {
                foreach ($currGroups as $key => $slugGroup)
                {
                    $slugGroups[$slugGroup["slug"]]=$slugGroup;
                }
            }

            foreach ($groups as $group)
            {
                if(isset($group["slug"]) && isset($group["name"]) && isset($group["permissions"]))
                {
                    $slug = $group["slug"];
                    $permissions= $group["permissions"];

                    $currPerms = [];
                    if(isset($slugGroups[$slug]))
                    {
                        $group = $slugGroups[$slug];
                        $currPerms = $group["permissions"];
                    }
                    else
                    {
                        $group["group_id"] = "";
                    }

                    $permissions = self::preparePermissions($permissions, $currPerms, $exclude);

                    if(is_array($permissions) && count($permissions)>0)
                    {
                        $group["permissions"]=$permissions;

                        $prepared[]=$group;
                    }
                }
            }
        }

        return $prepared;
    }

    /**
     * @param array $permissions
     * @param array $currPerms
     * @param bool $exclude
     * @return array
     */
    public static function preparePermissions($permissions=array(), $currPerms=[], $exclude=false)
    {
        $prepared = [];
        if(is_array($permissions) && count($permissions)>0)
        {
            //get existing hashes to exclude from the result
            $hashPerms = [];
            if(count($currPerms)>0)
            {
                foreach ($currPerms as $currPerm)
                {
                    $hashPerms[$currPerm["hash"]]=$currPerm;
                }
            }

            foreach ($permissions as $perm)
            {
                if(isset($perm["action"]) && isset($perm["name"]))
                {
                    //remove trailing slashes
                    $del = "/";
                    $perm["action"] = rtrim($perm["action"], $del);
                    $hash = self::getPermissionHash($perm["action"]);

                    if(isset($hashPerms[$hash]))
                    {
                        if(!$exclude)
                        {
                            $perm = $hashPerms[$hash];
                            $prepared[]=$perm;
                        }
                    }
                    else
                    {
                        $perm["perm_id"] = "";
                        $perm["hash"] = self::getPermissionHash($perm["action"]);
                        $prepared[]=$perm;
                    }
                }
            }
        }

        return $prepared;
    }

    /**
     * Get submitted form data of permissions for all system permissions
     * @return array
     */
    public static function getPermissionFormData()
    {
        $formData = array();

        $request = request();
        $permissions = $request->post("permissions");
        $permissions = json_decode($permissions, true);

        if(is_array($permissions) && count($permissions)>0)
        {
            foreach($permissions as $system => $perms)
            {
                $formData[$system]=$perms;
            }
        }

        return $formData;
    }

    /**
     * This function is for load the permission for the default controller path
     * @return array
     */
    public static function loadDefaultPermissions()
    {
        $permissions = array();

        $permissionModuleGroups=config('permissions.groups');

        if(is_array($permissionModuleGroups))
        {
            if(count($permissionModuleGroups["groups"])>0)
            {
                $permissions = self::extractPermissions($permissionModuleGroups);
            }
        }

        return $permissions;
    }

    /**
     * This function is for load the permission for a single module
     * @param string $module
     * @return mixed
     */
    public static function loadSingleModulePermissions($module)
    {
        $filePath = Module::getModulePath($module)."Config/permissions.php";

        $permissionGroups = array();
        if(file_exists($filePath))
        {
            $configArray=include($filePath);

            if(isset($configArray["groups"]))
            {
                if(is_array($configArray["groups"]) && count($configArray["groups"])>0)
                {
                    $permissionGroups = $configArray["groups"];
                }
            }
        }

        return self::extractPermissions($permissionGroups);
    }

    /**
     * Extract permissions for the required format
     * @param array $permissionGroups
     * @return array
     */
    public static function extractPermissions($permissionGroups)
    {
        $permissions = array();

        if(is_array($permissionGroups) && count($permissionGroups)>0)
        {
            foreach($permissionGroups as $group => $pG)
            {
                if(isset($pG["permissions"]) && is_array($pG["permissions"]) && count($pG["permissions"])>0)
                {
                    $permissionActions = [];

                    foreach($pG["permissions"] as $permission)
                    {
                        //trim trailing slashes;
                        $del = "/";
                        $action = rtrim($permission["action"], $del);

                        $permissionActions[]=$action;
                    }

                    $permissions = array_merge($permissionActions, $permissions);
                }
            }
        }

        return $permissions;
    }

    /**
     * Get currently logged in user's permissions list
     * @param int $adminId
     * @param int $adminRoleId
     * @param string $systemId
     * @return array
     */
    public static function getPermissions($adminId, $adminRoleId, $systemId="")
    {
        if($systemId == "")
        {
            $system = config('admin.system');
            $adminPermRepo = new AdminPermissionSystemRepository();
            $systemId = $adminPermRepo->getSystemId($system);
        }

        $adminPermissions = self::getAdminPermissions($adminId, $systemId);
        $adminRolePermissions = AdminRoleRepository::getPermissionData($adminRoleId, $systemId);

        $validPermissions = array_merge($adminPermissions["invoked"], array_diff($adminRolePermissions, $adminPermissions["invoked"]));

        return array_diff($validPermissions, $adminPermissions["revoked"]);
    }

    /**
     * Get currently logged in user's permissions list
     * @param int $systemId
     * @param int $adminId
     * @return array
     */
    public static function getAdminPermissions($adminId, $systemId)
    {
        $adminPermissions = AdminRepository::getPermissionData($adminId, $systemId);

        $data = array();
        $data["invoked"] = [];
        $data["revoked"] = [];
        if(is_array($adminPermissions) && count($adminPermissions)>0)
        {
            foreach ($adminPermissions as $permission)
            {
                if($permission["inv_rev_status"] == "1")
                {
                    $data["invoked"][] = $permission["system_perm_id"];
                }
                else
                {
                    $data["revoked"][] = $permission["system_perm_id"];
                }
            }
        }

        return $data;
    }

    /**
     * Check if have permissions for set of URLs/URIs and then return prepared urls array according to the permissions
     * @param array $urls
     * @return array
     */
    public static function validateUrls($urls=array())
    {
        $defaultAdmin = request()->session()->get("default_admin");

        if($defaultAdmin)
        {
            if(is_array($urls) && count($urls)>0)
            {
                $slash = "/";
                foreach ($urls as $key => $url)
                {
                    $urls[$key]= rtrim($url, $slash).$slash;
                }
            }

            return $urls;
        }
        else
        {
            if(is_array($urls) && count($urls)>0)
            {
                $permValidate = new PermissionValidate();
                $permRepo = new AdminSystemPermissionRepository();

                $baseUrl = URL::to('/');

                $hashes = [];
                $preparedUrls = [];
                foreach ($urls as $key => $url)
                {
                    $routeUrl = str_replace($baseUrl, "", $url);

                    $route = $permValidate->getRouteUri($routeUrl);
                    $module = $permValidate->getModuleFromUri($route);
                    $hash = $permRepo->generatePermissionHash($route);

                    $hashes[]=$hash;

                    $slot = [];
                    $slot["url"]=$url;
                    $slot["route"]=$route;
                    $slot["module"]=$module;
                    $slot["hash"]=$hash;

                    $preparedUrls[$key]=$slot;
                }

                //get permission ids for route hashes
                $permIds = $permRepo->getPermissionFromHashes($hashes);

                $urls = array();
                foreach ($preparedUrls as $key => $preparedUrl)
                {
                    $url = $preparedUrl["url"];
                    $route = $preparedUrl["route"];
                    $module = $preparedUrl["module"];
                    $hash = $preparedUrl["hash"];

                    $permId = false;
                    if(isset($permIds[$hash]))
                    {
                        $permId = $permIds[$hash];
                    }

                    if($permValidate->checkHavePermission($module, $route, $permId))
                    {
                        //set requested url since have permission
                        $urls[$key]=$url;
                    }
                    else
                    {
                        //set url as false since have no permission
                        $urls[$key]=false;
                    }
                }
            }

            return $urls;
        }
    }

    /**
     * Check permissions if this user have permission to requested route
     * @param string $url
     * @return bool
     */
    public static function haveUrlPermission($url)
    {
        $defaultAdmin = request()->session()->get("default_admin");

        if($defaultAdmin)
        {
            return true;
        }
        else
        {
            $baseUrl = URL::to('/');

            $routeUrl = str_replace($baseUrl, "", $url);

            $permValidate = new PermissionValidate();
            $permRepo = new AdminSystemPermissionRepository();

            $route = $permValidate->getRouteUri($routeUrl);
            $module = $permValidate->getModuleFromUri($route);
            $permission = $permRepo->getPermissionFromAction($route);

            $permId = false;
            if(isset($permission["system_perm_id"]))
            {
                $permId = $permission["system_perm_id"];
            }

            return $permValidate->checkHavePermission($module, $route, $permId);
        }
    }

    /**
     * Check permissions if this user have permission to requested action
     * @param string $module
     * @param string $action
     * @return bool
     */
    public static function haveActionPermission($module, $action)
    {
        $defaultAdmin = request()->session()->get("default_admin");

        if($defaultAdmin)
        {
            return true;
        }
        else
        {
            $permValidate = new PermissionValidate();
            $permRepo = new AdminSystemPermissionRepository();
            $permission = $permRepo->getPermissionFromAction($action);

            $permId = false;
            if(isset($permission["system_perm_id"]))
            {
                $permId = $permission["system_perm_id"];
            }

            return $permValidate->checkHavePermission($module, $action, $permId);
        }
    }

    /**
     * Check permissions if this user have permission to current route
     * @return bool
     */
    public static function haveCurrentUrlPermission()
    {
        $permValidate = new PermissionValidate();
        return $permValidate->haveCurrentUrlPermission();
    }

    /**
     * Get permission data from database for a specific route or url path
     * @param string $route
     * @return bool
     */
    public static function getRoutePermission($route)
    {
        $permRepo = new AdminSystemPermissionRepository();
        return $permRepo->getPermissionFromAction($route);
    }

    /**
     * Get permission data from database for a specific route or url path
     * @param string $route
     * @return bool
     */
    public static function getPermissionHash($route)
    {
        $permRepo = new AdminSystemPermissionRepository();
        return $permRepo->generatePermissionHash($route);
    }

    /**
     * Get permission data from database for a specific route or url path
     * @param $repository
     * @return array
     */
    public static function getImportingFormData($repository=false)
    {
        if(!$repository)
        {
            $repository = new BaseRepository();
        }

        $request = request();
        $formModules = $request->post("modules");

        $permFormData = [];
        if(is_array($formModules) && count($formModules)>0)
        {
            $permModules = [];
            foreach ($formModules as $modKey)
            {
                $formGroups = $request->post($modKey."_groups");
                if(is_array($formGroups) && count($formGroups)>0)
                {
                    $permGroups = [];
                    foreach ($formGroups as $groupKey)
                    {
                        $formPerms = $request->post($modKey."_".$groupKey."_permissions");
                        if(is_array($formPerms) && count($formPerms)>0)
                        {
                            $perms = [];
                            foreach ($formPerms as $permKey)
                            {
                                $checked = $request->post($modKey . "_" . $groupKey . "_" . $permKey."_checked");

                                if(isset($checked) && $checked=="1")
                                {
                                    $permission = self::getImportingPermData($repository, $modKey, $groupKey, $permKey);
                                    $perms[]=$permission;
                                }
                            }

                            if(count($perms)>0)
                            {
                                $group = self::getImportingGroupData($repository, $modKey, $groupKey);
                                $group["perms"] = $perms;

                                $permGroups[]=$group;
                            }
                        }
                    }

                    if(count($permGroups)>0)
                    {
                        $module = self::getImportingModuleData($repository, $modKey);
                        $module["groups"] = $permGroups;

                        $permModules[]=$module;
                    }
                }
            }

            $permFormData = $permModules;
        }

        return $permFormData;
    }

    /**
     * @param $repository
     * @param $modKey
     * @return mixed
     */
    public static function getImportingModuleData(BaseRepository $repository, $modKey)
    {
        $module_name= $modKey."_module_name";
        $module_slug= $modKey."_module_slug";
        $module_id= $modKey."_module_id";

        $postData = Validator::make(request()->all(), [
            $module_name => "required",
            $module_slug => "required",
            $module_id => ""
        ], [], [$module_name => "Module Name", $module_slug => "Module Slug"]);

        if ($postData->fails())
        {
            $response = $repository->getValidationErrors($postData->errors());

            return $repository->handleResponse($response);
        }
        else
        {
            $data = $postData->validated();
        }

        $module = [];
        foreach ($data as $key => $value)
        {
            if($key == $module_name)
            {
                $module["module_name"]=$value;
            }
            else if($key == $module_slug)
            {
                $module["module_slug"]=$value;
            }
            else if($key == $module_id)
            {
                $module["module_id"]=$value;
            }
        }

        return $module;
    }

    /**
     * @param $repository
     * @param $modKey
     * @param $groupKey
     * @return mixed
     */
    public static function getImportingGroupData(BaseRepository $repository, $modKey, $groupKey)
    {
        $group_name= $modKey."_".$groupKey."_group_name";
        $group_slug= $modKey."_".$groupKey."_group_slug";
        $group_id= $modKey."_".$groupKey."_group_id";

        $postData = Validator::make(request()->all(), [
            $group_name => "required",
            $group_slug => "required",
            $group_id => ""
        ], [], [$group_name => "Group Name", $group_slug => "Group Slug"]);

        if ($postData->fails())
        {
            $response = $repository->getValidationErrors($postData->errors());

            return $repository->handleResponse($response);
        }
        else
        {
            $data = $postData->validated();
        }

        $group = [];
        foreach ($data as $key => $value)
        {
            if($key == $group_name)
            {
                $group["group_name"]=$value;
            }
            else if($key == $group_slug)
            {
                $group["group_slug"]=$value;
            }
            else if($key == $group_id)
            {
                $group["group_id"]=$value;
            }
        }

        return $group;
    }

    /**
     * @param $repository
     * @param $modKey
     * @param $groupKey
     * @param $permKey
     * @return mixed
     */
    public static function getImportingPermData(BaseRepository $repository, $modKey, $groupKey, $permKey)
    {
        $permission_title = $modKey."_".$groupKey."_".$permKey."_permission_title";
        $permission_action= $modKey."_".$groupKey."_".$permKey."_permission_action";
        $permission_key= $modKey."_".$groupKey."_".$permKey."_permission_key";

        $postData = Validator::make(request()->all(), [
            $permission_title => "required",
            $permission_action => "required",
            $permission_key => "required"
        ], [], [$permission_title => "Permission Title", $permission_action => "Permission Action", $permission_key => "Permission Hash"]);

        if ($postData->fails())
        {
            $response = $repository->getValidationErrors($postData->errors());

            return $repository->handleResponse($response);
        }
        else
        {
            $data = $postData->validated();
        }

        $permission = [];
        foreach ($data as $key => $value)
        {
            if($key == $permission_title)
            {
                $permission["permission_title"]=$value;
            }
            else if($key == $permission_action)
            {
                $permission["permission_action"]=$value;
            }
            else if($key == $permission_key)
            {
                $permission["permission_key"]=$value;
            }
        }

        return $permission;
    }
}
