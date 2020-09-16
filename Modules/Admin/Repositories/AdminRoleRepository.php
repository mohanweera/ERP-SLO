<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Modules\Admin\Entities\AdminRolePermission;
use Modules\Admin\Entities\AdminRolePermissionHistory;
use Modules\Admin\Services\Permission;

class AdminRoleRepository extends BaseRepository
{
    public static function updatePermission($adminRoleId)
    {
        $systemsPermissions = Permission::getPermissionFormData();

        $systems = array();
        $invokedPermissions = array();
        $revokedPermissions = array();

        $data = array();
        $data["admin_role_id"] = $adminRoleId;

        if(is_array($systemsPermissions) && count($systemsPermissions)>0)
        {
            foreach ($systemsPermissions as $systemId => $permissions)
            {
                $systems[]=$systemId;

                //get previous permissions
                $currPerms = AdminRolePermission::query()->select("permissions")
                                                         ->where("admin_role_id", "=", $adminRoleId)
                                                         ->where("admin_perm_system_id", "=", $systemId)
                                                         ->first();

                if ($currPerms)
                {
                    $currPerms = $currPerms->toArray();
                }

                if(!is_array($currPerms["permissions"]))
                {
                    $currPerms["permissions"] = [];
                }

                $revokedPermissions[$systemId] = array_values(array_diff($currPerms["permissions"], $permissions));
                $invokedPermissions[$systemId] = array_values(array_diff($permissions, $currPerms["permissions"]));

                $data["admin_perm_system_id"]=$systemId;
                $data["permissions"]=$permissions;

                AdminRolePermission::updateOrCreate(["admin_role_id" => $adminRoleId, "admin_perm_system_id" => $systemId], $data);
            }
        }

        if(count($systems)>0)
        {
            $data = array();
            $data["admin_role_id"] = $adminRoleId;
            $data["remarks"] = request()->post("remarks");

            foreach ($systems as $systemId)
            {
                $invPerms = array();
                if(isset($invokedPermissions[$systemId]))
                {
                    $invPerms = $invokedPermissions[$systemId];
                }
                $revPerms = array();
                if(isset($revokedPermissions[$systemId]))
                {
                    $revPerms = $revokedPermissions[$systemId];
                }

                if(count($invPerms)>0 || count($revPerms)>0)
                {
                    $data["admin_perm_system_id"] = $systemId;
                    $data["invoked_permissions"] = $invPerms;
                    $data["revoked_permissions"] = $revPerms;

                    AdminRolePermissionHistory::create($data);
                }
            }
        }
    }

    public static function getPermissionData($adminRoleId, $systemId)
    {
        $result = AdminRolePermission::query()->select("permissions")
                                              ->where(["admin_role_id" => $adminRoleId, "admin_perm_system_id" => $systemId])->first();

        $data = array();
        if($result)
        {
            $data = $result["permissions"];
        }

        return $data;
    }

    public static function getPermissionDataJson($adminRoleId, $systemId)
    {
        $data = self::getPermissionData($adminRoleId, $systemId);

        return json_encode($data);
    }

    public static function getAllSystemPermissionData($admin_role_id)
    {
        $results = AdminRolePermission::query()->select("admin_perm_system_id", "permissions")->where(["admin_role_id" => $admin_role_id])->get()->toArray();

        $data = array();
        if($results)
        {
            if(is_array($results) && count($results)>0)
            {
                foreach ($results as $result)
                {
                    $data[$result["admin_perm_system_id"]]=$result["permissions"];
                }
            }
        }

        return $data;
    }

    public static function getAllSystemPermissionDataJson($adminRoleId)
    {
        $data = self::getAllSystemPermissionData($adminRoleId);

        return json_encode($data);
    }
}
