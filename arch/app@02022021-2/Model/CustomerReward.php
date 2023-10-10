<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerReward extends Model
{
	use SoftDeletes;

    // this is the recommended way for declaring event handlers
    // public static function boot() {
    //     parent::boot();
    //     self::deleting(function($customerreward) { // before delete() method call this
    //     	dd('sdfsd');
    //          $customerreward->customertransactions()->each(function($customertransaction) {
    //             $customertransaction->delete(); // <-- direct deletion
    //          });
    //     });
    // }

    protected $fillable = ['customer_id','amount','commission_percent','month','year','start_date','end_date','total_amount','commission_percent','reward_type'];

    protected $appends = ['sum_monthly_interest'];
    
    public function customertransactions(){
        return $this->hasMany('App\Model\CustomerTransactions','respective_table_id')->where('respective_table_name','customer_rewards');
    }

    public function delete()
    {
    	// delete all related photos 
        $this->customertransactions()->delete();
        // as suggested by Dirk in comment,
        // it's an uglier alternative, but faster
        // Photo::where("user_id", $this->id)->delete()

        // delete the user
        return parent::delete();
    }

    public function associaterewards(){
        return $this->hasMany('App\Model\AssociateReward','customer_id');
    }

    public function getSumMonthlyInterestAttribute()
    {
        // dd($this->month);
        return CustomerReward::where('customer_id',$this->customer_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','interest')->sum('amount');
    }

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
    }

    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 

    public function getSumMonthlyPayoutCustomerWiseAttribute()
    {
        // dd(CustomerReward::where('customer_id',$this->customer_id)->groupBy(['customer_id','month','year'])->sum('amount'));
        return CustomerReward::where('customer_id',$this->customer_id)->where('month',$this->month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->sum('amount');//where('customer_id',$this->customer_id)->
    }
}
