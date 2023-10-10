<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\ActivityLog;
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
    	// $records = [];
        if($this->from_date != null && $this->to_date != null){
            $from_date = date('Y-m-d',strtotime($this->from_date));
            $to_date = date('Y-m-d',strtotime($this->to_date));
        }else{
            $from_date = $to_date = null;
        }

        $records[0] = ['Debitor/Creditor List As on Date '.$this->to_date];
    	$records[1] = ['Client Code', 'Client Name', 'Debit','Credit'];

		$transactions = CustomerTransactions::where('transaction_type', '!=', 'interest')->groupBy('customer_id');

        if($this->from_date && $this->to_date || $this->customer){
            if($this->from_date!= '' && $this->to_date!= ''){
            	$transactions->whereBetween('deposit_date', [$from_date, $to_date]);
            }
            if($this->customer != ''){
                $transactions->whereIn('customer_id',function($q){
                    return $q->select('id')->where('code','like','%'.$this->customer.'%')->orWhere('name','like','%'.$this->customer.'%')->from('users');
                });
            }

        }
        $db_records = $transactions->orderByDesc('deposit_date')->get();
        $i = 3;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
			if($record->customers != NULL){
                $i = $i+1;

                if($record->customer_current_balance($record->customer_id) > 0){
                    $records[$i]['Client Code'] = $record->customers->code;
                    $records[$i]['Client Name'] = $record->customers->name;
                }else{
                    if($record->customer_current_balance($record->customer_id) < 0){
                        $records[$i]['Client Code'] = $record->customers->code;
                        $records[$i]['Client Name'] = $record->customers->name;
                    }
                }
                if($from_date != null && $to_date != null){
                    if($record->customer_current_balance($record->customer_id,$from_date,$to_date) < 0){
                        $records[$i]['Debit'] = $record->customer_current_balance($record->customer_id,$from_date,$to_date);
                        $records[$i]['Credit'] = '0.00';
                    }else{
                        $records[$i]['Debit'] = '0.00';
                        $records[$i]['Credit'] = $record->customer_current_balance($record->customer_id,$from_date,$to_date);

                    }
                }else{
                    if($record->customer_current_balance($record->customer_id) < 0){
                        $records[$i]['Debit'] = $record->customer_current_balance($record->customer_id);
                        $records[$i]['Credit'] = '0.00';
                    }else{
                        if($record->customer_current_balance($record->customer_id) > 0){
                            $records[$i]['Debit'] = '0.00';
                            $records[$i]['Credit'] = $record->customer_current_balance($record->customer_id);
                        }
                    }
                }
                $i++;
            }
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Debitor Creditor Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
