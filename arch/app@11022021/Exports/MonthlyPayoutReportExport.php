<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerReward;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 
use Carbon\Carbon;

class MonthlyPayoutReportExport implements FromCollection
{
  public $month = '';
  public $year = '';
  public $payment_type = '';

	public function __construct($request){
	    $this->month = $request->month;
	    $this->year = $request->year;
        $this->payment_type = $request->payment_type;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer Code','Customer Name','Interest Amount','Month','Year','Total Amount','Account Number','IFSC Code'];
    	$month = str_pad($this->month,2,0,STR_PAD_LEFT);
        $cust_reward = '';
        if($this->month != '' && $this->year != '' || $this->payment_type){
            // dd($this->payment_type);
            if($this->payment_type == '0'){
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->get();
            }else{
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('customer_id')->where('payment_type', $this->payment_type)->from('customer_details');
                })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->get();
            }
        }elseif($this->month != '' && $this->year != '' || $this->payment_type){
            $cust_reward = CustomerReward::where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->get();
        }else{
            $cust_reward = CustomerReward::groupBy(['customer_id','month','year'])->get();
        }

        foreach($cust_reward as $key => $reward){
	    	$key++;
	        	$records[$key]['Customer Code'] = $reward->customer->code;
				$records[$key]['Customer Name'] = $reward->customer->name;
				$records[$key]['Interest Amount'] =$reward->sum_monthly_payout_customer_wise;
				$records[$key]['Month'] = $reward->month;
				$records[$key]['Year'] = $reward->year;
				$records[$key]['Total Amount'] =$reward->total_amount;
				$records[$key]['Account Number'] = $reward->customer->customerdetails->account_no?$reward->customer->customerdetails->account_no:'N/A';
    			$records[$key]['IFSC Code'] = $reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A';
	    
        }


        return collect($records);
    }
}
