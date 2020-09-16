<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Modules\Admin\Entities\AdminSystemPermission;

class AdminSystemPermissionRepository extends BaseRepository
{
    public function generatePermissionHash($action)
    {
        return md5($action);
    }

    public function getPermissionFromAction($action)
    {
        $hash = $this->generatePermissionHash($action);

        $model = AdminSystemPermission::query()->where("permission_key", "=", $hash)->first();

        if($model)
        {
            return $model;
        }

        return false;
    }

    /**
     * Get permission ids for set of permission hashes
     * @param array $hashes
     * @return array|bool
     */
    public function getPermissionFromHashes($hashes=array())
    {
        $results = AdminSystemPermission::query()->select("permission_key", "system_perm_id")->whereIn("permission_key", $hashes)->get();

        $data = array();
        if($results)
        {
            if(is_array($results) && count($results)>0)
            {
                foreach ($results as $result)
                {
                    $data[$result["permission_key"]]=$result["system_perm_id"];
                }
            }
        }

        return $data;
    }
}
