<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class studentUp extends Model
{
    protected $fillable = [];
    protected $table = 'student_uploads';
    protected $primaryKey = 'std_upload_id';
}
