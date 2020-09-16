<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $fillable = [];
    protected $table = 'departments';
    protected $primaryKey = 'dept_id';
}
