<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerInvestment extends Model
{
	 use SoftDeletes;
    protected $fillable = ['customer_id','amount','remarks','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','customer_interest_rate'];

    public function customertransactions(){
        return $this->hasMany('App\Model\CustomerTransactions','customer_investment_id');
    }

    public function associatecommissions()
    {
       return $this->hasMany('App\Model\AssociateCommissionPercentage','customer_id');
    }

    public function balance($tillDate)
    {
    	// dd($tillDate);
    	$totalcr = $this->customertransactions->where('cr_dr','cr')->where('deposit_date', '<=', $tillDate)->sum('amount');
    	// if($this->customer_id=16){
    		// dd($totalcr);
    	// }
    	$totaldr = $this->customertransactions->where('cr_dr','dr')->where('deposit_date', '<=', $tillDate)->sum('amount');

    	return $totalcr-$totaldr;
    }

    public function customers(){
    	return $this->belongsTo('App\User','customer_id');
    }
}
