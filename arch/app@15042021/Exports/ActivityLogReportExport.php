<?php

namespace App\Exports;

use App\User;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class ActivityLogReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Created By', 'Code', 'Name', 'Statement', 'Created At'];

      	$db_records = ActivityLog::orderByDesc('created_at')->get();

	  	foreach($db_records as $key => $record){//$transaction->associate->name//customers
	      	$key++;
        	$records[$key]['Created By']  = $record->user->name;
	        if($record->client){
		        if($record->client->login_type == 'associate'){
	          		$records[$key]['Code'] = $record->client->code;
		          	$records[$key]['Name'] = 'Associate : '.ucwords($record->client->name);
		        }elseif($record->client->login_type == 'customer'){
		          	$records[$key]['Code'] = $record->client->code;
		          	$records[$key]['Name'] = 'Customer : '.ucwords($record->client->name);
		        }
	        }else{
                $records[$key]['Code'] = 'N/A';
                $records[$key]['Name'] = 'N/A';
            }
	        $records[$key]['Statement']  = $record->statement;
	        $records[$key]['Created At'] = $record->created_at;
        
    	}  
    	$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Activity Log Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save(); 

      return collect($records);
    }
}

