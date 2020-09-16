<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Observers\AdminActivityObserver;

class AdminRolePermission extends Model
{
    protected $fillable = [
        "admin_role_id", "admin_perm_system_id", "permissions"
    ];

    protected $with = [];

    protected $casts = ["permissions" => "array"];

    public $timestamps = false;
}
