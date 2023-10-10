<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateRewardTemp;
use App\Model\CustomerRewardTemp;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class BeforeConfirmationPayoutReportExport implements FromCollection
{

	public $month = '';
	public $year = '';

	public function __construct($request){
		$this->month = $request->month;
		$this->year = $request->year;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Code', 'Name','Interest/Commission Amount', 'Month', 'Year', 'Total Amount', 'Interest/Commission Percent', 'Reward Type', 'Created At'];

      $commissions = AssociateRewardTemp::where('reward_type','commission')->groupBy(DB::raw("CONCAT(year,month)"),'associate_id')->pluck('id'); 

      $Associatetransactions = AssociateRewardTemp::select('id','associate_id','customer_id','amount','month','year','start_date','end_date','payable_days','total_amount','reward_type','commission_percent','created_by','updated_by','created_at','updated_at')->where(function($q){
              return $q->where('reward_type','commission')->where('month',$this->month)->where('year',$this->year);
          })
        ->whereIn('id',$commissions);

      $interest = CustomerRewardTemp::where('reward_type','interest')->groupBy(DB::raw("CONCAT(year,month)"),'customer_id')->pluck('id'); 

      $transactions = CustomerRewardTemp::select('id',DB::raw('0 as associate_id'),'customer_id','amount','month','year','start_date','end_date','payable_days','total_amount','reward_type','interest_percent','created_by','updated_by','created_at','updated_at')->where(function($q){
            return $q->where('reward_type', 'interest')->where('month',$this->month)->where('year',$this->year);
        })
        ->whereIn('id',$interest);

      $combine_transactions = $transactions->union($Associatetransactions);
      $db_records = $combine_transactions->get();

		  foreach($db_records as $key => $record){//$transaction->associate->name//customers
	      $key++;
        if($record->reward_type == 'commission'){
          $records[$key]['Code']                       = $record->associate->code;
          $records[$key]['Name']                       = 'Associate : '.ucwords($record->associate->name);
          $records[$key]['Interest/Commission Amount'] = $record->sum_commission_amount;
        }else{
          $records[$key]['Code']                       = $record->customer->code;
          $records[$key]['Name']                       = 'Customer : '.ucwords($record->customer->name);
          $records[$key]['Interest/Commission Amount'] = $record->sum_interest_amount;
        }
        
        $records[$key]['Month']                       = $record->month;
        $records[$key]['Year']                        = $record->year;
        $records[$key]['Total Amount']                = $record->total_amount;
        $records[$key]['Interest/Commission Percent'] = $record->interest_percent;
        $records[$key]['Reward Type']                 = $record->reward_type;
        $records[$key]['Created At']                  = $record->created_at;
        
    	}  
      $activity_log = new ActivityLog;
      $activity_log->created_by = auth()->user()->id;  
      // $activity_log->user_id = $user->id;    
      $activity_log->statement = 'Before Confirmation Payout Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
      $activity_log->action_type = 'Excel Export';
      $activity_log->save();
      return collect($records);
    }
}

