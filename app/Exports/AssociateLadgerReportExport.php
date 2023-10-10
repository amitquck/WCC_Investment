<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateTransactions;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class AssociateLadgerReportExport implements FromCollection
{

	public $from_date = '';
	public $to_date = '';
	public $associate = '';

	public function __construct($request){
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
		$this->associate = $request->associate;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	// $records = [];
    	$from_date = date('Y-m-d',strtotime($this->from_date));
        $to_date = date('Y-m-d',strtotime($this->to_date));
        if($this->associate != NULL){
            $ass = User::where('code',$this->associate)->first();
        }else{
            $ass = User::where('code',auth()->user()->code)->first();
            $this->associate = auth()->user()->code;
        }
        $records[0] = [$ass->name.'('.$ass->code.')'];
    	$records[1] = ['User','Code', 'Name','Credit', 'Debit', 'Running Balnace', 'Credit/Debit Date'];

		if($this->from_date && $this->to_date && $this->associate){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($from_date, $to_date){
                    return $q->whereBetween('deposit_date', [$from_date, $to_date]);
                });


            $transactions->whereIn('associate_id',function($q){
                return $q->select('id')->where('code',$this->associate)->from('users');
            });

        }elseif($this->associate != ''){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at');


            $transactions->whereIn('associate_id',function($q){
                return $q->select('id')->where('code',$this->associate)->from('users');
            });

        }
        $db_records = $transactions->orderBy('deposit_date')->get();
        $total_credit = $total_debit = $running_balance = 0;
        $i = 3;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			$key++;
            $i = $i+1;
            if($record->customer_id != null){
                $records[$i]['User'] = 'Customer';
                $records[$i]['Code'] = $record->customer->code;
                $records[$i]['Name'] = $record->customer->name;
            }else{
                $records[$i]['User'] = 'Associate';
                $records[$i]['Code'] = $record->associate->code;
                $records[$i]['Name'] = $record->associate->name;
            }
			if($record->transaction_type == 'commission'){
	        	$records[$i]['Credit'] = $record->amount;
	        	$running_balance += $record->amount;
	        	$total_credit += $record->amount;
	        }else{
	        	$records[$i]['Credit'] = '0.00';
	        }
	        if($record->transaction_type == 'withdraw'){
	        	$records[$i]['Debit'] = $record->amount;
	        	$running_balance -= $record->amount;
                $total_debit += $record->amount;
	        }else{
	        	$records[$i]['Debit'] = '0.00';

	        }
	        $records[$i]['Running Balnace'] = number_format($running_balance,2);
	        $records[$i]['Credit/Debit Date'] = $record->deposit_date;
            $i++;
      	}
      	$records[] = [''];
      	$records[] = ['Total Credit',number_format($total_credit,2),'Total Debit',number_format($total_debit,2)];

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Associate Ladger Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
