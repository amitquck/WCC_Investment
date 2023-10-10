<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyBank extends Model
{
	use SoftDeletes;
    protected $fillable = ['bank_name'];

    
}

