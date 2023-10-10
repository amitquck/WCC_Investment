<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class CustomerTransactions extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','amount','payment_type','cr_dr','remarks','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','created_by','respective_table_id','respective_table_name'];

    protected $appends = ['sum_monthly_interest'];

    public function user()
    {
    	return $this->belongsTo('App\User','customer_id');
    }

    public function customers(){
    	return $this->belongsTo('App\User','customer_id');
    }

    public function customerdetail(){
        return $this->belongsTo('App\Model\CustomerDetail','customer_id');
    }

    
    public function getSumMonthlyInterestAttribute()
    {
        // dump($this->customer_id.'-'.Carbon::parse($this->deposit_date)->format('m').'-'.Carbon::parse($this->deposit_date)->format('Y'));
        // dump(CustomerReward::where('customer_id',$this->customer_id)->where('month',Carbon::parse($this->deposit_date)->format('m'))->where('year',Carbon::parse($this->deposit_date)->format('Y'))->where('reward_type','interest')->toSql());
        return CustomerReward::where('customer_id',$this->customer_id)->where('month',Carbon::parse($this->deposit_date)->format('m'))->where('year',Carbon::parse($this->deposit_date)->format('Y'))->where('reward_type','interest')->sum('amount');
    }	

    public function bankname()
    {
        return $this->belongsTo('App\Model\CompanyBank','bank_id');
    }

    public function getIsMonthPayoutGenerateAttribute()
    {
        return CustomerReward::where('customer_id',$this->customer_id)->where('reward_type','interest')->first();//->where('month', '>=', Carbon::parse($this->deposit_date)->format('m'))->where('year', '>=',Carbon::parse($this->deposit_date)->format('Y'))
    }

    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 


    public function getCustomerWiseBusinessAttribute()
    {
        $txn = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','cr')->where('transaction_type','deposit')->sum('amount');
        $cust_reward = CustomerReward::where('customer_id',$this->customer_id)->where('reward_type','interest')->sum('amount');
        $asso_reward = AssociateReward::where('customer_id',$this->customer_id)->where('reward_type','commission')->sum('amount');

        return $txn - ($cust_reward + $asso_reward);
    }

    public function customer_current_balance($customerId,$fromDate,$toDate){
        if($fromDate != Null && $toDate != Null){
            $totalcr = $this->where('cr_dr','cr')->where('customer_id',$customerId)->whereBetween('deposit_date', [$fromDate, $toDate])->sum('amount');
            $totaldr = $this->where('cr_dr','dr')->where('customer_id',$customerId)->whereBetween('deposit_date', [$fromDate, $toDate])->sum('amount');
            return $totalcr-$totaldr;
        }
        $totalcr = $this->where('cr_dr','cr')->where('customer_id',$customerId)->sum('amount');
        $totaldr = $this->where('cr_dr','dr')->where('customer_id',$customerId)->sum('amount');
        return $totalcr-$totaldr;
    }


    // public function cr_sum($customerId)
    // {
    //     return CustomerTransactions::where('customer_id',$customerId)->where('transaction_type','deposit')->sum('amount');
    // }

    // public function dr_sum($customerId)
    // {
    //     return CustomerTransactions::where('customer_id',$customerId)->where('transaction_type','withdraw')->sum('amount');
    // }
    
    
}
