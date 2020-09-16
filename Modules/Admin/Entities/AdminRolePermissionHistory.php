<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Observers\AdminActivityObserver;

class AdminRolePermissionHistory extends Model
{
    protected $fillable = ["admin_role_id", "admin_perm_system_id", "remarks", "invoked_permissions", "revoked_permissions", "created_by"];

    protected $with = [];

    public $timestamps = ["created_at"];
    const UPDATED_AT = null;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'invoked_permissions' => 'array',
        'revoked_permissions' => 'array',
    ];

    public function permissionSystem()
    {
        return $this->belongsTo(AdminPermissionSystem::class, "admin_perm_system_id", "admin_perm_system_id");
    }

    public static function boot()
    {
        parent::boot();

        //Use this code block to track activities regarding this model
        //Use this code block in every model you need to record
        //This will record created_by, updated_by, deleted_by admins too, if you have set those fields in your model
        self::observe(AdminActivityObserver::class);
    }
}
