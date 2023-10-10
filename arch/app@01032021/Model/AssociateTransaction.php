<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssociateTransaction extends Model
{
	 use SoftDeletes;
    protected $fillable = ['associate_id','amount','remarks','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type'];

    
}
