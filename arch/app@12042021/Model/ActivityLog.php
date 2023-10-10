<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
{
	use SoftDeletes;
    protected $fillable = ['created_by','user_id','action_type','statement'];

    
    public function user()
    {
    	return $this->belongsTo('App\User','created_by');
    }
    public function client()
    {
    	return $this->belongsTo('App\User','user_id');
    }
}

