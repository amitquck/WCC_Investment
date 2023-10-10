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
    public $month = '';
    public $year = '';
    public $paymentType = '';

	public function __construct($date,$month,$year,$paymentType){
		$this->date = $date;
        $this->paymentType = $paymentType;
        $this->month = $month;
        $this->year = $year;
		// dd($this->date);
	}
    /**
    * @param Collection $collection
    */
    public function collection($paths)//Collection $collection,
    {
    	// dd($collection);

    // 0 => "CHANDRA PRAKASH PAL"
    // 1 => "914010036991775"
    // 2 => "UTIB0001471"
    // 3 => 20
    // 4 => "FKNPR101"

        $records = 0;
        foreach($paths as $key => $path){
            // dd($this->paymentType);
            if($key != 0){
            	if($this->paymentType == 'bank'){
                    $user = User::where('code',$path[4])->first();
                    if($user){
                        $txn = new CustomerTransactions;
                        $txn->customer_id = $user->id;
                        $txn->amount = $path[3];
                        $txn->cr_dr = 'dr';
                        $txn->payment_type = 'cash'; 
                        $txn->transaction_type = 'withdraw'; 
                        $txn->deposit_date = date('Y-m-d',strtotime($this->date));
                        $txn->status = 1;
                        $txn->month_year_import_excel = $this->month.'-'.$this->year;
                        $txn->payment_type_import_excel = 'bank';
                        $txn->import_excel_date = date('Y-m-d',strtotime($this->date));
                        if($txn->save()){
                            $activity_log = new ActivityLog;
                            $activity_log->created_by = auth()->user()->id;  
                            $activity_log->user_id = $user->id;    
                            $activity_log->statement = $user->name.' ('.$user->code.') Add Transaction Using Import Excel Rs. '. $path[3].' Since At '.date('d-m-Y');  
                            $activity_log->action_type = 'Import Excel';
                            $activity_log->save();
                            $records++;
                        }
                    }
                }elseif($this->paymentType == 'cash'){
                    $user = User::where('code',$path[1])->first();
                    if($user){
                        $txn = new CustomerTransactions;
                        $txn->customer_id = $user->id;
                        $txn->amount = $path[2];
                        $txn->cr_dr = 'dr';
                        $txn->payment_type = 'cash'; 
                        $txn->transaction_type = 'withdraw'; 
                        $txn->deposit_date = date('Y-m-d',strtotime($this->date));
                        $txn->status = 1;
                        $txn->month_year_import_excel = $this->month.'-'.$this->year;
                        $txn->payment_type_import_excel = 'cash';
                        $txn->import_excel_date = date('Y-m-d',strtotime($this->date));
                        if($txn->save()){
                            $activity_log = new ActivityLog;
                            $activity_log->created_by = auth()->user()->id;  
                            $activity_log->user_id = $user->id;    
                            $activity_log->statement = $user->name.' ('.$user->code.') Add Transaction Using Import Excel Rs. '. $path[2].' Since At '.date('
                                d-m-Y');  
                            $activity_log->action_type = 'Import Excel';
                            $activity_log->save();
                            $records++;
                        }
                    }
                }
            }
        }
        return $records;
    }
}
