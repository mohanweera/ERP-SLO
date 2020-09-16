<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermission;
use Modules\Admin\Entities\AdminPermissionHistory;
use Modules\Admin\Services\Permission;

class AdminRepository extends BaseRepository
{
    public function generatePermissionHash($permissionAction)
    {
        return md5($permissionAction);
    }

    /**
     * Return column UI for the datatable of the model
     * @return Factory|View
     */
    public function display_admin_role_as()
    {
        $url = URL::to("/admin/admin_role/");
        return view("admin::admin.datatable.admin_role_ui", compact('url'));
    }

    /**
     * Update admin permissions
     * @param int $adminId
     * @param int $systemId
     * @param int $invRevStatus
     */
    public static function updatePermission($adminId, $adminRoleId, $systemId, $invRevStatus)
    {
        $systemsPermissions = Permission::getPermissionFormData();
        $adminRolePermissions = AdminRoleRepository::getPermissionData($adminRoleId, $systemId);

        $data = array();
        $data["admin_id"] = $adminId;
        $data["inv_rev_status"] = $invRevStatus;

        $valid_from = request()->post("valid_from");
        $valid_till = request()->post("valid_till");

        if($valid_from != "" && $valid_till != "")
        {
            $data["valid_from"] = $valid_from;
            $data["valid_till"] = $valid_till;
        }

        $systems = array();
        $updatedPermissions = array();
        $resetPermissions = array();

        $permChangeDetected = [];

        if(is_array($systemsPermissions) && count($systemsPermissions)>0)
        {
            foreach ($systemsPermissions as $sysId => $permissions)
            {
                $permChangeDetected[$systemId] = false;
                if($systemId == $sysId)
                {
                    $data["admin_perm_system_id"]=$systemId;
                    $systems[]=$systemId;

                    if(is_array($permissions) && count($permissions)>0)
                    {
                        foreach ($permissions as $key => $permission)
                        {
                            $permId= $permission["perm_id"];
                            $prevStatus= $permission["prev_tatus"];
                            $newStatus = $permission["new_status"];

                            if($prevStatus != $newStatus)
                            {
                                $permChangeDetected[$systemId] = true;
                                if($newStatus == "1" || $newStatus == "0")
                                {
                                    $updatedPermissions[$systemId][]=$permId;
                                    $data["system_perm_id"]=$permId;
                                    AdminPermission::updateOrCreate(["admin_id" => $adminId, "admin_perm_system_id" => $systemId, "system_perm_id" => $permId, "inv_rev_status" => $invRevStatus], $data);
                                }
                                else
                                {
                                    if($newStatus == "1")
                                    {
                                        if(in_array($permId, $adminRolePermissions))
                                        {
                                            //this is not permission grant reverse
                                            //this means it's inherited from admin role
                                        }
                                        else
                                        {
                                            $resetPermissions[$systemId][]=$permId;
                                        }
                                    }
                                    else
                                    {
                                        if(!in_array($permId, $adminRolePermissions))
                                        {
                                            //this is not permission revoke reverse
                                            //this means it's inherited from admin role
                                        }
                                        else
                                        {
                                            $resetPermissions[$systemId][]=$permId;
                                        }
                                    }

                                    AdminPermission::query()->where("admin_id", "=", $adminId)
                                        ->where("admin_perm_system_id", "=", $systemId)
                                        ->where("system_perm_id", "=", $permId)->delete();
                                }
                            }
                        }
                    }
                }
            }
        }

        if($invRevStatus == "1")
        {
            $invokedPermissions = $updatedPermissions;
            $revokedPermissions = $resetPermissions;
        }
        else
        {
            $invokedPermissions = $resetPermissions;
            $revokedPermissions = $updatedPermissions;
        }

        if(is_array($systems) && count($systems)>0)
        {
            $data = array();
            $data["admin_id"] = $adminId;
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

                if(count($invPerms)>0 || count($revPerms)>0 || $permChangeDetected[$systemId])
                {
                    $data["admin_perm_system_id"] = $systemId;
                    $data["invoked_permissions"] = $invPerms;
                    $data["revoked_permissions"] = $revPerms;

                    AdminPermissionHistory::create($data);
                }
            }

            if(count($permChangeDetected)>0)
            {
                $response["status"]="success";
                $response["notify"][]="Successfully saved the details.";
            }
            else
            {
                $response["status"]="failed";
                $response["notify"][]="Details saving was failed. No any permission change detected.";
            }
        }
        else
        {
            $response["status"]="failed";
            $response["notify"][]="Details saving was failed. No any permission change detected.";
        }

        return $response;
    }

    /**
     * Get permission data of a specific system
     * @param int $adminId
     * @param int $systemId
     * @return array
     */
    public static function getPermissionData($adminId, $systemId)
    {
        $date = date("Y-m-d", time());
        $results = AdminPermission::query()
            ->select("admin_perm_system_id","system_perm_id", "inv_rev_status")
            ->where(["admin_id" => $adminId, "admin_perm_system_id" => $systemId])
            ->where(function ($query) use($date) {

                $query->where(function ($query) use($date) {

                    $query->where("valid_from", "<=", $date)->where("valid_till", ">=", $date);
                })->orWhere(function ($query) use($date) {

                    $query->where(["valid_from" => null, "valid_till" => null]);
                });
            })->get()->toArray();

        $data = array();
        if($results)
        {
            if(is_array($results) && count($results)>0)
            {
                foreach ($results as $result)
                {
                    $data[] = $result;
                }
            }
        }

        return $data;
    }

    /**
     * Extract permission data of a specific system
     * @param array $permissions
     * @param int $invRevStatus
     * @return array
     */
    public static function getPermissionDataExtract($permissions, $invRevStatus)
    {
        $data = [];
        if($permissions)
        {
            if(is_array($permissions) && count($permissions)>0)
            {
                foreach ($permissions as $permission)
                {
                    if($permission["inv_rev_status"] == $invRevStatus)
                    {
                        $data[]=$permission["system_perm_id"];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get permission data of a specific system as JSON
     * @param int $adminId
     * @param int $systemId
     * @return string
     */
    public static function getPermissionDataJson($adminId, $systemId)
    {
        $data = self::getPermissionData($adminId, $systemId);

        return json_encode($data);
    }

    /**
     * Get permission data of all systems
     * @param int $adminId
     * @return array
     */
    public static function getAllSystemPermissions($adminId)
    {
        $date = date("Y-m-d", time());
        $results = AdminPermission::query()
            ->select("admin_perm_system_id","system_perm_id", "inv_rev_status")
            ->where("admin_id", "=", $adminId)
            ->where("valid_from", "<=", $date)
            ->where("valid_till", ">=", $date)
            ->get()->toArray();

        $data = array();
        if($results)
        {
            $data = array();
            foreach ($results as $result)
            {
                $data[$result["admin_perm_system_id"]][]=$result;
            }
        }

        return $data;
    }

    /**
     * Get permission data of all systems as JSON
     * @param int $adminId
     * @return string
     */
    public static function getAllSystemPermissionsJson($adminId)
    {
        $data = self::getAllSystemPermissions($adminId);

        return json_encode($data);
    }
}
