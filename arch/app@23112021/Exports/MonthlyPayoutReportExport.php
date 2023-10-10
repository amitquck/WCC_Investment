<?php

namespace App\Exports;

use DB;
use App\User;
use Carbon\Carbon;
use App\Model\ActivityLog;
use App\Model\CustomerReward;
use App\Model\AssociateReward;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class MonthlyPayoutReportExport  extends DefaultValueBinder implements WithCustomValueBinder, FromCollection //,WithColumnFormatting
{
  public $month = '';
  public $year = '';
  public $payment_type = '';
  public $user_type = '';

	public function __construct($request){
	    $this->month = $request->month;
	    $this->year = $request->year;
        $this->payment_type = $request->payment_type;
        $this->user_type = $request->user_type;
        // dd($this->payment_type);
	}

    /**
    * @return \Illuminate\Support\Collection
    */

    // public function columnFormats(): array
    // {
    //     return [
    //         'A' => NumberFormat::FORMAT_TEXT,
    //         'B' => NumberFormat::FORMAT_TEXT,
    //         'C' => NumberFormat::FORMAT_GENERAL,
    //         'D' => NumberFormat::FORMAT_NUMBER_00,
    //         'E' => NumberFormat::FORMAT_TEXT,
    //     ];
    // }

    public function bindValue(Cell $cell, $value)
    {
        $values = explode('NUMBER_',$value);
        if (sizeof($values) == 2) {
            // dd($value);
            $cell->setValueExplicit($values[1], DataType::TYPE_NUMERIC);

            return true;
        }else{
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }



    public function collection()
    {
    	$records = [];
    	if($this->user_type == '0'){
            if($this->payment_type == 'bank'){
                // dd('scf');
                $records[] = ['Customer Name','Account Number','IFSC Code','Interest Amount','Remarks'];
            }elseif($this->payment_type != 'bank' && $this->payment_type != NULL){
                $records[] = ['Customer Name','Customer Code','Interest Amount'];
            }else{
                $records[] = ['Customer Code','Customer Name','Interest Amount','Month','Year','Total Amount','Account Number','IFSC Code'];
            }
        }else{
            if($this->payment_type == 'bank'){
                // dd('scf');
                $records[] = ['Associate Name','Account Number','IFSC Code','Commission Amount','Remarks'];
            }elseif($this->payment_type != 'bank' && $this->payment_type != NULL){
                $records[] = ['Associate Name','Associate Code','Commission Amount'];
            }else{
                $records[] = ['Associate Code','Associate Name','Commission Amount','Month','Year','Total Amount','Account Number','IFSC Code'];
            }
        }

    	$month = str_pad($this->month,2,0,STR_PAD_LEFT);
        $cust_reward = [];
        // dd();
        if($this->month != '' && $this->year != '' && $this->payment_type != '' && $this->user_type != ''){
            if($this->payment_type == '0'){
                if($this->user_type == '0'){
                    $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                        return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                    })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
                }else{
                    $cust_reward = AssociateReward::whereIn('associate_id',function($q){
                        return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                    })->where('month',$month)->where('year',$this->year)->groupBy(['associate_id','month','year'])->orderByDesc('month')->get();
                }
            }else{
                if($this->user_type == '0'){
                    $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                        return $q->select('customer_id')->where('payment_type', $this->payment_type)->from('customer_details');
                    })->where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
                }else{
                    $cust_reward = AssociateReward::whereIn('associate_id',function($q){
                        return $q->select('associate_id')->where('payment_type', $this->payment_type)->from('associate_details');
                    })->where('month',$month)->where('year',$this->year)->groupBy(['associate_id','month','year'])->orderByDesc('month')->get();
                }
            }
        }elseif($this->month != '' && $this->year != '' && $this->user_type != ''){
            if($this->user_type == '0'){
                $cust_reward = CustomerReward::where('month',$month)->where('year',$this->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->get();
            }else{
                $cust_reward = AssociateReward::where('month',$month)->where('year',$this->year)->groupBy(['associate_id','month','year'])->orderByDesc('month')->get();
            }
        }elseif($this->payment_type != ''){
            if($this->payment_type == '0'){
                if($this->user_type == '0'){
                    $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                        return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                    })->groupBy('customer_id')->get();
                }else{
                    $cust_reward = AssociateReward::whereIn('associate_id',function($q){
                        return $q->select('id')->where('hold_status', $this->payment_type)->from('users');
                    })->groupBy('associate_id')->get();
                }
            }else{
                if($this->user_type == '0'){
                    $cust_reward = CustomerReward::whereIn('customer_id',function($q){
                        return $q->select('customer_id')->where('payment_type', $this->payment_type)->from('customer_details');
                    })->groupBy('customer_id')->get();
                }else{
                    $cust_reward = AssociateReward::whereIn('associate_id',function($q){
                        return $q->select('associate_id')->where('payment_type', $this->payment_type)->from('associate_details');
                    })->groupBy('associate_id')->get();
                }

            }

        }elseif($this->user_type == '1'){
            $cust_reward = AssociateReward::where('amount','>',0)->groupBy(['associate_id','month','year'])->get();
        }else{
            $cust_reward = CustomerReward::where('amount','>',0)->groupBy(['customer_id','month','year'])->get();
        }
        if($cust_reward->count()>0 && $this->user_type == '0'){
            foreach($cust_reward as $key => $reward){
    	    	$key++;
                if($this->payment_type == 'bank' && $reward->sum_monthly_payout_customer_wise != 0){
                    $records[$key]['Customer Name'] = $reward->customer->name;
                    $records[$key]['Account Number'] = $reward->customer->customerdetails->account_number?$reward->customer->customerdetails->account_number:'N/A';
                    $records[$key]['IFSC Code'] = $reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A';
                    $records[$key]['Interest Amount'] = 'NUMBER_'.$reward->sum_monthly_payout_customer_wise;
                    $records[$key]['Remarks'] = $reward->customer->code;
                }elseif($this->payment_type != 'bank' && $this->payment_type != NULL && $reward->sum_monthly_payout_customer_wise != 0){
                    $records[$key]['Customer Name'] = $reward->customer->name;
                    $records[$key]['Customer Code'] = $reward->customer->code;
                    $records[$key]['Interest Amount'] =$reward->sum_monthly_payout_customer_wise;
                }else{
                    if($reward->sum_monthly_payout_customer_wise != 0){
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
            }
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            // $activity_log->user_id = $user->id;
            $activity_log->statement = 'Monthly Payout Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
            $activity_log->action_type = 'Excel Export';
            $activity_log->save();
        }elseif($cust_reward->count()>0 && $this->user_type == '1'){
            foreach($cust_reward as $key => $reward){
    	    	$key++;
                if($this->payment_type == 'bank' && $reward->customer_sum_monthly_commission != 0){
                    $records[$key]['Associate Name'] = $reward->associate->name;
                    $records[$key]['Account Number'] = $reward->associate->associatedetail->account_number?$reward->associate->associatedetail->account_number:'N/A';
                    $records[$key]['IFSC Code'] = $reward->associate->associatedetail->ifsc_code?$reward->associate->associatedetail->ifsc_code:'N/A';
                    $records[$key]['Commission Amount'] ='NUMBER_'.$reward->customer_sum_monthly_commission;
                    $records[$key]['Remarks'] = $reward->associate->code;
                }elseif($this->payment_type != 'bank' && $this->payment_type != NULL && $reward->customer_sum_monthly_commission != 0){
                    $records[$key]['Associate Name'] = $reward->associate->name;
                    $records[$key]['Associate Code'] = $reward->associate->code;
                    $records[$key]['Commission Amount'] =$reward->customer_sum_monthly_commission;
                }else{
                    if($reward->customer_sum_monthly_commission != 0){
                        $records[$key]['Associate Code'] = $reward->associate->code;
                        $records[$key]['Associate Name'] = $reward->associate->name;
                        $records[$key]['Commission Amount'] =$reward->customer_sum_monthly_commission;
                        $records[$key]['Month'] = $reward->month;
                        $records[$key]['Year'] = $reward->year;
                        $records[$key]['Total Amount'] =$reward->total_amount;
                        $records[$key]['Account Number'] = $reward->associate->associatedetail->account_number?$reward->associate->associatedetail->account_number:'N/A';
                        $records[$key]['IFSC Code'] = $reward->associate->associatedetail->ifsc_code?$reward->associate->associatedetail->ifsc_code:'N/A';
                    }
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
