<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\AssociateDetail;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\CustomerInvestment;
use App\Model\CustomerReward;
use App\Model\AssociateReward;
use App\Model\AssociateCommissionPercentage;
use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;
use App\Exports\UsersExport;
use App\Exports\AssociateWiseCustomerExport;
use App\Exports\CustomerWiseMonthlyReportExport;
use App\Exports\AllTransactionReportExport;
use Maatwebsite\Excel\Facades\Excel;


// use App\Model\CustomerService;
class ReportController extends Controller
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

    public function investment(Request $request)
    {

    	$customer_deposits = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit');

        if($request->has('id') && $request->id > 0){
            $customer_deposits->where('customer_id',$request->id);
        }else if($request->has('from_date') && $request->has('to_date')){
            $customer_deposits->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
        }
		// $id = isset($request->id)?decrypt($request->id):$request->id;
		// dd($id);
		
		$customer_deposits = $customer_deposits->paginate(20);
    	return view('admin.reports.customerInvestment.customerinvestmentreport',compact('customer_deposits'));
    }


    public function viewInvestmentDetail($id)
    {
        $customertransactions = CustomerTransactions::where('customer_id',$id)->where('cr_dr','cr')->first();
        // dd($customertransactions);
        // $customerinterest = CustomerInvestment::where('customer_id',$id)->first();
        return view('admin.reports.customerInvestment.investmentdetail',compact('customertransactions'));
    }


    // public function destroyInterestAllInvest($id)
    // {
    //     // dd(decrypt($id));
    //     $customer = CustomerInvestment::where('id',decrypt($id))->first();
    //     // dd($customer);
    //     CustomerTransactions::where('customer_investment_id',decrypt($id))->where('customer_id',$customer->customer_id)->delete();
    //     // AssociateTransactions::where('customer_id',$customer->customer_id)->delete();
    //     AssociateTransactions::where('customer_investment_id',decrypt($id))->where('customer_id',$customer->customer_id)->delete();
    //     CustomerReward::where('customer_investment_id',decrypt($id))->where('customer_id',$customer->customer_id)->delete();
    //     AssociateReward::where('customer_investment_id',decrypt($id))->where('customer_id',$customer->customer_id)->delete();
    //     AssociateCommissionPercentage::where('customer_investment_id',decrypt($id))->where('customer_id',$customer->customer_id)->delete();
    //     CustomerInvestment::where('id',decrypt($id))->delete();
    //     // CustomerDetail::where('customer_id',decrypt($id))->delete();
    //     return redirect()->back()->with('success','Customer Deleted Successfully!');

    // }
    public function getPageAssociateWiseCustomer(Request $request){
       $userss = User::where('login_type','associate');
		if($request->has('id') && $request->id != '' ){
			$userss->where('id',$request->id);
		}
		$userss = $userss->get();
        $users = [];
        $totalcommission = [];
        foreach($userss as $user){
            $users = $user->associatecommission;
            $totalcommission = $user->associatetransactions;
        }
        // dd($totalcommission);
        return view('admin.reports.associate-wise-customer-report',compact('users','userss','totalcommission'));
    }
    public function autocompleteAssociateCustomer(Request $request){
        if($request->has('term')){
            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,",", mobile) AS label'))->where('name','like','%'.$request->input('term').'%')->get();
            return response()->json($results);
        }
    }
    
    public function customerWiseMonthlyReport(Request $request){
        // dd($request);
        $user = User::whereId($request->id)->first();
        $monthlyReport = array();
        $firstdata = [];
        if($user){
             $rewards = $user->customerrewards()->groupBy(DB::Raw('CONCAT(year,month)'))->get();
            foreach($rewards as $reward){
                // dd($customer);
                $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
                $monthlyReport[$reward->year.'-'.$reward->month]['customer'] = $reward->customer;
                // dd($user->customerassociaterewards()->where('month',$reward->month)->where('year',$reward->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get());
                $monthlyReport[$reward->year.'-'.$reward->month]['commissions'] = $user->customerassociaterewards()->where('month',$reward->month)->where('year',$reward->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();
                // array_push(,$customer);
                // foreach($customer as $key => $cus){
                //       if($key == 0){
                //           $firstdata = $cus;
                //       }
                // }
            }
        }
        // dd($monthlyReport);
        $associate_reward = 'App\Model\AssociateReward'::whereCustomerId($request->id)->get();
        
        return view('admin.reports.customer-wise-monthly-report',compact('associate_reward','monthlyReport','firstdata'));
    }
    public function bkpcustomerWiseMonthlyReport(Request $request){
        // dd($request);
        $user = User::whereId($request->id)->first();
        $monthlyReport = array();
        $first = [];
        if($user){
            foreach($user->customerrewards->groupBy('month','year') as $customer){
                array_push($monthlyReport,$customer);
            }
        }
        $associate_reward = 'App\Model\AssociateReward'::whereCustomerId($request->id)->get();
        
        return view('admin.reports.customer-wise-monthly-report',compact('associate_reward','monthlyReport'));
    }
    
    public function excelExportCustomerInvestments(Request $request)
    {
        return Excel::download(new UsersExport($request), 'customer_investments.xlsx');
    }
    
    public function excel_associatewise_customer(Request $request)
    {
        return Excel::download(new AssociateWiseCustomerExport($request), 'associate_wise_customer.xlsx');
    }
    
    public function excel_customer_wise_monthly_report(Request $request)
    {
        return Excel::download(new CustomerWiseMonthlyReportExport($request), 'customer_wise_monthly_report.xlsx');
    }
    
    public function excel_all_transactions(Request $request)
    {
        return Excel::download(new AllTransactionReportExport($request), 'all_transactions.xlsx');
    }

    public function transactions(Request $request)
    {
        // dd($request->all());
        // $commissions = AssociateTransactions::where('transaction_type','commission')->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"),'associate_id')->pluck('id'); 
        // dump($commissions);
        $Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($request){
            
            if($request->has('from_date') && $request->has('to_date') || $request->has('payment_type') != ''){
                // dd('zds');
                if($request->has('from_date') && $request->input('from_date')!= '' && $request->has('to_date') && $request->input('to_date')!= ''){
                return $q->where('transaction_type', '!=', 'commission')->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
                }
                if($request->has('payment_type') && $request->input('payment_type') == 'cash' || $request->input('payment_type') == 'cheque' || $request->input('payment_type') == 'dd' || $request->input('payment_type') == 'NEFT' || $request->input('payment_type') == 'RTGS'){
                // dd('payment_type');
                    return $q->where('transaction_type', '!=', 'commission')->where('payment_type', $request->payment_type);
                }
            }else{
                return $q->where('transaction_type', '!=', 'commission');
            }
        });
        // ->orWhereIn('id',$commissions)
        // ->orderByDesc('deposit_date')
        // ->paginate(20);
        // ->get();

        // $interest = CustomerTransactions::where('transaction_type','interest')->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"),'customer_id')->pluck('id'); 
        // dump($interest);
        $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q){
            return $q->where('transaction_type', '!=', 'interest');
        });
        // ->orWhereIn('id',$interest)
        // ->orWhereIn('id',$commissions)
        // ->orWhereIn('id', function($query) use ($user){
        //     $query->select('id')
        //     ->from(with(new CustomerTransactions)->getTable())
        //     ->where('transaction_type','reward')
        //     ->where('customer_id',$user->id)
        //     ->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"));
        // })
        // ->orderByDesc('deposit_date')
        // ->toSql();
        // ->paginate(20);
        // ->get();
        // dd($Associatetransactions);

        $combine_transactions = $transactions->union($Associatetransactions);
        if($request->input('from_date')!= '' && $request->input('to_date')!= '' || $request->input('payment_type') != ''){
            if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
                // dd('zdvzv');
                $combine_transactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
            }
            if($request->has('payment_type') && $request->input('payment_type') == 'cash' || $request->input('payment_type') == 'cheque' || $request->input('payment_type') == 'dd' || $request->input('payment_type') == 'NEFT' || $request->input('payment_type') == 'RTGS'){
                // dd('payment_type');
                $combine_transactions->where('payment_type', $request->payment_type);
            }
        }
            // dd($combine_transactions->toSql());
        // dd($combine_transactions);
        $transactions = $combine_transactions->orderByDesc('deposit_date')->paginate(50);
        // dd($transactions);
        return view('admin.reports.transactions',compact('transactions'));
    }
}