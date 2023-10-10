<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;
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

    public function customerinvestment()
    {
        return $this->belongsTo('App\Model\CustomerInvestment','respective_table_id');
    }

    public function getIsMonthPayoutGenerateAttribute()
    {
        return CustomerReward::where('customer_id',$this->customer_id)->where('reward_type','interest')->first();//->where('month', '>=', Carbon::parse($this->deposit_date)->format('m'))->where('year', '>=',Carbon::parse($this->deposit_date)->format('Y'))
    }

    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 

    public function getState(){
        return $this->belongsTo('App\Model\State','state_id');
    } 

    public function getCity(){
        return $this->belongsTo('App\Model\City','city_id');
    }

    public function getCustomerWiseBusinessAttribute()
    {
        // $txn_deposit = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','cr')->where('transaction_type','deposit')->sum('amount');
        // $txn_withdraw = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','dr')->where('transaction_type','withdraw')->sum('amount');
        // $cust_reward = CustomerReward::where('customer_id',$this->customer_id)->where('reward_type','interest')->sum('amount');->where('customer_id',$this->customer_id)
        // $asso_reward = AssociateReward::where('customer_id',$this->customer_id)->where('reward_type','commission')->sum('amount');

        // return $txn_deposit - $txn_withdraw;
 
        // $cust_balance = CustomerTransactions::select('*',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))->groupBy('customer_id')->orderByDesc('cust_balance')->get();
        // // dd($cust_balance);
        // return $cust_balance;
    }

    public function customer_current_balance($customerId,$fromDate=null,$toDate=null){
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
    
    public function getCustomerWiseThisMonthBusinessAttribute()
    {
        // $txn_deposit = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','cr')->where('transaction_type','deposit')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');

        // $txn_withdraw = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','dr')->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');

      

        // return $txn_deposit - $txn_withdraw;
    }
    
    public function getCustomerWiseLastMonthBusinessAttribute()
    {
        // $txn_deposit = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','cr')->where('transaction_type','deposit')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');

        // $txn_withdraw = CustomerTransactions::where('customer_id',$this->customer_id)->where('cr_dr','dr')->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');

        // return $txn_deposit - $txn_withdraw;
    }

    public function topInvesters()
    {
        $totalcr = $this->where('customer_id', $this->customer_id)->where('transaction_type','deposit')->sum('amount');
        $totaldr = $this->where('customer_id', $this->customer_id)->where('transaction_type','withdraw')->sum('amount');
        
        return $totalcr-$totaldr;
    }


    public function customer_first_deposit_date()
    {
        return $this->where('customer_id', $this->customer_id)->where('transaction_type','deposit')->orderBy('deposit_date')->first();
    }


    public function getAssociate(){
        return $this->belongsTo('App\Model\AssociateCommissionPercentage','customer_id');
    }
}
