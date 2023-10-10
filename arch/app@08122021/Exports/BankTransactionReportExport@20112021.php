<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\CompanyBank;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;

class BankTransactionReportExport implements FromCollection
{

	public $from_date = '';
	public $to_date = '';
	public $bank_id = '';

	public function __construct($request){
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
		$this->bank_id = $request->bank_id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Description', 'Cheque/DD Number','Cheque/DD Date', 'Credit', 'Debit', 'Running Balnace', 'Credit/Debit Date'];
    	$bank = CompanyBank::where('id',$this->bank_id)->first();
        $Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
            return $q->where('transaction_type','withdraw')->where('bank_id',$this->bank_id);
        });
            if($this->from_date && $this->from_date!= '' && $this->to_date && $this->to_date!= ''){
            // dd($request->from_date);
            $Associatetransactions->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
            }
        $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
            return $q->where('transaction_type', '!=', 'interest')->where('bank_id',$this->bank_id);
        });

        $combine_transactions = $transactions->union($Associatetransactions);


            if($this->from_date != '' && $this->to_date != ''){
                // dd('zdvzv');
                $combine_transactions->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
            }
        // dd($transactions->toSql());

        // $transactions = $combine_transactions->orderBy('deposit_date')->paginate(50);
        $db_records = $combine_transactions->orderBy('deposit_date')->get();

		$total_credit = $total_debit = $running_balance = 0;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			$key++;
	        $records[$key]['Description'] = $record->remarks;
	        $records[$key]['Cheque/DD Number'] = $record->cheque_dd_number;
	        $records[$key]['Cheque/DD Date'] = $record->cheque_dd_date;
	        if($record->cr_dr == 'cr'){
	        	$records[$key]['Credit'] = $record->amount?$record->amount:'0.00';
	        	$records[$key]['Debit'] = '0.00';
	        	$running_balance += $record->amount;
	        	$total_credit += $record->amount;
	        }else{
	        	$records[$key]['Credit'] = '0.00';
	        	$records[$key]['Debit'] = $record->amount?$record->amount:'0.00';
	        	$running_balance -= $record->amount;
				$total_debit += $record->amount;
	        }
	        // dump($running_balance);
	        $records[$key]['Running Balnace'] = number_format($running_balance,2);
	        $records[$key]['Credit/Debit Date'] = $record->deposit_date;
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Bank Transaction Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
      	$records[] = ['Total Credit',number_format($total_credit,2)];
      	$records[] = ['Total Debit',number_format($total_debit,2)];
        return collect($records);
    }
}

