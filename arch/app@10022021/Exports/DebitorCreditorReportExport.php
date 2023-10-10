<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class DebitorCreditorReportExport implements FromCollection
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
    	$records[] = ['Client Code', 'Client Name', 'Debit','Credit'];
    	
		$transactions = CustomerTransactions::where('transaction_type', '!=', 'interest')->groupBy('customer_id');

        if($this->from_date && $this->to_date || $this->customer){
            if($this->from_date!= '' && $this->to_date!= ''){
            	$transactions->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
            }
            if($this->customer != ''){
                $transactions->whereIn('customer_id',function($q){
                    return $q->select('id')->where('code','like','%'.$this->customer.'%')->orWhere('name','like','%'.$this->customer.'%')->from('users');
                });
            }
            
        }
        $db_records = $transactions->orderByDesc('deposit_date')->get();
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			$key++;

			$records[$key]['Client Code'] = $record->customers->code;
			$records[$key]['Client Name'] = $record->customers->name;
			$records[$key]['Debit']       = '₹ '.$record->dr_sum($record->customer_id)?$record->dr_sum($record->customer_id):'0.00';
			$records[$key]['Credit']      = '₹ '.$record->cr_sum($record->customer_id)?$record->cr_sum($record->customer_id):'0.00';
			
      	}
        return collect($records);
    }
}
