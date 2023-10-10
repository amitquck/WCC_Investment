<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class CustomerTransactionReportExport implements FromCollection
{

	public $from_date = '';
	public $to_date = '';
	public $customer = '';

	public function __construct($request){
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
		$this->customer = $request->customer;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Client Code', 'Client Name','Credit', 'Debit', 'Running Balnace', 'Credit/Debit Date'];
    	
		if($this->from_date && $this->to_date && $this->customer){
            $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
                    return $q->where('transaction_type', '!=', 'interest')->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
                });

            
            $transactions->whereIn('customer_id',function($q){
                return $q->select('id')->where('code','like','%'.$this->customer.'%')->from('users');
            });
            
        }elseif($this->customer != ''){
            $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where('transaction_type', '!=', 'interest');

            
            $transactions->whereIn('customer_id',function($q){
                return $q->select('id')->where('code','like','%'.$this->customer.'%')->from('users');
            });
            
        }
        $db_records = $transactions->orderBy('deposit_date')->get();
        $total_credit = $total_debit = $running_balance = 0;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			$key++;

			$records[$key]['Client Code'] = $record->customers->code;
			$records[$key]['Client Name'] = $record->customers->name;
			if($record->transaction_type == 'deposit'){
	        	$records[$key]['Credit'] = $record->amount;
	        	$running_balance += $record->amount;
	        	$total_credit += $record->amount;
	        }else{
	        	$records[$key]['Credit'] = '0.00';
	        }
	        if($record->transaction_type == 'withdraw'){
	        	$records[$key]['Debit'] = $record->amount;
	        	$running_balance -= $record->amount;
                $total_debit += $record->amount;
	        }else{
	        	$records[$key]['Debit'] = '0.00';

	        }
	        $records[$key]['Running Balnace'] = number_format($running_balance,2);
	        $records[$key]['Credit/Debit Date'] = $record->deposit_date;
      	}
      	$records[] = [''];
      	$records[] = ['Total Credit',number_format($total_credit,2),'Total Debit',number_format($total_debit,2)];
        return collect($records);
    }
}
