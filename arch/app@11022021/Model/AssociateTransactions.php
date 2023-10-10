<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class AssociateTransactions extends Model
{
 	use Notifiable,SoftDeletes;
    protected $fillable = ['associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type'];

	protected $appends = ['sum_monthly_commission'];

    public function associatereward()
    {
    	return $this->hasMany('App\Model\AssociateReward','associate_id');
    }

    public function getSumMonthlyCommissionAttribute()
    {
        return AssociateReward::where('associate_id',$this->associate_id)->where('month',Carbon::parse($this->deposit_date)->format('m'))->where('year',Carbon::parse($this->deposit_date)->format('Y'))->where('reward_type','commission')->sum('amount');
    }


    public function bankname()
    {
        return $this->belongsTo('App\Model\CompanyBank','bank_id');
    }

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
    } 
    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 

    public function associate_data(){
        // dd('safafs');
        return $this->belongsTo('App\User','associate_id');
    } 
}
