<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateCommissionPercentage;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class AssociateDirectCustomerReportExport implements FromCollection
{

	public $custId = '';

	// public function __construct($request){
	// 	$this->custId = $request->custId;
	// }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	// $records = [];
        $records[0] = [auth()->user()->name.' ('.auth()->user()->code.')'];
    	$records[1] = ['Customer Code', 'Customer Name','Investment Amount','Commission Percent', 'Last Deposit Date'];

		$db_records = AssociateCommissionPercentage::whereAssociateId(auth()->user()->id)->where('no_of_introducer',1)->where('status',1)->get();


        // $db_records = $transactions->orderByDesc('deposit_date')->get();
        $i = 3;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
            $i = $i+1;
            $records[$i]['Customer Code'] = $record->customer->code;
            $records[$i]['Customer Name'] = $record->customer->name;
            $date = getLastCRDate($record->customer_id);
            $records[$i]['Investment Amount'] = $date?$date->amount:'N/A';
            $records[$i]['Commission Percent'] = $record->commission_percent.' % ';
            if($date != NULL){
                $records[$i]['Deposit Date'] = date('d-m-Y',strtotime($date->deposit_date));
            }else{
                $records[$i]['Deposit Date'] = 'N/A';
            }
            $i++;
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Direct Customer Report Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
