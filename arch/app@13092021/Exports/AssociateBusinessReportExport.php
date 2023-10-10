<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 
use App\Model\AssociateCommissionPercentage;
use App\Model\ActivityLog;

class AssociateBusinessReportExport implements FromCollection
{
	public $id = '';
  public $from_date = '';
  public $to_date = '';

	public function __construct($request){
    $this->id = $request->id;
    $this->from_date = $request->from_date;
    $this->to_date = $request->to_date;
	}

    public function collection()
    {
        $associate = User::where('id', $this->id)->where('login_type', 'associate')->first();
        $records[0] = [$associate->name.' ('.$associate->code.')'];
        $records[1] = ['Customer Code','Customer Name','Mobile','Email','Balance'];
        $associate_txn = AssociateCommissionPercentage::where('associate_id', $this->id)->where('no_of_introducer', 1)->where('status', 1)->groupBy('customer_id')->get();
        $i = 3;
        foreach($associate_txn as $key => $commission){
        // dd($commission->customer->name);
          $i = $i+1;
          if($commission->customer->customerMainBalance() != null){
            $records[$i]['Customer Code'] = $commission->customer->code;
            $records[$i]['Customer Name'] = $commission->customer->name;
            $records[$i]['Mobile'] = $commission->customer?$commission->customer->mobile:'N/A';
            $records[$i]['Email'] = $commission->customer?$commission->customer->email:'N/A';
            if($this->from_date == null){
              $records[$i]['Balance'] = $commission->customer->customerMainBalance();
            }elseif(date('Y-m',strtotime($this->from_date)) == date('Y-m',strtotime('-1 month'))){
              $records[$i]['Balance'] = $commission->customer->customerLastMonthMainBalance();
            }elseif(date('Y-m',strtotime($this->from_date)) == date('Y-m')){
              $records[$i]['Balance'] = $commission->customer->customerThisMonthMainBalance();
            }
          }
          $i++;
        }
        
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Associate Wise Customer Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
