<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionAction extends Model
{
	use SoftDeletes;
    protected $fillable = ['permission_id','action_name'];

    
    
}

