<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\CompanyBank;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;

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
        $banks = CompanyBank::paginate(10);
        // dd($banks);
        return view('admin.companybank.index',compact('banks'));
    }

    public function store(Request $request)
    {
        $user_id = auth()->user()->id;
        $this->validate($request,[
            'bank_name' => 'required',
            'amount' => 'required',
        ]);
        $bank = new CompanyBank;
        $bank->bank_name = $request->bank_name;
        $bank->amount = $request->amount;
        $bank->status = 1;
        $bank->created_by = $user_id;
        $bank->save();
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
            'amount' => 'required',
        ]);
        $bank->bank_name = $request->bank_name;
        $bank->amount = $request->amount;
        $bank->status = 1;
        $bank->updated_by = auth()->user()->id;
        $bank->save();
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

        public function perBankReport($bankId)
        {
            // dd(decrypt($bankId));
            $bank = CompanyBank::where('id',decrypt($bankId))->first();
            $Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($bankId){
                return $q->where('transaction_type','withdraw')->where('bank_id',decrypt($bankId));
            });
            $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($bankId){
                return $q->where('transaction_type', '!=', 'interest')->where('bank_id',decrypt($bankId));
            });
            // dd($transactions->toSql());

            $transactions = $transactions->union($Associatetransactions)->orderByDesc('deposit_date')->paginate(50);
            return view('admin.companybank.bankreports',compact('transactions','bank'));
        }

}
