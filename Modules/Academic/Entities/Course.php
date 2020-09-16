<?php

namespace Modules\Academic\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Slo\Entities\IdRange;
use Modules\Slo\Entities\Student;

class Course extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'course_id';

    public function idRanges()
    {
        return $this->hasMany(IdRange::class,'id','id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student', 'course_id', 'student_id');
    }
}
