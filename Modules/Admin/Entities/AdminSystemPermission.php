<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Observers\AdminActivityObserver;

class AdminSystemPermission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_perm_group_id", "permission_title", "permission_action", "permission_key", "permission_status", "disabled_reason", "created_by", "updated_by", "deleted_by"
    ];

    protected $with = [];

    protected $primaryKey = "system_perm_id";

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $appends = ["id", "name"];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function getNameAttribute()
    {
        return $this->permission_title;
    }

    public function permissionGroup()
    {
        return $this->belongsTo(AdminPermissionGroup::class, "admin_perm_group_id", "admin_perm_group_id");
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
