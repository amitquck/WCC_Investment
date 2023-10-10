<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssociateReward extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','associate_id','amount','commission_percent','month','year','start_date','end_date','total_amount','commission_percent','reward_type'];

    protected $appends = ['sum_monthly_commission','customer_sum_monthly_commission'];


	public function customer(){
		return $this->belongsTo('App\User','customer_id');
	}
    public function associate(){
		return $this->belongsTo('App\User','associate_id');
	}

	public function associatetransactions(){
        return $this->hasMany('App\Model\AssociateTransactions','respective_table_id')->where('respective_table_name','associate_rewards');
    }

	public function delete()
    {
    	// delete all related photos
        $this->associatetransactions()->delete();
        // as suggested by Dirk in comment,
        // it's an uglier alternative, but faster
        // Photo::where("user_id", $this->id)->delete()

        // delete the user
        return parent::delete();
    }


    public function getSumMonthlyCommissionAttribute()
    {
        return AssociateReward::where('associate_id',$this->associate_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','commission')->sum('amount');
    }


    public function getCustomerSumMonthlyCommissionAttribute()
    {
        // dump($this->customer_id);
        // dump($this->associate_id);
        // dump($this->month);
        // dump($this->year);
        // dd(AssociateReward::where('customer_id',$this->customer_id)->where('associate_id',$this->associate_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','commission')->toSql());

        // $custs = AssociateReward::where('associate_id',$this->associate_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','commission')->get();
        // $total = 0;
        // foreach($custs as $key => $cust){
        //     $total += AssociateReward::where('customer_id',$cust)->where('associate_id',$cust->associate_id)->where('month',$cust->month)->where('year',$cust->year)->where('reward_type','commission')->sum('amount');
        // }

        return AssociateReward::where('associate_id',$this->associate_id)->where('month',$this->month)->where('year',$this->year)->where('reward_type','commission')->sum('amount');
    }

    public function getAssociatePerCustomerTotalAmountAttribute()
    {
        return AssociateReward::where('customer_id',$this->customer->id)->where('associate_id',$this->associate->id)->where('reward_type','commission')->sum('amount');
    }
}
