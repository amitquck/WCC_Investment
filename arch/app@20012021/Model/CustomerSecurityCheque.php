<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSecurityCheque extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','cheque_issue_date','cheque_maturity_date','cheque_bank_name','cheque_amount','scan_copy'];

    public function cheque_user()
    {
    	return $this->belongsTo('App\User','customer_id');
    }
}

