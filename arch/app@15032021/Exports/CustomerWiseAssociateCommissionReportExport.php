<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateCommissionPercentage;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class CustomerWiseAssociateCommissionReportExport implements FromCollection
{

	public $associate = '';
    public $month = '';
    public $year = '';

	public function __construct($request,$associateID){
        // 
		$this->associate = decrypt($associateID);
        $this->month = $request->month;
        $this->year = $request->year;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
        $user = User::where('id',$this->associate)->whereStatus(1)->first();
        $records[0][2] = ['Associate Name-Code', $user->name.'-'.$user->code];
    	$records[1] = ['Customer Code','Customer Name','Commission Amount','No Of Introducer','Associate Commission Percent','Customer Mark Date'];

    	// $ass_id = decrypt($associateID);
        $associate_txn = AssociateCommissionPercentage::where('associate_id', $this->associate)->groupBy('customer_id');
        
		$db_records = $associate_txn->get();
        $i = 3;
		foreach($db_records as $key => $record){
			$i = $i+1;
	        $records[$i]['Customer Code']   = $record->customer->code;
	        $records[$i]['Customer Name']   = $record->customer->name;

            if($this->month != '' && $this->year != ''){
               $records[$i]['Commission Amount'] = $record->getMonthlyCommission($record->customer_id,$record->associate_id,$this->month,$this->year)?$record->getMonthlyCommission($record->customer_id,$record->associate_id,$this->month,$this->year):'0.00';
            }else{
                $records[$i]['Commission Amount'] = $record->getCommission($record->customer_id,$record->associate_id)?$record->getCommission($record->customer_id,$record->associate_id):'0.00';
            }
            $records[$i]['No Of Introducer']   = $record->introducer_rank->no_of_introducer;
            $records[$i]['Associate Commission Percent']   = $record->introducer_rank->commission_percent.' %';
	        $records[$i]['Customer Mark Date']  = $record->created_at;
	        $i++;
      	}
        return collect($records);
    }
}
