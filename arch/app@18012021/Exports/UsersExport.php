<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class UsersExport implements FromCollection
{

	public $from_date = '';
	public $to_date = '';
	public $id = '';

	public function __construct($request){
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
		$this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Customer Name','Deposit Amount','Transaction Type','Payment Mode','Bank Details','Cheque/DD Number','Deposit Date','Created At'];
    	$customer_deposits = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit');

        if($this->id > 0){
            $customer_deposits->where('customer_id',$this->id);
        }else if($this->from_date && $this->to_date){
            $customer_deposits->whereBetween('deposit_date', [$this->from_date, $this->to_date]);
        }
		$db_records = $customer_deposits->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Customer Name'] = $record->user->name;
	        $records[$key]['Deposit Amount'] = $record->amount;
	        $records[$key]['Transaction Type'] = $record->transaction_type;
	        $records[$key]['Payment Mode'] = $record->payment_type;
	        $records[$key]['Bank Details'] = ($record->payment_type != 'cash')?$record->bankname->bank_name:'Cash Payment';
	        $records[$key]['Cheque/DD Number'] = $record->cheque_dd_number?$record->cheque_dd_number:'N/A';
	        $records[$key]['Deposit Date'] = $record->deposit_date;
	        $records[$key]['Created At'] = $record->created_at;
      	}
        return collect($records);
    }
}
