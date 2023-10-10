<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateReward;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 
use Carbon\Carbon;

class AssociateCommissionReportExport implements FromCollection
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
    	$records[] = ['Associate Code','Associate Name','Commission Amount','Month','Year','Account Number','IFSC Code'];
    	$month = str_pad($this->month,2,0,STR_PAD_LEFT);
        $associate_reward = [];
        if($this->month != '' && $this->year != ''){
            $associate_reward = AssociateReward::where('month',$month)->where('year',$this->year)->groupBy(['associate_id','month','year'])->get(); 
        }else{
            $associate_reward = AssociateReward::groupBy(['associate_id','month','year'])->get();
        }
        if($associate_reward->count()>0){
            foreach($associate_reward as $key => $reward){
    	    	$key++;
	        	$records[$key]['Associate Code'] = $reward->associate->code;
				$records[$key]['Associate Name'] = $reward->associate->name;
				$records[$key]['Commission Amount'] =$reward->sum_monthly_commission;
				$records[$key]['Month'] = $reward->month;
				$records[$key]['Year'] = $reward->year;
				$records[$key]['Account Number'] = $reward->associate->associatedetail->account_number?$reward->associate->associatedetail->account_number:'N/A';
    			$records[$key]['IFSC Code'] = $reward->associate->associatedetail->ifsc_code?$reward->associate->associatedetail->ifsc_code:'N/A';
    	    
            }
        }
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Associate Commission Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
