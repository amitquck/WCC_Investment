<?php

namespace App\Exports;

use App\User;
use App\Model\ActivityLog;
use App\Model\AssociateTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class LastMonthAssBusiReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $param = '';
	public function __construct($request){
		 $this->param = $request->param;
    }

    public function collection()
    {
    	$records = [];
    	$records[] = ['','Code', 'Name', 'Amount'];

        if($this->param == 'selfCustBusiLastMon'){
            $customer = AssociateTransactions::whereIn('customer_id',function($q){
                return $q->select('customer_id')->from('associate_commission_percentages')->where('associate_id',auth()->user()->id)->where('status',1)->where('no_of_introducer',1)->where('deleted_at');
            })->whereBetween('deposit_date',[date('Y-m-01',strtotime("-1 month")),date('Y-m-t',strtotime("-1 month"))]);

            $associate = AssociateTransactions::where('associate_id',auth()->user()->id)->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01',strtotime("-1 month")),date('Y-m-t',strtotime("-1 month"))]);

            $customers = $customer->union($associate);
            $db_records = $customers->groupBy('customer_id')->get();
        }else{
            $customer = AssociateTransactions::whereIn('customer_id',function($q){
                return $q->select('customer_id')->from('associate_commission_percentages')->where('associate_id',auth()->user()->id)->where('status',1)->where('deleted_at');
            })->whereBetween('deposit_date',[date('Y-m-01',strtotime("-1 month")),date('Y-m-t',strtotime("-1 month"))]);

            $associate = AssociateTransactions::where('associate_id',auth()->user()->id)->where('transaction_type','withdraw')->whereBetween('deposit_date',[date('Y-m-01',strtotime("-1 month")),date('Y-m-t',strtotime("-1 month"))]);

            $customers = $customer->union($associate);
            $db_records = $customers->groupBy('customer_id')->get();
        }

	  	foreach($db_records as $key => $record){//$transaction->associate->name//customers
            $amt = getBalance($record->customer_id,$record->associate_id);
            if($amt != 0){
                $key++;
                if($record->customer_id){
                    $records[$key][0]  = 'Customer';
                }else{
                    $records[$key][0]  = 'Associate';
                }
                $records[$key][1]  = $record->customer_id?$record->customer->code:$record->associate->code;
                $records[$key][2]  = $record->customer_id?$record->customer->name:$record->associate->name;
                if($amt > 0){
                    $records[$key][3]  = $amt;
                }else{
                    $records[$key][3]  = - $amt;
                }
            }
    	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Last Month Associate Business Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}

