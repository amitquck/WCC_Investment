<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
	use SoftDeletes;
    protected $fillable = ['slug','name','actions'];

    public function actions()
    {
    	return $this->hasMany('App\PermissionAction','permission_id');
    }
    public function userpermission(){
        return $this->hasMany('App\Model\UserPermission');
    }
    
}

