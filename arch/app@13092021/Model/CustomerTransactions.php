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

    public function associatedetail(){
        return $this->belongsTo('App\User','associate_id');
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
        return CustomerReward::where('customer_id',$this->customer_id)->where('reward_type','interest')->where('month', '>=', Carbon::parse($this->deposit_date)->format('m'))->where('year', '>=',Carbon::parse($this->deposit_date)->format('Y'))->first();//->where('month', '>=', Carbon::parse($this->deposit_date)->format('m'))->where('year', '>=',Carbon::parse($this->deposit_date)->format('Y'))
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
