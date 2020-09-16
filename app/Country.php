<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        "country_name", "country_code", "country_code_alt", "calling_code", "currency_code", "citizenship"
    ];

    protected $primaryKey = "country_id";

    public $timestamps = false;

    protected $appends = ["id", "name"];

    public function getIdAttribute()
    {
        return $this->{$this->primaryKey};
    }

    public function getNameAttribute()
    {
        return $this->country_name;
    }
}
