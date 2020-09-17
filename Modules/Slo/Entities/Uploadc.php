<?php

namespace Modules\Slo\Entities;

use Illuminate\Database\Eloquent\Model;

class uploadc extends Model
{
    protected $fillable = [];
    protected $table = 'upload_categories';
    protected $primaryKey = 'upload_cat_id';
}
