<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\CompanyBank;
use App\Model\BankTransaction;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Carbon\Carbon;

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
    	$records[] = ['User','Code','Name','Description', 'Credit', 'Debit', 'Running Balnace', 'Credit/Debit Date', 'Cheque/DD Number','Cheque/DD Date','Created At'];
        $bank = CompanyBank::where('id',$this->bank_id)->first();
    	$transactions = BankTransaction::where('bank_id',$this->bank_id);
        $db_records = $transactions->orderByDesc('transaction_date')->get();

		$total_credit = $total_debit = $running_balance = 0;
        if($db_records->count()>0){
            $total_credit = $total_debit = $running_balance = 0;
            foreach($db_records as $key => $transaction){
                $key++;
                if($transaction->respective_table_name != 'customer_transactions' && $transaction->respective_table_name != 'associate_transactions'){
                    $records[$key]['User'] = 'N/A';
                    $records[$key]['Code'] = 'N/A';
                    $records[$key]['Name'] = 'N/A';
                }elseif($transaction->respective_table_name == 'customer_transactions' || $transaction->respective_table_name == 'associate_transactions'){
                    $user = getUserCodeName($transaction->respective_table_id,$transaction->respective_table_name);
                    if($user){
                        $records[$key]['User'] = ucwords($user->login_type);
                            if($user->login_type == 'customer'){
                                $records[$key]['Code'] = $user->code;
                            }elseif($user->login_type == 'associate'){
                                $records[$key]['Code'] = $user->code;
                            }
                        $records[$key]['Name'] = $user->name;
                    }else{
                        $records[$key]['User'] = 'N/A';
                        $records[$key]['Code'] = 'N/A';
                        $records[$key]['Name'] = 'N/A';
                    }
                }
                if($transaction->respective_table_name == 'company_banks'){
                    $records[$key]['Description'] = 'Bank openning balance';
                }elseif($transaction->respective_table_name == 'bank_transactions'){
                    $records[$key]['Description'] = 'Bank to bank transaction'.'<br>'. ( $bank->bank_name .' - '. getBank($transaction->respective_table_id));
                }else{
                    $records[$key]['Description'] = ucfirst($transaction->remarks?$transaction->remarks:'N/A');
                }

                if($transaction->cr_dr == 'cr'){
                    $total_credit += $transaction->amount;
                    $records[$key]['Credit'] = $transaction->amount;
                    $records[$key]['Debit'] = '0.00';
                    $running_balance += $transaction->amount;
                }else{
                    $total_debit += $transaction->amount;
                    $records[$key]['Credit'] = '0.00';
                    $records[$key]['Debit'] = $transaction->amount;
                    $running_balance -= $transaction->amount;
                }

                $records[$key]['Running Balnace'] = number_format($running_balance,2);
                $records[$key]['Credit/Debit Date'] = date('j-m-Y',strtotime($transaction->transaction_date));
                $records[$key]['Cheque/DD Number'] = $transaction->cheque_dd_number?$transaction->cheque_dd_number:'cash transaction';
                $records[$key]['Cheque/DD Date'] = $transaction->cheque_dd_date?date('j-m-Y',strtotime($transaction->cheque_dd_date)):'N/A';
                $records[$key]['Created At'] = date('j-m-Y',strtotime($transaction->created_at));
            }
        }

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'Bank Transaction Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}

