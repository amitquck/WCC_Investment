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
        $i = 0;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
            $records[$i] = ['Client Code', 'Client Name', 'Balance'];
            $i = $i+1;
            // dump($records);
			$records[$i]['Client Code'] = $record->code;
			$records[$i]['Client Name'] = $record->name;
            $records[$i]['Balance']     = $record->customer_current_balance();
            // $records[] = [''];
            // $records[] = [''];
            // $records[] = [''];
            $i = $i+1;
            $records[$i] = ['Associate Code','Associate Name','Commission %','Balance'];
            
            foreach($record->associatecommissions as $key => $commission){
                $i = $i+1;
                $records[$i] = [$commission->associate->code,$commission->associate->name,$commission->commission_percent,(($record->customer_current_balance()*$commission->commission_percent)/100)];
              
            }
            $records[] = ['']; 
            // $key++;
            $i++;
      	}
        return collect($records);
    }
}
