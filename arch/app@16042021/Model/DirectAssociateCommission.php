<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectAssociateCommission extends Model
{
	use SoftDeletes;
    protected $fillable = ['associate_id','customer_id','total_investment','total_withdraw'];
    
   
    public function associate(){
		return $this->belongsTo('App\User','associate_id');
	}  

	public function topAssociate($associateID)
    {
        $customers = AssociateCommissionPercentage::where('associate_id', $associateID)->where('no_of_introducer', 1)->get();
        $totalcr = $totaldr = 0;
        foreach($customers as $key => $customer){
            $totalcr += CustomerTransactions::where('customer_id', $customer->customer_id)->where('transaction_type', 'deposit')->sum('amount');
            $totaldr += CustomerTransactions::where('customer_id', $customer->customer_id)->where('transaction_type', 'withdraw')->sum('amount');
        }
        return $totalcr - $totaldr;
    }
}
