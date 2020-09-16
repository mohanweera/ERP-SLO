<?php
namespace Modules\Admin\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Modules\Admin\Entities\AdminPermissionChangeRemark;
use Modules\Admin\Entities\AdminPermissionSystem;

class AdminPermissionChangeRemarkRepository
{
    /**
     * Get admin permission system id using system slug
     * @param string $system
     * @return bool|int
     */
    public static function addRemark()
    {
        $model = new AdminPermissionChangeRemark();
        $permissionSystem = $model::query()->where("system_slug", '=', $system);

        if($permissionSystem)
        {
            $primaryKey = $model->getKeyName();
            return $permissionSystem->$primaryKey;
        }

        return false;
    }
}
