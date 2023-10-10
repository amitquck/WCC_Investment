<?php

namespace App\Exports;

use App\User;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class HoldCustomerReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer Code', 'Customer Name', 'Mobile Number', 'Email', 'Balance', 'Hold Remarks'];

      	$db_records = User::where('hold_status',0)->get();

	  	foreach($db_records as $key => $record){//$transaction->associate->name//customers
	      	$key++;
        	$records[$key]['Customer Code']  = $record->code;
	        $records[$key]['Customer Name']  = $record->name;
	        $records[$key]['Mobile Number']  = $record->mobile?$record->mobile:'N/A';
	        $records[$key]['Email']  = $record->email?$record->email:'N/A';
	        $records[$key]['Balance']  = $record->customer_current_balance();
	        $records[$key]['Hold Remarks']  = $record->hold_remarks;
    	} 
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Hold Customer Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save(); 
      return collect($records);
    }
}

