<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Observers\AdminActivityObserver;

class AdminPermissionModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_perm_system_id", "module_name", "module_slug", "module_status", "remarks", "created_by", "updated_by", "deleted_by"
    ];

    protected $with = [];

    protected $primaryKey = "admin_perm_module_id";

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
        return $this->module_name;
    }

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
