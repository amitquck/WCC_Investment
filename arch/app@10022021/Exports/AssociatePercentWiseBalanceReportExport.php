<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AssociatePercentWiseBalanceReportExport implements FromCollection
{

	// public $from_date = '';
	// public $to_date = '';
	// public $customer = '';

	// public function __construct($request){
	// 	$this->from_date = $request->from_date;
	// 	$this->to_date = $request->to_date;
	// 	$this->customer = $request->customer;
	// }

    /**
    * @return \Illuminate\Support\Collection, 'Associate Breakage'
    */
    public function collection()
    {
    	$records = [];
    	
		$db_records = User::where('login_type','customer')->get();
        
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
            $records[] = ['Client Code', 'Client Name', 'Balance'];
			$key++;
			$records[$key]['Client Code'] = $record->code;
			$records[$key]['Client Name'] = $record->name;
            $records[$key]['Balance']     = $record->customer_current_balance();
			$records[] = ['Associate Code','Associate Name','Commission %','Balance'];
            foreach($record->associatecommissions as $key => $commission){
                $records[] = [$commission->associate->code,$commission->associate->name,$commission->commission_percent,'₹ '.(($record->customer_current_balance()*$commission->commission_percent)/100)];
              
            }
            $records[] = ['']; 
      	}
        return collect($records);
    }
}
