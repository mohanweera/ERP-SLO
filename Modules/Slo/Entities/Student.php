<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    protected $fillable = [];
    protected $table = 'students';
    protected $primaryKey = 'student_id';
}
