<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\CompanyBank;
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
	        	$records[$key]['Credit'] = '₹ '.$record->amount?$record->amount:'0.00';
	        	$records[$key]['Debit'] = '₹ 0.00';
	        	$running_balance += $record->amount;
	        	$total_credit += $record->amount;
	        }else{
	        	$records[$key]['Credit'] = '₹ 0.00';
	        	$records[$key]['Debit'] = '₹ '.$record->amount?$record->amount:'0.00';
	        	$running_balance -= $record->amount;
				$total_debit += $record->amount;
	        }
	        // dump($running_balance);
	        $records[$key]['Running Balnace'] = number_format($running_balance,2);
	        $records[$key]['Credit/Debit Date'] = $record->deposit_date;
      	}
      	$records[] = ['Total Credit','₹ '.number_format($total_credit,2)];
      	$records[] = ['Total Debit','₹ '.number_format($total_debit,2)];
        return collect($records);
    }
}

/* @if($transactions->count()>0)
          @php $total_credit = $total_debit = $running_balance = 0; @endphp
            @foreach($transactions as $key => $transaction)
                <tr>
                   @if($transaction->cr_dr == 'cr')
                    @php
                      $total_credit += $transaction->amount;
                    @endphp
                    @else
                    @php
                      $total_debit += $transaction->amount;
                    @endphp
                   @endif
                  <td>{{$key+1}}.</td>
                  <td>{{ucfirst($transaction->remarks?$transaction->remarks:'N/A')}}</td>
                  <td>{{$transaction->cheque_dd_number}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-M-Y')}}</td>
                  <td class="text-success">@if($transaction->cr_dr == 'cr')₹  {{$transaction->amount}} @endif</td>
                  <td class="text-danger">@if($transaction->cr_dr == 'dr')₹  {{$transaction->amount}} @endif</td>
                  @if($transaction->cr_dr == 'cr')
                    @php
                        $running_balance += $transaction->amount;
                    @endphp
                      @else
                    @php
                        $running_balance -= $transaction->amount;
                    @endphp
                  @endif

                  <td class="text-primary">₹ {{number_format($running_balance,2)}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-M-Y')}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-M-Y')}}</td>
                </tr>
            @endforeach
                <tfoot>
                  <tr>
                    <td colspan="6"><span class="text-success">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Pending :  ₹ {{$total_credit - $total_debit}}</span>    &nbsp;  &nbsp;   ||  &nbsp;  &nbsp;  <span class="text-primary">Total Debit :  ₹ {{$total_debit}}</span>   </td>
                  </tr>
                </tfoot>
          @else
            <tr>
              <td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif*/