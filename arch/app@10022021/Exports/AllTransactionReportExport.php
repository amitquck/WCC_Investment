<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AllTransactionReportExport implements FromCollection
{

	public $from_date = '';
	public $to_date = '';
	public $payment_type = '';

	public function __construct($request){
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
		$this->payment_type = $request->payment_type;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer/Associate Name', 'Code','Credit','Debit', 'Transaction Type', 'Payment Mode', 'Bank Name', 'Cheque/DD Number', 'Deposit Date/Withdrawl Date', 'Created At'];
    	$Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
	            if($this->from_date && $this->to_date || $this->payment_type != ''){
	                if($this->from_date && $this->from_date!= '' && $this->to_date && $this->to_date!= ''){
	                	return $q->where('transaction_type', '!=', 'commission')->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
	                }
	                if($this->payment_type && $this->payment_type == 'cash' || $this->payment_type == 'cheque' || $this->payment_type == 'dd' || $this->payment_type == 'NEFT' || $this->payment_type == 'RTGS'){
	                // dd('payment_type');
	                    return $q->where('transaction_type', '!=', 'commission')->where('payment_type', $this->payment_type);
	                }
	            }else{
	                return $q->where('transaction_type', '!=', 'commission');
	            }
	        });
		$transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
		            return $q->where('transaction_type', '!=', 'interest');
	        });
		$combine_transactions = $transactions->union($Associatetransactions);
        if($this->from_date && $this->to_date || $this->payment_type != ''){
            if($this->from_date!= '' && $this->to_date!= ''){
            	$combine_transactions->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
            }
            if($this->payment_type!= '' && $this->payment_type == 'cash' || $this->payment_type == 'cheque' || $this->payment_type == 'dd' || $this->payment_type == 'NEFT' || $this->payment_type == 'RTGS'){
            // dd('payment_type');
                $combine_transactions->where('payment_type', $this->payment_type);
            }
        }
        $db_records = $combine_transactions->orderByDesc('deposit_date')->get();

		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			$key++;
			if($record->associate_id){
				$records[$key]['Customer/Associate Name'] = $record->associate->name;
				$records[$key]['Code'] = $record->associate->code;
			}else{
				$records[$key]['Customer/Associate Name'] = $record->customers->name;
				$records[$key]['Code'] = $record->customers->code;
			}
	        if($record->transaction_type == 'deposit'){
	        	$records[$key]['Credit'] = '₹ '.$record->amount;
	        }else{
	        	$records[$key]['Credit'] = '₹ '.'0.00';

	        }
	        if($record->transaction_type == 'withdraw'){
	        	$records[$key]['Debit'] = '₹ '.$record->amount;
	        }else{
	        	$records[$key]['Debit'] = '₹ '.'0.00';

	        }
	        $records[$key]['Transaction Type'] = $record->transaction_type;
	        $records[$key]['Payment Mode'] = $record->payment_type;
	        $records[$key]['Bank Details'] = ($record->payment_type != 'cash')?$record->bankname->bank_name:'Cash Payment';
	        $records[$key]['Cheque/DD Number'] = $record->cheque_dd_number?$record->cheque_dd_number:'N/A';
	        $records[$key]['Deposit Date/Withdrawl Date'] = $record->deposit_date;
	        $records[$key]['Created At'] = $record->created_at;
      	}
        return collect($records);
    }
}
