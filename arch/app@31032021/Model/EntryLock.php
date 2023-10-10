<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryLock extends Model
{
	use SoftDeletes;
    protected $fillable = ['month','year','status'];

    
    
}

