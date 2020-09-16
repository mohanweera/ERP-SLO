<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Observers\AdminActivityObserver;

class SystemAccessAdminIpRestriction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "admin_id", "ip_location", "ip_address", "ip_address_key", "remarks", "access_status", "created_by", "updated_by", "deleted_by"
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $with = [];

    public function admin()
    {
        return $this->belongsTo(Admin::class, "admin_id", "admin_id");
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
