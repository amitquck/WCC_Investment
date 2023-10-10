<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class DirectAssociateCommission extends Model
{
	use SoftDeletes;
    protected $fillable = ['associate_id','customer_id','total_investment','total_withdraw'];
    
   
    public function associate(){
		return $this->belongsTo('App\User','associate_id');
	}  

	public function topAssociate($associateID)
    {
        $customers = AssociateCommissionPercentage::where('associate_id', $associateID)->where('no_of_introducer', 1)->where('status',1)->get();
        $totalcr = $totaldr = 0;
        foreach($customers as $key => $customer){
            $totalcr += CustomerTransactions::where('customer_id', $customer->customer_id)->where('transaction_type','!=', 'withdraw')->sum('amount');
            $totaldr += CustomerTransactions::where('customer_id', $customer->customer_id)->where('transaction_type', 'withdraw')->sum('amount');
        }
        return $totalcr - $totaldr;

        // $result = CustomerTransactions::select('customer_transactions.*',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))->whereIn('customer_id', function($q) use ($associateID){
        //         return $q->select('customer_id')->from('direct_associate_commissions')->whereIn('associate_id',function($q) use ($associateID){
        //                 return $q->select('associate_id')->from('associate_commission_percentages')->where('associate_id',$associateID)->where('no_of_introducer',1)->where('status',1);
        //         });
        // })->orderByDesc('cust_balance')->get();
        // // dd($result);
        // return $result;
    }
}
