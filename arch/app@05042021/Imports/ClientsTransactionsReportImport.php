<?php

namespace App\Imports;
use File;
use App\User;
use App\Model\CustomerTransactions;
use App\Model\ActivityLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientsTransactionsReportImport implements ToCollection
{



	public $date = '';

	public function __construct($date){
		$this->date = $date;
		// dd($this->date);
	}
    /**
    * @param Collection $collection
    */
    public function collection($paths)//Collection $collection,
    {
    	// dd($this->date);
    	// dd($collection);
        $records = 0;
        foreach($paths as $key => $path){
        	$user = User::where('code',$path[0])->first();
        	$txn = new CustomerTransactions;
        	$txn->customer_id = $user->id;
        	$txn->amount = $path[2];
        	$txn->cr_dr = 'dr';
        	$txn->payment_type = 'cash'; 
        	$txn->transaction_type = 'withdraw'; 
        	$txn->deposit_date = date('Y-m-d',strtotime($this->date));
        	$txn->status = 1;
        	if($txn->save()){
                $activity_log = new ActivityLog;
                $activity_log->created_by = auth()->user()->id;  
                $activity_log->user_id = $user->id;    
                $activity_log->statement = $user->name.' ('.$user->code.') Add Transaction Using Import Excel Rs. '. $path[2].' Since At '.date('d-m-Y');  
                $activity_log->action_type = 'Import Excel';
                $activity_log->save();
                $records++;
            }

              

        }
        return $records;
    }
}
