<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;
use App\Model\CustomerTransactions;
use App\Model\CustomerReward;
use App\Model\AssociateReward;
use DB;

class State extends Model
{
	use SoftDeletes;
	protected $fillable = ['name','country','created_by'];

    public function countrydetail()
    {
    	return $this->belongsTo(Country::class,'country');
    }
    public function customerdetails(){
        // dd($this);
       return $this->hasMany('App\Model\CustomerDetail');
    }

    public function getStateWiseBusinessAttribute()
    {
        // dd($customerId);
        // $txn_deposit = CustomerTransactions::whereIn('customer_id',function($q){
        //     return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        // })->where('transaction_type','deposit')->sum('amount');
        // // dd($txn_deposit->toSql());
        // $txn_withdraw = CustomerTransactions::whereIn('customer_id',function($q){
        //     return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        // })->where('transaction_type','withdraw')->sum('amount');

        // return $txn_deposit - $txn_withdraw;

        // $cust_reward = CustomerReward::whereIn('customer_id',function($q){
        //     return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        // })->where('reward_type','interest')->sum('amount');
        // $asso_reward = AssociateReward::whereIn('customer_id',function($q){
        //     return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        // })->where('reward_type','commission')->sum('amount');
    }

    // public function state_wise_business(){
    //     $cust_txn = CustomerTransactions::select('*',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))->whereIn('customer_id',function($q){
    //                 return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
    //             })->orderByDesc('cust_balance')->get();
    //     return $cust_txn;
    // }


    public function getStateWiseThisMonthBusinessAttribute()
    {
        // dd($customerId);
        $txn_deposit = CustomerTransactions::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        })->where('transaction_type','deposit')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');

        $txn_withdraw = CustomerTransactions::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        })->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');

        return $txn_deposit - $txn_withdraw;

    }


    public function getStateWiseLastMonthBusinessAttribute()
    {
        // dd($customerId);
        $txn_deposit = CustomerTransactions::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        })->where('transaction_type','deposit')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');
        
        $txn_withdraw = CustomerTransactions::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('state_id',$this->id)->from('customer_details');
        })->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');

        return $txn_deposit - $txn_withdraw;

    }

}
