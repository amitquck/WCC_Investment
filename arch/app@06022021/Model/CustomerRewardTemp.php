<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerRewardTemp extends Model
{
    use SoftDeletes;

    // this is the recommended way for declaring event handlers
    // public static function boot() {
    //     parent::boot();
    //     self::deleting(function($customerreward) { // before delete() method call this
    //      dd('sdfsd');
    //          $customerreward->customertransactions()->each(function($customertransaction) {
    //             $customertransaction->delete(); // <-- direct deletion
    //          });
    //     });
    // }

    protected $fillable = ['customer_id','amount','commission_percent','month','year','start_date','end_date','total_amount','commission_percent','reward_type'];

    // protected $appends = ['sum_monthly_interest'];
    
    public function customertransactions(){
        return $this->hasMany('App\Model\CustomerTransactionTemp','respective_table_id')->where('respective_table_name','customer_rewards');
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

    // public function associaterewards(){
    //     return $this->hasMany('App\Model\AssociateReward','customer_id');
    // }

    // public function associaterewardtemp(){
    //     return dump($this->hasMany('App\Model\AssociateRewardTemp','customer_id'));
    //     // return $this->belongsTo('App\Model\AssociateRewardTemp','customer_id');
    // }

    // public function getSumMonthlyInterestAttribute()
    // {
    //     return CustomerReward::where('customer_id',$this->customer_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','interest')->sum('amount');
    // }

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
    }

    /*
    * Important : This Relation Will Be Accessible Only When Customer Reward Temp Union With Associate Reward Temp.
    *
    */
    public function associate(){
        return $this->belongsTo('App\User','associate_id');
    } 

    
}
