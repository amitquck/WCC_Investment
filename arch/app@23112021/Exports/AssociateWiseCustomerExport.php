<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 
use App\Model\AssociateCommissionPercentage;
use App\Model\ActivityLog;

class AssociateWiseCustomerExport implements FromCollection
{
	public $id = '';
  public $q = '';

	public function __construct($request){
		$this->q = $request->q;
    $this->id = $request->id;
	}

    
  //   public function collection()
  //   {
  //   	$records = [];
  //   	$records[] = ['Customer Code','Customer Name','Associate Commission Percentage','Total Commission Per Customer'];
  //   	$userss = User::where('login_type','associate');
		// if($this->id != '' ){
		// 	$userss->where('id',$this->id);
		// }
		// $userss = $userss->get();
  //       foreach($userss as $user){
  //           $users = $user->associatecommission;
  //           $totalcommission = $user->associatetransactions;
  //           foreach($users as $key => $commission){
  //           $commission_amount = $totalcommission->where('customer_id',$commission->customer->id)->where('associate_id',$commission->associate->id)->where('transaction_type','commission')->sum('amount');
  //           	$key++;
  //           	$records[$key]['Customer Code'] = $commission->customer->code;
  //           	$records[$key]['Customer Name'] = $commission->customer->name;
  //           	$records[$key]['Associate Commission Percentage'] = $commission->commission_percent;
  //           	$records[$key]['Total Commission Per Customer'] = $commission_amount?$commission_amount:'0';
  //       	}
  //       }
        
  //       return collect($records);
  // 	}


    public function collection()
    {
        $associate = User::where('id', $this->id)->where('login_type', 'associate')->first();
        $records[0] = [$associate->name.' ('.$associate->code.')'];
        $records[1] = ['Customer Code','Customer Name','Mobile','Email','Balance'];
        if($this->q == 'top_ass'){
          $associate_txn = AssociateCommissionPercentage::where('associate_id', $this->id)->where('no_of_introducer',1)->groupBy('customer_id')->get();
        }else{
          $associate_txn = AssociateCommissionPercentage::where('associate_id', $this->id)->groupBy('customer_id')->get();
        }
        $i = 3;
        foreach($associate_txn as $key => $commission){
          $i = $i+1;
         // if($commission->customer->customerMainBalance() != null || $commission->customer->customer_current_balance() != null){
            $records[$i]['Customer Code'] = $commission->customer->code;
            $records[$i]['Customer Name'] = $commission->customer->name;
            $records[$i]['Mobile'] = $commission->customer->mobile?$commission->customer->mobile:'N/A';
            $records[$i]['Email'] = $commission->customer->email?$commission->customer->email:'N/A';
            if($this->q == 'top_ass'){
              $records[$i]['Balance'] = $commission->customer->customerMainBalance().'0.00';
            }else{
              $records[$i]['Balance'] = $commission->customer->customer_current_balance().'0.00';
            }
        //  } 
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
