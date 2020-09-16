<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Observers\AdminActivityObserver;

class AdminPermission extends Model
{
    protected $fillable = [
        "admin_id", "admin_perm_system_id", "system_perm_id", "inv_rev_status", "valid_from", "valid_till"
    ];

    protected $with = [];

    public $timestamps = false;
}
