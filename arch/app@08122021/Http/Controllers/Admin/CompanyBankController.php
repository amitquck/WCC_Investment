<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\CompanyBank;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\BankTransaction;
use App\Model\ActivityLog;
use Auth;
use Redirect;
use DB;
use Carbon\Carbon;
use App\User;
use App\Exports\BankTransactionReportExport;
use App\Exports\CompanyBankReportExport;
use Maatwebsite\Excel\Facades\Excel;

// use App\Model\CustomerService;
class CompanyBankController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $banks = CompanyBank::whereStatus(1)->paginate(20);
        // dd($banks);
        return view('admin.companybank.index',compact('banks'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user_id = auth()->user()->id;
        $this->validate($request,[
            'bank_name' => 'required',
            // 'amount' => 'required',
        ]);
        $bank = new CompanyBank;
        $bank->bank_name = $request->bank_name;
        $bank->amount = $request->amount?$request->amount:'0.00';
        if($request->deposit_date != null){
            $bank->deposit_date = date('Y-m-d',strtotime($request->deposit_date));
        }else{
            $bank->deposit_date = NULL;
        }
        $bank->status = 1;
        $bank->created_by = $user_id;
        $bank->save();

        $txnEntry = new BankTransaction;
        $txnEntry->user_id = auth()->user()->id;
        $txnEntry->amount = $request->amount?$request->amount:'0.00';
        $txnEntry->cr_dr = 'cr';
        $txnEntry->payment_type = 'cash';
        if($request->deposit_date != null){
            $txnEntry->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
        }else{
            $txnEntry->transaction_date = NULL;
        }
        $txnEntry->bank_id = $bank->id;
        $txnEntry->respective_table_id = $bank->id;
        $txnEntry->respective_table_name = 'company_banks';
        $txnEntry->created_by = auth()->user()->id;
        $txnEntry->save();


        $activity_log = new ActivityLog;
        $activity_log->created_by = $user_id;
        $activity_log->statement = $request->bank_name.' Added With Opening Balance Rs. '.$request->amount.' Since '. date('d-m-Y');
        $activity_log->action_type = 'Add Bank';
        $activity_log->save();
        return redirect('admin/company_bank')->with('success','Company Bank Name Added Successfully!');
    }


    public function edit(Request $request,$id)
    {
        // dd($id);
        $bank = CompanyBank::where('id',decrypt($id))->first();
        return view('admin.companybank.edit',compact('bank'));
    }

    public function update(Request $request,$id)
    {
        // dd($id);
        $bank = CompanyBank::where('id',decrypt($id))->first();
         $this->validate($request,[
            'bank_name' => 'required',
            // 'amount' => 'required',
        ]);
        $bank->bank_name = $request->bank_name;
        $bank->amount = $request->amount;
        if($request->deposit_date != null){
            $bank->deposit_date = date('Y-m-d',strtotime($request->deposit_date));
        }else{
            $bank->deposit_date = 'NULL';
        }
        $bank->status = 1;
        $bank->updated_by = auth()->user()->id;
        // $bank->save();
        // dump(decrypt($id));
        // dump($bank->id);
        $bnkTxn = BankTransaction::where('bank_id',decrypt($id))->where('respective_table_id',$bank->id)->where('respective_table_name','company_banks')->first();
        $bnkTxn->amount = $request->amount;
        if($request->deposit_date != null){
            $bnkTxn->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
        }else{
            $bnkTxn->transaction_date = 'NULL';
        }
        $bnkTxn->save();

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->statement = $request->bank_name.' Edit With Opening Balance Rs. '.$request->amount.' Since '. date('d-m-Y');
        $activity_log->action_type = 'Edit Bank';
        $activity_log->save();
        return redirect('admin/company_bank')->with('success','Company Bank Name Updated Successfully!');
    }





    // public function perBankReport@bkpforcombinecustomer_nd_associate($bankId)
    // {
    //     $bank = CompanyBank::where('id',decrypt($bankId))->first();
    //     $customertransaction = CustomerTransactions::where('bank_id',decrypt($bankId))->get();
    //     if($customertransaction == ''){
    //         return redirect()->back()->with('error','There is no transaction in this bank.');
    //     }
    //     $associatetransaction = AssociateTransactions::where('bank_id',decrypt($bankId))->get();
    //     if($associatetransaction == ''){
    //         return redirect()->back()->with('error','There is no transaction in this bank.');
    //     }
    //     return view('admin.companybank.bankreports',compact('customertransaction','associatetransaction','bank'));
    // }

    // public function perBankReport(Request $request,$bankId)@bkpForCust&&AssTable
    // {
    //     // dd($request->all());
    //     $bank = CompanyBank::where('id',decrypt($bankId))->first();
    //     $Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($bankId){
    //         return $q->where('transaction_type','withdraw')->where('bank_id',decrypt($bankId));
    //     });
    //     if($request->has('from_date') && $request->has('to_date')){
    //         if($request->has('from_date') && $request->input('from_date')!= '' && $request->has('to_date') && $request->input('to_date')!= ''){
    //         // dd($request->from_date);
    //         $Associatetransactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
    //         }
    //     }
    //     $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($bankId){
    //         return $q->where('transaction_type', '!=', 'interest')->where('bank_id',decrypt($bankId));
    //     });

    //     $combine_transactions = $transactions->union($Associatetransactions);


    //     if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
    //         if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
    //             // dd('zdvzv');
    //             $combine_transactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
    //         }
    //     }
    //     // dd($transactions->toSql());

    //     $transactions = $combine_transactions->orderBy('deposit_date')->paginate(50);
    //     return view('admin.companybank.bankreports',compact('transactions','bank'));
    // }


    public function perBankReport(Request $request,$bankId)
    {
        // dd($request->all());
        // dump(decrypt($bankId));dump($request->from_date);dump($request->to_date);
        $banks = CompanyBank::whereStatus(1)->paginate(20);
        $bank = CompanyBank::where('id',decrypt($bankId))->first();
        if($request->has('from_date') && $request->input('from_date')!= '' && $request->has('to_date') && $request->input('to_date')!= ''){
            $transactions = BankTransaction::where('bank_id',decrypt($bankId))->whereBetween('transaction_date', [date('Y-m-j',strtotime($request->from_date)),date('Y-m-j',strtotime($request->to_date))])
            ->paginate(100);
            // ->toSql();
            // dd($transactions);
        }else{
            $transactions = BankTransaction::where('bank_id',decrypt($bankId))->paginate(100);
        }
        return view('admin.companybank.bankreports',compact('transactions','bank','banks'));
    }

    public function excel_bank_transactions(Request $request)
    {
        // dd($request->all());
        if($request->from_date){
            $bank = CompanyBank::where('id',$request->bank_id)->first();
            return Excel::download(new BankTransactionReportExport($request), $bank->bank_name.' bank_transaction.xlsx');
        }else{
            $bank = CompanyBank::where('id',$request->bank_id)->first();
            // $transactions = BankTransaction::where('bank_id',$request->bank_id)->get();
            return Excel::download(new BankTransactionReportExport($request), $bank->bank_name.' bank_transaction.xlsx');
        }

    }

    public function excel_company_bank(Request $request)
    {
        return Excel::download(new CompanyBankReportExport($request),'bank_transaction.xlsx');
    }

    public function getFromBankCash(Request $request)
    {
        $amount = BankTransaction::select(DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN cr_dr = "dr" THEN -1 END )) AS balance'))->whereBankId($request->from_bank_id)->first();
        $amount = $amount->balance?$amount->balance:'0.00';
        return $amount;
    }

    public function getToBankCash(Request $request)
    {
        $amount = BankTransaction::select(DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN cr_dr = "dr" THEN -1 END )) AS balance'))->whereBankId($request->to_bank_id)->first();
        $amount = $amount->balance?$amount->balance:'0.00';
        return $amount;
    }

    public function b2bTransaction(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'trans_amt' => 'required',
            'payment_type' => 'required',
            'transaction_date' => 'required',
            'from_bank' => 'required',
            'to_bank' => 'required',
        ]);
        if($request->from_bank){
            $b2btxn = new BankTransaction;
            $b2btxn->user_id = auth()->user()->id;
            $b2btxn->amount = $request->trans_amt;
            $b2btxn->cr_dr = 'dr';
            $b2btxn->payment_type = $request->payment_type;
            $b2btxn->transaction_date = date('Y-m-d',strtotime($request->transaction_date));
            $b2btxn->cheque_dd_date = $request->date;
            $b2btxn->bank_id = $request->from_bank;
            $b2btxn->cheque_dd_number = $request->cheque_dd_number;
            $b2btxn->respective_table_id = $request->to_bank;
            $b2btxn->respective_table_name = 'bank_transactions';
            $b2btxn->remarks = $request->remarks;
            $b2btxn->created_by = auth()->user()->id;
            $b2btxn->save();
        }
        if($request->to_bank){
            // dd($b2btxn->id);
            $b2btxns = new BankTransaction;
            $b2btxns->user_id = auth()->user()->id;
            $b2btxns->amount = $request->trans_amt;
            $b2btxns->cr_dr = 'cr';
            $b2btxns->payment_type = $request->payment_type;
            $b2btxns->transaction_date = date('Y-m-d',strtotime($request->transaction_date));
            $b2btxns->cheque_dd_date = $request->date;
            $b2btxns->bank_id = $request->to_bank;
            $b2btxns->cheque_dd_number = $request->cheque_dd_number;
            $b2btxns->respective_table_id = $request->from_bank;
            $b2btxns->respective_table_name = 'bank_transactions';
            $b2btxns->bank_transfer_id = $b2btxn->id;
            $b2btxns->remarks = $request->remarks;
            $b2btxns->created_by = auth()->user()->id;
            $b2btxns->save();
        }

        $bkTx = BankTransaction::orderByDesc('id')->skip(1)->take(1)->first();
        $bkTx->bank_transfer_id = $b2btxns->id;
        $bkTx->save();

        return back()->withSuccess('Transaction has been successfully.');
    }

    public function addDepositBankTxns(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'amount' => 'required',
            'payment_type' => 'required',
            'deposit_date' => 'required',
            'bank_id' => 'required',
        ]);
        $addDepp = new BankTransaction;
        $addDepp->user_id = auth()->user()->id;
        $addDepp->amount = $request->amount;
        $addDepp->cr_dr = 'cr';
        $addDepp->payment_type = $request->payment_type;
        $addDepp->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
        $addDepp->cheque_dd_date = $request->date?date('Y-m-d',strtotime($request->date)):NULL;
        $addDepp->bank_id = $request->bank_id;
        $addDepp->cheque_dd_number = $request->cheque_dd_number;
        $addDepp->remarks = 'Direct deposit - '.$request->remarks;
        $addDepp->created_by = auth()->user()->id;
        $addDepp->save();
        return back()->withSuccess('Transaction has been successfully.');
    }




    public function editDepositBankTxns(Request $request)
    {
        // dd($request->all());
        $addDepp = BankTransaction::where('id',$request->table_id)->first();
        if($addDepp){
            $addDepp->user_id = auth()->user()->id;
            $addDepp->amount = $request->amount;
            $addDepp->cr_dr = 'cr';
            $addDepp->payment_type = $request->payment_type;
            $addDepp->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
            $addDepp->cheque_dd_date = $request->date?date('Y-m-d',strtotime($request->date)):NULL;
            $addDepp->bank_id = $request->bank_id;
            $addDepp->cheque_dd_number = $request->cheque_dd_number;
            $addDepp->remarks = 'Direct deposit - '.$request->remarks;
            $addDepp->created_by = auth()->user()->id;
            $addDepp->save();
            return back()->withSuccess('Transaction has been successfully.');
        }
        return back()->withError('Not Found.');
    }



    public function addDeductionBankTxns(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'amount' => 'required',
            'payment_type' => 'required',
            'deposit_date' => 'required',
            'bank_id' => 'required',
        ]);
        $addDepp = new BankTransaction;
        $addDepp->user_id = auth()->user()->id;
        $addDepp->amount = $request->amount;
        $addDepp->cr_dr = 'dr';
        $addDepp->payment_type = $request->payment_type;
        $addDepp->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
        $addDepp->cheque_dd_date = $request->date?date('Y-m-d',strtotime($request->date)):NULL;
        $addDepp->bank_id = $request->bank_id;
        $addDepp->cheque_dd_number = $request->cheque_dd_number;
        $addDepp->remarks = 'Direct deduction - '.$request->remarks;
        $addDepp->created_by = auth()->user()->id;
        $addDepp->save();
        return back()->withSuccess('Transaction has been successfully.');
    }


    public function editDeductionBankTxns(Request $request)
    {
        // dd($request->all());
        $addDepp = BankTransaction::where('id',$request->table_id)->first();
        if($addDepp){
            $addDepp->user_id = auth()->user()->id;
            $addDepp->amount = $request->amount;
            $addDepp->cr_dr = 'dr';
            $addDepp->payment_type = $request->payment_type;
            $addDepp->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
            $addDepp->cheque_dd_date = $request->date?date('Y-m-d',strtotime($request->date)):NULL;
            $addDepp->bank_id = $request->bank_id;
            $addDepp->cheque_dd_number = $request->cheque_dd_number;
            $addDepp->remarks = 'Direct deduction - '.$request->remarks;
            $addDepp->created_by = auth()->user()->id;
            $addDepp->save();
            return back()->withSuccess('Transaction has been successfully.');
        }
        return back()->withError('Not Found.');
    }



    public function bank_delete_confirm(Request $request)
    {
        $bank = CompanyBank::whereId($request->id)->first();
        return view('admin.companybank.destroy',compact('bank'));
    }

    public function bankDelete(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'password'=>'required',
        ]);

        $users = User::whereLoginType('superadmin')->first();
        $user = Hash::check($request->password,$users->password);

        if($user){
            $bank = CompanyBank::where('id',$request->bank_id)->first();
            if($bank){
                $cust_txn = CustomerTransactions::where('bank_id',$request->bank_id)->first();
                $ass_txn = AssociateTransactions::where('bank_id',$request->bank_id)->first();
                if($cust_txn != NULL || $ass_txn != NULL){
                    return back()->with('error',"Transaction has been done from this bank. So this bank can't be deleted.");
                }
                $activity_log = new ActivityLog;
                $activity_log->created_by = auth()->user()->id;
                $activity_log->user_id = auth()->user()->id;
                $activity_log->statement = $bank->name .' Deleted Since '.date('d-m-Y');
                $activity_log->action_type = 'Delete';
                $activity_log->save();

                CompanyBank::where('id',$request->bank_id)->delete();
                BankTransaction::where('bank_id',$request->bank_id)->delete();

                return back()->with('success','Bank Deleted Successfully!');
            }else{
                return back()->with('error','Bank Not Found.');
            }
        }else{
            return redirect()->back()->with('error','Password is Wrong!');
        }
    }

    public function edit_b2bTransaction(Request $request)
    {
        if($request->table_id != ''){
            $bankTxn = BankTransaction::whereId($request->table_id)->first();
            $bankTxn->amount = $request->trans_amt ;
            $bankTxn->payment_type = $request->payment_type ;
            $bankTxn->transaction_date = $request->transaction_date ;
            $bankTxn->cheque_dd_date = $request->date ;
            $bankTxn->bank_id = $request->from_bank ;
            $bankTxn->cheque_dd_number = $request->cheque_dd_number ;
            $bankTxn->respective_table_id = $request->to_bank ;
            $bankTxn->remarks = $request->remarks ;
            $bankTxn->save();
            return back()->withSuccess('Record updated successfully.');
        }
        return back()->withError('Nothing happened.');
    }

    public function b2bt_delete_confirm(Request $request){
        $data = BankTransaction::where('id',$request->tableId)->where('bank_id',$request->bankId)->where('respective_table_id',$request->respTableId)->where('bank_transfer_id',$request->bankTranId)->first();
        return view('admin.companybank.delete_record',compact('data'));
    }

    public function deleteBankRecords(Request $request){
        $this->validate($request,[
            'password'=>'required',
        ]);

        $users = User::whereLoginType('superadmin')->first();
        $user = Hash::check($request->password,$users->password);

        if($user){
            $data = BankTransaction::where('id',$request->table_id)->first();
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            $activity_log->user_id = $data->user_id;
            $activity_log->statement = 'Bank to bank transaction deleted Since '.date('d-m-Y');
            $activity_log->action_type = 'Delete';
            $activity_log->save();

            BankTransaction::where('id',$request->table_id)->delete();
            BankTransaction::where('id',$request->second_table_id)->delete();

            return back()->with('success','Bank Records Deleted Successfully!');
        }else{
            return redirect()->back()->with('error','Password is Wrong!');

        }
    }

    public function directdepded_delete_confirm(Request $request){
        $data = BankTransaction::where('id',$request->tableId)->where('bank_id',$request->bankId)->first();
        return view('admin.companybank.delete_deposit_deduction',compact('data'));
    }

    public function deleteDepositDeductionRecords(Request $request){
        $this->validate($request,[
            'password'=>'required',
        ]);

        $users = User::whereLoginType('superadmin')->first();
        $user = Hash::check($request->password,$users->password);

        if($user){
            $data = BankTransaction::where('id',$request->table_id)->first();
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            $activity_log->user_id = $data->user_id;
            $activity_log->statement = 'Direct Transaction deleted Since '.date('d-m-Y');
            $activity_log->action_type = 'Delete';
            $activity_log->save();

            BankTransaction::where('id',$request->table_id)->delete();

            return back()->with('success','Bank Records Deleted Successfully!');
        }else{
            return redirect()->back()->with('error','Password is Wrong!');

        }
    }
}
