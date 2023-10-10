<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AssociateWiseCustomerExport implements FromCollection
{
	public $id = '';

	public function __construct($request){
		$this->id = $request->id;
	}

    
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer Code','Customer Name','Associate Commission Percentage','Total Commission Per Customer'];
    	$userss = User::where('login_type','associate');
		if($this->id != '' ){
			$userss->where('id',$this->id);
		}
		$userss = $userss->get();
        foreach($userss as $user){
            $users = $user->associatecommission;
            $totalcommission = $user->associatetransactions;
            foreach($users as $key => $commission){
            $commission_amount = $totalcommission->where('customer_id',$commission->customer->id)->where('associate_id',$commission->associate->id)->where('transaction_type','commission')->sum('amount');
            	$key++;
            	$records[$key]['Customer Code'] = $commission->customer->code;
            	$records[$key]['Customer Name'] = $commission->customer->name;
            	$records[$key]['Associate Commission Percentage'] = $commission->commission_percent;
            	$records[$key]['Total Commission Per Customer'] = $commission_amount?$commission_amount:'0';
        	}
        }
        
    return collect($records);
  	}
}
