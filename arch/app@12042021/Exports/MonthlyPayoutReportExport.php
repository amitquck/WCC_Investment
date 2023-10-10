<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerReward;
use App\Model\ActivityLog;
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
        // dd($this->payment_type);
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	if($this->payment_type == 'bank'){
            // dd('scf');
            $records[] = ['Customer Name','Account Number','IFSC Code','Interest Amount','Remarks'];
        }elseif($this->payment_type == 'cash' || $this->payment_type == 'accumulate' || $this->payment_type == 'hold' || $this->payment_type == 'no_interest'){
            $records[] = ['Customer Name','Customer Code','Interest Amount'];
        }else{
            $records[] = ['Customer Code','Customer Name','Interest Amount','Month','Year','Total Amount','Account Number','IFSC Code'];
        }

    	$month = str_pad($this->month,2,0,STR_PAD_LEFT);
        $cust_reward = [];
        // dd();
        if($this->month != '' && $this->year != '' && $this->payment_type != ''){
            if($this->payment_type == '0'){
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
            }else{
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('customer_id')->where('payment_type', $this->payment_type)->from('customer_details');
                })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
            }
        }elseif($this->month != '' && $this->year != ''){

            $cust_reward = CustomerReward::where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
       
        }elseif($this->payment_type != ''){
            // dd('xfbfsh');
            if($this->payment_type == '0'){
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                })->groupBy('customer_id')->get();
            }else{
                $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                    return $q->select('customer_id')->where('payment_type', $this->payment_type)->from('customer_details');
                })->groupBy('customer_id')->get();
            }

        }else{
            $cust_reward = CustomerReward::groupBy(['customer_id','month','year'])->get();
        }
        if($cust_reward->count()>0){
            foreach($cust_reward as $key => $reward){
    	    	$key++;
                if($this->payment_type == 'bank'){
                    $records[$key]['Customer Name'] = $reward->customer->name;
                    $records[$key]['Account Number'] = $reward->customer->customerdetails->account_number?$reward->customer->customerdetails->account_number:'N/A';
                    $records[$key]['IFSC Code'] = $reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A';
                    $records[$key]['Interest Amount'] =$reward->sum_monthly_payout_customer_wise;
                    $records[$key]['Remarks'] = $reward->customer->code;
                }elseif($this->payment_type == 'cash' || $this->payment_type == 'accumulate' || $this->payment_type == 'hold' || $this->payment_type == 'no_interest'){
                    $records[$key]['Customer Name'] = $reward->customer->name;
                    $records[$key]['Customer Code'] = $reward->customer->code;
                    $records[$key]['Interest Amount'] =$reward->sum_monthly_payout_customer_wise;
                }else{
    	        	$records[$key]['Customer Code'] = $reward->customer->code;
    				$records[$key]['Customer Name'] = $reward->customer->name;
    				$records[$key]['Interest Amount'] =$reward->sum_monthly_payout_customer_wise;
    				$records[$key]['Month'] = $reward->month;
    				$records[$key]['Year'] = $reward->year;
    				$records[$key]['Total Amount'] =$reward->total_amount;
    				$records[$key]['Account Number'] = $reward->customer->customerdetails->account_number?$reward->customer->customerdetails->account_number:'N/A';
        			$records[$key]['IFSC Code'] = $reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A';
    	        }
            }
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;  
            // $activity_log->user_id = $user->id;    
            $activity_log->statement = 'Monthly Payout Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
            $activity_log->action_type = 'Excel Export';
            $activity_log->save();
        }

        return collect($records);
    }
}
