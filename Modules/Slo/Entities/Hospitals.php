<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class Hospitals extends Model
{
    protected $fillable = [];
    protected $table = 'gen_hospitals';
    protected $primaryKey = 'gen_hospital_id';
}
