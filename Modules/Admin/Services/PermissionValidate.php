<?php

namespace Modules\Admin\Services;

class PermissionValidate
{
    private $adminPermissions = null;
    private $defaultPermissions = null;
    private $modulePermissions = [];

    /**
    * This function is for load the permission for the default controller path
    * @return array
    */
    public function loadDefaultPermissions()
    {
        if($this->defaultPermissions === null)
        {
            $this->defaultPermissions = Permission::loadDefaultPermissions();
        }

        return $this->defaultPermissions;
    }

    /**
     * This function is for load the permission for a single module
     * @param string $module
     * @return mixed
     */
    public function loadSingleModulePermissions($module)
    {
        if(!isset($this->modulePermissions[$module]))
        {
            $this->modulePermissions[$module] = Permission::loadSingleModulePermissions($module);
        }

        return $this->modulePermissions[$module];;
    }

    /**
     * Get currently logged in user's permissions list
     * @return array
     */
    public function getCurrentAdminPermissions()
    {
        if($this->adminPermissions === null)
        {
            //get admin's permission list from session
            $adminPermissions = request()->session()->get("permissions");

            if(!is_array($adminPermissions))
            {
                $adminPermissions = array();
            }

            $this->adminPermissions = $adminPermissions;
        }

        return $this->adminPermissions;
    }

    /**
     * Check permissions if this user has access to a selected area
     * @param boolean $module module name
     * @param string $urlPath URL path
     * @param int $permId
     * @return boolean
     */
    public function checkHavePermission($module, $urlPath, $permId)
    {
        //get required permission list
        if(!empty($module))
        {
            //load this module's permissions
            $requiredPermissions = $this->loadSingleModulePermissions($module);
        }
        else
        {
            //load default permissions
            $requiredPermissions = $this->loadDefaultPermissions();
        }

        //get admin's permission list
        $adminPermissions = $this->getCurrentAdminPermissions();

        //trim trailing slashes;
        $del = "/";
        $urlPath = rtrim($urlPath, $del);

        //check if this url path has been set in required permissions list
        if(in_array($urlPath, $requiredPermissions))
        {
            if($permId)
            {
                //check if this permission has been set in user's permission
                if(in_array($permId, $adminPermissions))
                {
                    $have_permission=true;
                }
                else
                {
                    //this user has no permission to perform this operation
                    $have_permission=false;
                }
            }
            else
            {
                //this user has no permission to perform this operation
                $have_permission=false;
            }
        }
        else
        {
            //it doesn't need permission to perform this operation
            $have_permission=true;
        }

        return $have_permission;
    }

    /**
     * Check permissions if this user have permission to current route
     * @return bool
     */
    public function haveCurrentUrlPermission()
    {
        $defaultAdmin = request()->session()->get("default_admin");

        $urlPath = request()->getPathInfo();
        $urlPath = $this->getRouteUri($urlPath);
        $permission = Permission::getRoutePermission($urlPath);

        if($defaultAdmin)
        {
            $this->setCurrentActivity($permission);
            return true;
        }
        else
        {
            $module = $this->getModuleFromUri($urlPath);

            $permId = false;
            if($permission)
            {
                $permId = $permission["system_perm_id"];
            }

            $this->setCurrentActivity($permission);

            return $this->checkHavePermission($module, $urlPath, $permId);
        }
    }

    /**
     * Set as current activity of the user in the session
     * @param $permission
     * @return void
     */
    private function setCurrentActivity($permission)
    {
        $permission_title = "";
        if($permission)
        {
            $permission_title = $permission["permission_title"];
        }

        request()->session()->put("currentActivity", $permission_title);
    }

    /**
     * Get correct route URI which matched with the system routes
     * @param string $urlPath
     * @return string
     */
    public function getRouteUri($urlPath)
    {
        //extract URL
        $route = collect(\Route::getRoutes())->first(function($route) use($urlPath){

            $method = request()->method();
            return $route->matches(request()->create($urlPath, $method));
        });

        $urlPath = $route->uri;

        //check for url params
        $paramStart = strpos($urlPath, "{");
        if($paramStart)
        {
            //get rest of the URL without URL params
            $urlPath = substr($urlPath, 0, $paramStart);
        }

        $slash = "/";
        $urlPath = $slash.ltrim($urlPath, $slash);

        return $urlPath;
    }

    /**
     * Get module name if current controller belongs to a module
     * @param string $url
     * @return string
     */
    public function getModuleFromUri($url)
    {
        $controller = $this->getControllerFromRoute($url);

        $del = '\\';
        $controllerExp = @explode($del, $controller);

        $module = "";
        if($controllerExp[0] == "Modules")
        {
            $module = strtolower($controllerExp[1]);
        }

        return $module;
    }

    /**
     * Get controller of the specific URI
     * @param string $urlPath
     * @return string|null
     */
    public function getControllerFromRoute($urlPath)
    {
        $route = collect(\Route::getRoutes())->first(function($route) use($urlPath){

            return $route->matches(request()->create($urlPath));
        });

        if($route)
        {
            $del = "@";
            $controller = $route->action["controller"];
            $controller = @explode($del, $controller);
            $controller = $controller[0];

            return $controller;
        }
        else
        {
            return null;
        }
    }
}
