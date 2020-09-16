<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class batch extends Model
{
    protected $fillable = [];
    protected $table = 'batches';
    protected $primaryKey = 'batch_id';
}
