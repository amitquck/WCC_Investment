<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Model\CustomerTransactions;
use App\Model\CustomerInterestPercentage;


class AssociateCommissionPercentage extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','associate_id','interest_amount','commission_percent','status','start_date','no_of_introducer'];

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
    }

    public function customertransaction($customerID){
        return CustomerTransactions::where('customer_id',$customerID)->where('transaction_type','deposit')->sum('amount');
    }

    public function associate()
    {
       return $this->belongsTo('App\User','associate_id');
    }

    // public function associatetransaction()
    // {
    //    return $this->hasMany('App\Model\AssociateTransactions','customer_id','associate_id');
    // }

    public function getIsGenerateMonthlyAssociatePayoutAttribute()
    {
        // return 'dfsds';
        // dd(AssociateReward::where('customer_id', $this->customer_id)->where('associate_id', $this->associate_id)->where('month', '>=', Carbon::parse($this->start_date)->month)->where('year', '>=', Carbon::parse($this->start_date)->year)->toSql());
        return AssociateReward::where('customer_id', $this->customer_id)->where('associate_id', $this->associate_id)->where('month', '>=', Carbon::parse($this->start_date)->month)->where('year', '>=', Carbon::parse($this->start_date)->year)->first();
    }

    public function getCommission($customerID,$associateID)
    {
        return AssociateTransactions::where('associate_id', $associateID)->where('customer_id', $customerID)->where('transaction_type', 'commission')->sum('amount');
    }

    public function getMonthlyCommission($customerID,$associateID,$month,$year)
    {
        return AssociateTransactions::where('associate_id', $associateID)->where('customer_id', $customerID)->where('transaction_type', 'commission')->whereBetween('deposit_date',[date($year.'-'.$month.'-'.'01'),date($year.'-'.$month.'-'.'31')])->sum('amount');
    }


    public function getIntroducerRankAttribute()
    {
        return AssociateCommissionPercentage::where('associate_id', $this->associate_id)->where('customer_id', $this->customer_id)->first();
    }

    public function customerinterest($customerID)
    {
        return CustomerInterestPercentage::where('customer_id',$customerID)->get();
    }

    public function associateinterest($associateID,$customerID)
    {
        return $this->where('associate_id',$associateID)->where('customer_id',$customerID)->get();
    }
    
}
