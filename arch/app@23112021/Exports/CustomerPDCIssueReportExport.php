<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerSecurityCheque;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class CustomerPDCIssueReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer Code', 'Customer Name', 'Cheque Issue Date', 'Cheque Maturity Date', 'Cheque Bank Name', 'Cheque Number', 'Cheque Amount', 'Created At'];

      	$db_records = CustomerSecurityCheque::where('cheque_issue_date', '!=', NULL)->get();

	  	foreach($db_records as $key => $record){//$transaction->associate->name//customers
	      	$key++;
        	$records[$key]['Customer Code']         = $record->cheque_user->code;
	        $records[$key]['Customer Name']         = $record->cheque_user->name;
	        $records[$key]['Cheque Issue Date']     = $record->cheque_issue_date;
	        $records[$key]['Cheque Maturity Date']  = $record->cheque_maturity_date;
	        $records[$key]['Cheque Bank Name']      = $record->cheque_bank_name?$record->cheque_bank_name:'N/A';
	        $records[$key]['Cheque Number']         = $record->cheque_number?$record->cheque_number:'N/A';
	        $records[$key]['Cheque Amount']         = $record->cheque_amount?$record->cheque_amount:'N/A';
	        $records[$key]['Created At']            = $record->created_at;
    	}  

    	$activity_log = new ActivityLog;
		$activity_log->created_by = auth()->user()->id;  
		// $activity_log->user_id = $user->id;    
		$activity_log->statement = 'Customer PDC Issue Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
		$activity_log->action_type = 'Excel Export';
		$activity_log->save();
      return collect($records);
    }
}

