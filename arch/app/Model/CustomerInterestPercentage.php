<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerInterestPercentage extends Model
{
	 use SoftDeletes;
    protected $fillable = ['customer_id', 'start_date',	'end_date',	'interest_percent','active_status' ];

    
}
