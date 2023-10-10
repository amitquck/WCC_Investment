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
        return CustomerReward::where('customer_id',$this->customer_id)->where('month', '>=', Carbon::parse($this->deposit_date)->format('m'))->where('year', '>=',Carbon::parse($this->deposit_date)->format('Y'))->where('reward_type','interest')->first();
    }

    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 


    
    
}
