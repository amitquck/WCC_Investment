<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class DirectCustomerTransactionsReportExport implements FromCollection
{

	public $custId = '';

	public function __construct($request){
		$this->custId = $request->custId;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[1] = ['Credit', 'Debit', 'Transaction Type','Payment Mode','Bank Name', 'Cheque/DD Number','Deposit Date'];

		$db_records = CustomerTransactions::where('customer_id',$this->custId)->where('transaction_type','!=','interest')->get();


        // $db_records = $transactions->orderByDesc('deposit_date')->get();
        $i = 3;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			if($record->amount != 0){
                $i = $i+1;
                if($record->transaction_type == 'deposit'){
                    $records[$i]['Credit'] = $record->amount;
                }else{
                    $records[$i]['Credit'] = 0.00;
                }

                if($record->transaction_type == 'withdraw'){
                    $records[$i]['Debit'] = $record->amount;
                }else{
                    $records[$i]['Debit'] = 0.00;
                }

                $records[$i]['Transaction Type'] = $record->transaction_type;
                $records[$i]['Payment Mode'] = $record->payment_type;
                $records[$i]['Bank Details'] = $record->bankname?$record->bankname->bank_name:'N/A';
                $records[$i]['Cheque/DD Number'] = $record->cheque_dd_number?$record->cheque_dd_number:'N/A';
                $records[$i]['Deposit Date'] = $record->deposit_date;

                $i++;
            }
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Direct Customer Transactions Report Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
