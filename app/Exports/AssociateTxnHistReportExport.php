<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateTransactions;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class AssociateTxnHistReportExport implements FromCollection
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
    	$records = [];
        $records[0] = [ucwords(auth()->user()->name).' ('.auth()->user()->code.')'];
    	$records[1] = ['Credit', 'Debit', 'Transaction Type','Payment Mode','Bank Name', 'Cheque/DD Number','Deposit Date'];

		$this->associate = auth()->user()->code;
        $from_date = date('Y-m-d',strtotime($this->from_date));
        $to_date = date('Y-m-d',strtotime($this->to_date));
        if($this->from_date != '' && $this->to_date != '' && $this->associate != ''){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($from_date, $to_date){
                    return $q->whereBetween('deposit_date', [$from_date, $to_date]);
                });


            $transactions->whereIn('associate_id',function($q){
                return $q->select('id')->where('code',$this->associate)->from('users');
            });

        }elseif($this->associate != ''){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where('transaction_type', '!=', 'interest');


            $transactions->whereIn('associate_id',function($q){
                return $q->select('id')->where('code',$this->associate)->from('users');
            });

        }
        $db_records = $transactions->orderByDesc('deposit_date')->get();


        // $db_records = $transactions->orderByDesc('deposit_date')->get();
        $i = 3;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			if($record->amount != 0){
                $i = $i+1;
                $total_credit = $total_debit = 0;
                if($record->associate != NULL || $record->customers != NULL){

                    if($record->transaction_type == 'deposit'){
                        $total_credit += $record->amount;
                        $records[$i]['Credit'] = $record->amount;
                    }else{
                        $records[$i]['Credit'] = 0.00;
                    }

                    if($record->transaction_type == 'withdraw'){
                        $total_debit += $record->amount;
                        $records[$i]['Debit'] = $record->amount;
                    }else{
                        $records[$i]['Debit'] = 0.00;
                    }

                    $records[$i]['Transaction Type'] = $record->transaction_type;

                    $records[$i]['Payment Mode'] = $record->payment_type;
                    $records[$i]['Bank Details'] = $record->bankname?$record->bankname->bank_name:'N/A';
                    $records[$i]['Cheque/DD Number'] = $record->cheque_dd_number?$record->cheque_dd_number:'N/A';
                    $records[$i][6] = $record->deposit_date;


                }

                $i++;
            }
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Associate Txn Hist Report Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
