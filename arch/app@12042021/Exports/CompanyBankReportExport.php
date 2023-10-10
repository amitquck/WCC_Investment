<?php

namespace App\Exports;

use App\User;
use App\Model\CompanyBank;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class CompanyBankReportExport implements FromCollection
{

	// public $state_id = '';
	// public $city_id = '';
	// public $id = '';

	public function __construct($request){
		// $this->state_id = $request->state_id;
		// $this->city_id = $request->city_id;
		// $this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Bank Name','Opening Balance','Balance','Created At'];
    	$banks = CompanyBank::whereStatus(1);
        
		$db_records = $banks->orderBy('created_at')->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Bank Name'] = $record->bank_name;
	        $records[$key]['Opening Balance'] = $record->amount;
	        $records[$key]['Balance'] = ($record->amount+$record->bank_current_balance)?($record->amount+$record->bank_current_balance):'0.00';
	        $records[$key]['Created At'] = $record->created_at;
      	}
		$activity_log = new ActivityLog;
		$activity_log->created_by = auth()->user()->id;  
		// $activity_log->user_id = $user->id;    
		$activity_log->statement = 'Company Bank Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
		$activity_log->action_type = 'Excel Export';
		$activity_log->save();
        return collect($records);
    }
}
