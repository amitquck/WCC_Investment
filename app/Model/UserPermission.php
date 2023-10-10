<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermission extends Model
{
	use SoftDeletes;
    protected $fillable = ['permission_id','action_name','user_id','status'];

    
    
}

