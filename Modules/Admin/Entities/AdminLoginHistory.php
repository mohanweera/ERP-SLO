<?php

namespace Modules\Admin\Entities;

use App\Country;
use Illuminate\Database\Eloquent\Model;

class AdminLoginHistory extends Model
{
    protected $fillable = [
        "admin_id", "login_ip", "country_id", "city", "login_failed_reason", "online_status", "last_activity_at", "sign_in_at", "sign_out_type", "sign_out_at"
    ];

    protected $with = [];

    protected $primaryKey = "admin_login_history_id";

    public $timestamps = false;

    protected $appends = ["id"];

    protected $casts = [
        'last_activity_at' => 'datetime:Y-m-d H:i:s',
        'sign_in_at' => 'datetime:Y-m-d H:i:s',
        'sign_out_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function setLastActivityAtAttribute($value)
    {
        $this->attributes['last_activity_at'] = date("Y-m-d H:i:s", time());
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, "admin_id", "admin_id");
    }

    public function country()
    {
        return $this->belongsTo(Country::class, "country_id", "country_id");
    }
}
