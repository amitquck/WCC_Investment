<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class AssociateCommissionPercentage extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','associate_id','interest_amount','commission_percent','status','start_date'];

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
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
    
}
