<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;
use App\Model\State;

class City extends Model
{
	 use SoftDeletes;
    protected $fillable = ['name','country','city','created_by'];

    public function countrydetail()
    {
    	return $this->belongsTo(Country::class,'country');
    }
    public function statedetail(){
    	return $this->belongsTo(State::class,'state');

    }

    public function customerdetails(){
        // dd($this);
       return $this->hasMany('App\Model\CustomerDetail');
    }

    public function getCityWiseBusinessAttribute($customerId)
    {
        // dd($customerId);
        $txn = CustomerTransactions::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('city_id',$this->id)->from('customer_details');
        })->where('cr_dr','cr')->where('transaction_type','deposit')->sum('amount');

        $cust_reward = CustomerReward::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('city_id',$this->id)->from('customer_details');
        })->where('reward_type','interest')->sum('amount');

        $asso_reward = AssociateReward::whereIn('customer_id',function($q){
            return $q->select('customer_id')->where('city_id',$this->id)->from('customer_details');
        })->where('reward_type','commission')->sum('amount');

        return $txn - ($cust_reward + $asso_reward);
    }
}
