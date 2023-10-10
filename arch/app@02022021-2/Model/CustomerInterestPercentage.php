<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class CustomerInterestPercentage extends Model
{
	 use SoftDeletes;
    protected $fillable = ['customer_id', 'start_date',	'end_date',	'interest_percent','active_status' ];

    // protected $appends = ['is_generate_monthly_customer_payout'];

    public function getIsGenerateMonthlyCustomerPayoutAttribute()
    {
    	// return 'dfsds';
    	return CustomerReward::where('customer_id', $this->customer_id)->where('month', '>=', Carbon::parse($this->start_date)->month)->where('year', '>=', Carbon::parse($this->start_date)->year)->first();
    }
}
