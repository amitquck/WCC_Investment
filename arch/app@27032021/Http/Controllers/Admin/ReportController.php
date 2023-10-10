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
use App\Model\CustomerDetail;
use App\Model\AssociateReward;
use App\Model\AssociateCommissionPercentage;
use App\Model\DirectAssociateCommission;
use App\Model\EntryLock;
use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;
use File;
use App\Exports\UsersExport;
use App\Exports\AssociateWiseCustomerExport;
use App\Exports\CustomerWiseMonthlyReportExport;
use App\Exports\AllTransactionReportExport;
use App\Exports\MonthlyPayoutReportExport;
use App\Exports\CustomerTransactionReportExport;
use App\Exports\DebitorCreditorReportExport;
use App\Exports\AssociatePercentWiseBalanceReportExport;
use App\Exports\AllAssociatePaymentReportExport;
use App\Exports\AssociateLadgerReportExport;
use App\Exports\AssociateCommissionReportExport;
use App\Exports\BusinessCategoryReportExport;
use App\Exports\ThisMonthBusinessCategoryReportExport;
use App\Exports\LastMonthBusinessCategoryReportExport;
use App\Imports\ClientsTransactionsReportImport;
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
		
		$customer_deposits = $customer_deposits->orderByDesc('deposit_date')->paginate(20);
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
    // public function getPageAssociateWiseCustomer(Request $request){
    //     // dd($request->all());
    //     $associate_txn = AssociateReward::where('associate_id', $request->id)->groupBy('customer_id')->paginate(20);

    //     return view('admin.reports.associate-wise-customer-report',compact('associate_txn'));
    // }

    public function getPageAssociateWiseCustomer(Request $request){
        // dd($request->all());
        $associate_txn = AssociateCommissionPercentage::where('associate_id', $request->id)->groupBy('customer_id')->paginate(20);
        // dd($associate_txn);

        return view('admin.reports.associate-wise-customer-report',compact('associate_txn'));
    }

    // public function getPageAssociateWiseCustomer(Request $request){
    //     // dd($request->all());
    //    $userss = User::where('login_type','associate');
    //     if($request->has('id') && $request->id != '' ){
    //         $userss->where('id',$request->id);
    //     }
    //     $userss = $userss->get();
    //     // dd($userss);
    //     $users = [];
    //     $totalcommission = [];
    //     foreach($userss as $user){
    //         $users = $user->associatecommission;
    //         $totalcommission = $user->associatetransactions;
    //     }
    //     // dd($totalcommission);
    //     return view('admin.reports.associate-wise-customer-report',compact('users','userss','totalcommission'));
    // }

    public function autocompleteAssociateCustomer(Request $request){
        if($request->has('term')){
            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,",", code) AS label'))->where('name','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%')->where('login_type','associate')->get();
            return response()->json($results);
        }
    }
    
    // public function customerWiseMonthlyReport(Request $request){
    //     // dd($request->all());
    //     $month = str_pad($request->month,2,0,STR_PAD_LEFT);
    //                 // dd($month);
    //     $user = User::whereId($request->id)->first();
    //     $monthlyReport = array();
    //     $firstdata = [];
    //     if($user){
    //         $rewards = $user->customerrewards()->where('month',$month)->where('year',$request->year)->groupBy(DB::Raw('CONCAT(year,month)'))->get();
    //         foreach($rewards as $reward){
    //             if($request->has('month') && $request->input('month')!= '' && $request->has('year') && $request->input('year')!= ''){
    //                 // dd($reward->sum_monthly_interest);
    //                 $monthlyReport[$request->year.'-'.$month]['interest_amount'] = $reward->sum_monthly_interest;
    //                 $monthlyReport[$request->year.'-'.$month]['customer'] = $reward->customer;
    //                 $monthlyReport[$request->year.'-'.$month]['commissions'] = $user->customerassociaterewards()->where('month',$month)->where('year',$request->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();
    //             }else{
    //                 $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
    //                 $monthlyReport[$reward->year.'-'.$reward->month]['customer'] = $reward->customer;
    //                 $monthlyReport[$reward->year.'-'.$reward->month]['commissions'] = $user->customerassociaterewards()->where('month',$reward->month)->where('year',$reward->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();
    //             }
    //         }
    //     }
    //     // dd($monthlyReport);
    //     $associate_reward = AssociateReward::whereCustomerId($request->id)->get();
        
    //     return view('admin.reports.customer-wise-monthly-report',compact('associate_reward','monthlyReport','firstdata','user'));
    // }


    public function customerWiseMonthlyReport(Request $request){
        // dd($request->all());
        $month = str_pad($request->month,2,0,STR_PAD_LEFT);
                    // dd($month);
        $user = User::whereId($request->id)->first();
        $monthlyReport = array();
        $firstdata = [];
        if($user){
            if($request->has('month') && $request->input('month')!= '' && $request->has('year') && $request->input('year')!= ''){
                $rewards = $user->customerrewards()->where('month',$month)->where('year',$request->year)->groupBy(DB::Raw('CONCAT(year,month)'))->get();
                foreach($rewards as $reward){
                    $monthlyReport[$request->year.'-'.$month]['interest_amount'] = $reward->sum_monthly_interest;
                    $monthlyReport[$request->year.'-'.$month]['customer'] = $reward->customer;
                    $monthlyReport[$request->year.'-'.$month]['commissions'] = $user->customerassociaterewards()->where('month',$month)->where('year',$request->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();
                }
            }else{
                $rewards = $user->customerrewards()->groupBy(DB::Raw('CONCAT(year,month)'))->get();
                foreach($rewards as $reward){
                    $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
                    $monthlyReport[$reward->year.'-'.$reward->month]['customer'] = $reward->customer;
                    $monthlyReport[$reward->year.'-'.$reward->month]['commissions'] = $user->customerassociaterewards()->where('month',$reward->month)->where('year',$reward->year)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();
                }
            }
        }
        // dd($monthlyReport);
        $associate_reward = AssociateReward::whereCustomerId($request->id)->get();
        
        return view('admin.reports.customer-wise-monthly-report',compact('associate_reward','monthlyReport','firstdata','user'));
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
        $associate_reward = AssociateReward::whereCustomerId($request->id)->get();
        
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
    
    public function excel_monthly_payout(Request $request)
    {
        return Excel::download(new MonthlyPayoutReportExport($request), 'monthly_payout.xlsx');
    }

    public function transactions(Request $request)
    {
        // dd($request->all());
        // $commissions = AssociateTransactions::where('transaction_type','commission')->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"),'associate_id')->pluck('id'); 
        // dump($commissions);
        /*->where(function($q){
                return $q->where('transaction_type', '!=', 'commission');
            })*/
        $Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at');
            
            if($request->has('from_date') && $request->has('to_date') || $request->has('payment_type') != ''){
                if($request->has('from_date') && $request->input('from_date')!= '' && $request->has('to_date') && $request->input('to_date')!= ''){
                // dd($request->from_date);
                    $Associatetransactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
                }
                if($request->has('payment_type') && $request->input('payment_type') == 'cash' || $request->input('payment_type') == 'cheque' || $request->input('payment_type') == 'dd' || $request->input('payment_type') == 'NEFT' || $request->input('payment_type') == 'RTGS'){
                // dd('payment_type');
                     $Associatetransactions->where('payment_type', $request->payment_type);
                }
            }
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
        if($request->input('from_date')!= '' && $request->input('to_date')!= '' || $request->input('payment_type') != ''){
            if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
                // dd('zdvzv');
                $transactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
            }
            if($request->has('payment_type') && $request->input('payment_type') == 'cash' || $request->input('payment_type') == 'cheque' || $request->input('payment_type') == 'dd' || $request->input('payment_type') == 'NEFT' || $request->input('payment_type') == 'RTGS'){
                // dd('payment_type');
                $transactions->where('payment_type', $request->payment_type);
            }
            // if($request->input('customer') != ''){
            //     $transactions->whereIn('customer_id',function($q) use ($request){
            //         return $q->select('id')->where('code','like','%'.$request->customer.'%')->orWhere('name','like','%'.$request->customer.'%')->where('login_type','customer')->from('users');
            //     });
            // } || $request->input('customer') != ''
        }
        if($request->input('customer') != ''){
            $transactions->whereIn('customer_id',function($q) use ($request){
                return $q->select('id')->where('code','like','%'.$request->customer.'%')->orWhere('name','like','%'.$request->customer.'%')->where('login_type','customer')->from('users');
            });
        } 

        if($request->user_type == 'customer' || $request->input('customer') != ''){
            $transactions = $transactions->orderByDesc('deposit_date')->paginate(50);
            return view('admin.reports.transactions',compact('transactions'));
        }elseif($request->user_type == 'associate'){
            $transactions = $Associatetransactions->orderByDesc('deposit_date')->paginate(50);
            return view('admin.reports.transactions',compact('transactions'));
        }


        $combine_transactions = $transactions->union($Associatetransactions);
        // dd($combine_transactions);
        
            // dd($combine_transactions->toSql());
        // dd($combine_transactions->get());
        $transactions = $combine_transactions->orderByDesc('deposit_date')->paginate(50);
        // dd($transactions);
        return view('admin.reports.transactions',compact('transactions'));

        /*(select `id`, 0 as associate_id, `customer_id`, `amount`, `payment_type`, `cr_dr`, `status`, `cheque_dd_number`, `bank_id`, `cheque_dd_date`, `deposit_date`, `transaction_type`, `respective_table_id`, `respective_table_name`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at` from `customer_transactions` where (`transaction_type` != 'interest') and `customer_id` in (select `id` from `users` where `code` like 'FALDK101' or `name` like 'FALDK101' and `login_type` = 'customer') and `customer_transactions`.`deleted_at` is null) union (select `id`, `associate_id`, `customer_id`, `amount`, `payment_type`, `cr_dr`, `status`, `cheque_dd_number`, `bank_id`, `cheque_dd_date`, `deposit_date`, `transaction_type`, `respective_table_id`, `respective_table_name`, `remarks`, `created_by`, `updated_by`, `created_at`, `updated_at` from `associate_transactions` where `associate_transactions`.`deleted_at` is null) */
    }



    public function customermonthlyreport(Request $request)
    {
        // dd($request->all());
        $associate_txn = AssociateTransactions::where('customer_id',$request->customer_id)->where('associate_id',$request->associate_id)->whereBetween('deposit_date',[date($request->year.'-'.$request->month.'-'.'01' ),date($request->year.'-'.$request->month.'-'.'t' )])->orderByDesc('deposit_date')->paginate(20);
        // dd($associate_txn);
        return view('admin.reports.customer_monthly_report_by_associate',compact('associate_txn'));
    }



    public function monthly_payout(Request $request)
    {
        // dd($request->all());
        $month = str_pad($request->month,2,0,STR_PAD_LEFT);
        // dd($month);
        $cust_reward = '';
        if($request->has('month') && $request->input('month')!= '' && $request->has('year') && $request->input('year')!= '' && $request->payment_type != ''){
        // dd('hk');
            if($request->payment_type == '0'){
                $cust_reward = CustomerReward::whereIn('customer_id',function($q) use ($request){
                    return $q->select('id')->where('hold_status', $request->payment_type)->from('users');
                })->where('month',$month)->where('year',$request->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->paginate(50);
            }else{
                $cust_reward = CustomerReward::whereIn('customer_id',function($q) use ($request){
                    return $q->select('customer_id')->where('payment_type', $request->payment_type)->from('customer_details');
                })->where('month',$month)->where('year',$request->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->paginate(50);
            }
        }elseif($request->has('month') && $request->input('month')!= '' && $request->has('year') && $request->input('year')!= ''){
            $cust_reward = CustomerReward::where('month',$month)->where('year',$request->year)->groupBy(['customer_id','month','year'])->orderByDesc('month')->paginate(50);
        }else{
            $cust_reward = CustomerReward::groupBy(['customer_id','month','year'])->paginate(50);
        }
        return view('admin.reports.monthly_payout',compact('cust_reward'));

        // $monthlyReport = array();
        // $rewards = CustomerReward::groupBy(DB::Raw('CONCAT(year,month)'))->get();
        // foreach($rewards as $reward){
        //     $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
        // }
        // // dd($monthlyReport);
        // return view('admin.reports.monthly_payout',compact('monthlyReport'));
    }


    public function business_category(Request $request)
    {
        // dd($request->all());
        if($request->has('q') != '' && $request->input('q') == 'customer'){
            $q = $request->q;
            $cust_txn = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->groupBy('customer_id')->paginate(50);
            return view('admin.reports.business_category',compact('cust_txn', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'associate'){
            $q = $request->q;
            // dd($q);
            $associates = DirectAssociateCommission::groupBy('associate_id')->paginate(50);
            return view('admin.reports.business_category',compact('associates', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'state'){
            $q = $request->q;
            $states = State::whereIn('id',function($q){
                return $q->select('customer_details.state_id')->from('customer_details');
            })->paginate(50);
            // dd($states);
            return view('admin.reports.business_category',compact('states', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'city'){
            $q = $request->q;
            $cities = City::whereIn('id',function($q){
                return $q->select('customer_details.city_id')->from('customer_details');
            })->paginate(50);
            // dd($cities);    
            return view('admin.reports.business_category',compact('cities', 'q'));
        }

        // else if($request->has('q') != '' && $request->input('q') == 'city'){
            
        // }

        // else if($request->has('q') != '' && $request->input('q') == 'associate'){
        //     $q = $request->q;
        //     $cust_txn = AssociateTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->groupBy('customer_id')->paginate(50);
        //     $cust_reward = '';
        //     $asso_reward = '';
        //     foreach ($cust_txn as $key => $txn) {
        //         $cust_reward = CustomerReward::where('customer_id', $txn->customer_id)->sum('amount');
        //         $asso_reward = AssociateReward::where('customer_id', $txn->customer_id)->sum('amount');
        //     }
        //     return view('admin.reports.business_category',compact('cust_txn', 'cust_reward', 'asso_reward', 'q'));
        // }

    }

    public function excel_business_category(Request $request)
    {
        return Excel::download(new BusinessCategoryReportExport($request),$request->q.'_wise_business_category_excel_export.xlsx');
    }


    public function customer_transactions(Request $request)
    {
        // dd($request->all());
        if($request->input('from_date')!= '' && $request->input('to_date')!= '' && $request->input('customer') != ''){
            // dd('1');
            $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($request){
                    return $q->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
                });

            
            $transactions->whereIn('customer_id',function($q) use ($request){
                return $q->select('id')->where('code',$request->customer)->from('users');
            });
            
            $transactions = $transactions->orderBy('deposit_date')->paginate(50);
            return view('admin.reports.customer_transactions',compact('transactions'));
        }elseif($request->input('customer') != ''){
            // dd('2');
            $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at');

            
            $transactions->whereIn('customer_id',function($q) use ($request){
                return $q->select('id')->where('code',$request->customer)->from('users');
            });
            // dd($transactions->toSql());
            $transactions = $transactions->orderBy('deposit_date')->paginate(50);
            return view('admin.reports.customer_transactions',compact('transactions'));
        }
        $transactions = 0;
        return view('admin.reports.customer_transactions',compact('transactions'));
    }


    
    public function excel_customer_transactions(Request $request)
    {
        return Excel::download(new CustomerTransactionReportExport($request), 'customer_transactions.xlsx');
    }

    public function associate_balance(Request $request)
    {
        // dd($request->all());
        $month = str_pad($request->month,2,0,STR_PAD_LEFT);
        $year = $request->year;
        // $month = $year = '';
        $customers = User::where('login_type', 'customer');
        if($request->customer != ''){
            // dd('1');
            $customers->where('name','like','%'.$request->customer.'%')->orWhere('code','like','%'.$request->customer.'%');
        }elseif($request->associate != ''){
            // dd('2');
            $associates = User::where('name','like','%'.$request->associate.'%')->orWhere('code','like','%'.$request->associate.'%')->where('login_type','associate')->pluck('id');
            // dd($associates);
            $customers->whereHas('associatecommissions', function($q) use ($associates){
                return $q->whereIn('associate_id', $associates);
            });
            // $customers;
            // dd($customers->toSql());
        }
        $customers = $customers->paginate(20);
        // $customers = $customers->toSql();
        // dd($customers);
        return view('admin.reports.associate_balance',compact('customers','month','year'));
    }

    public function excel_associate_per_balance(Request $request)
    {
        return Excel::download(new AssociatePercentWiseBalanceReportExport($request),'associate_per_balance.xlsx');
    }

    public function debitor_creditor_list(Request $request)
    {
        // dd($request);
        $transactions = CustomerTransactions::where('transaction_type', '!=', 'interest')->groupBy('customer_id');
            
        if($request->input('from_date')!= '' && $request->input('to_date')!= '' || $request->input('customer') != ''){
            
            if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
                $transactions->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
            }
            
            if($request->input('customer')!= ''){
                $transactions->whereIn('customer_id',function($q) use ($request){
                    return $q->select('id')->where('code','like','%'.$request->customer.'%')->orWhere('name','like','%'.$request->customer.'%')->from('users');
                });
            }
        }
        $transactions = $transactions->orderBy('deposit_date')->paginate(50);
        // dump($transactions);
        return view('admin.reports.debitor_creditor_list',compact('transactions'));
    }

    // public function debitor_creditor_list(Request $request)
    // {
    //     $transactions = CustomerTransactions::where('transaction_type', '!=', 'interest')->groupBy('customer_id');
            
    //     if($request->input('from_date')!= Null && $request->input('to_date')!= Null){
    //         // dd('ddd');
    //         if($request->input('from_date')!= '' && $request->input('to_date')!= ''){
    //             $transactions = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
    //         }
            
    //         if($request->input('customer')!= ''){
    //             $transactions->whereIn('customer_id',function($q) use ($request){
    //                 return $q->select('id')->where('code','like','%'.$request->customer.'%')->orWhere('name','like','%'.$request->customer.'%')->from('users');
    //             });
    //         }
    //         $transactions = $transactions->orderBy('deposit_date')->paginate(50);
    //         // dump($transactions);
    //         return view('admin.reports.debitor_creditor_list',compact('transactions'));
    //     }
    //     if($request->input('customer')!= ''){
    //             $transactions->whereIn('customer_id',function($q) use ($request){
    //                 return $q->select('id')->where('code','like','%'.$request->customer.'%')->orWhere('name','like','%'.$request->customer.'%')->from('users');
    //             });
    //         }

    //     $transactions = $transactions->orderBy('deposit_date')->paginate(50);
    //     // dump($transactions);
    //     return view('admin.reports.debitor_creditor_list',compact('transactions'));
    // }

    public function excel_debitor_creditor_list(Request $request)
    {
        // dd($request->all());
        return Excel::download(new DebitorCreditorReportExport($request),'Debitor_Creditor_List_As_on_Date '.$request->to_date.'.xlsx');
    }


    public function this_month_business_category(Request $request)
    {
        // dd($request->all());
        if($request->has('q') != '' && $request->input('q') == 'customer'){
            $q = $request->q;
            $cust_txn = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('deposit_date',[$request->from_date,$request->to_date])->groupBy('customer_id')->paginate(50);
            // dd($cust_txn);
            return view('admin.reports.this_month_business_category',compact('cust_txn', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'associate'){
            $q = $request->q;
            // dd($q);
            $associates = DirectAssociateCommission::whereBetween('created_at',[$request->from_date,$request->to_date])->groupBy('associate_id')->paginate(50);
            return view('admin.reports.this_month_business_category',compact('associates', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'state'){
            $q = $request->q;
            $states = State::whereIn('id',function($q){
                return $q->select('customer_details.state_id')->from('customer_details');
            })->paginate(50);
            // dd($states);
            return view('admin.reports.this_month_business_category',compact('states', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'city'){
            $q = $request->q;
            $cities = City::whereIn('id',function($q){
                return $q->select('customer_details.city_id')->from('customer_details');
            })->paginate(50);
            // dd($cities);    
            return view('admin.reports.this_month_business_category',compact('cities', 'q'));
        }

        // else if($request->has('q') != '' && $request->input('q') == 'city'){
            
        // }

        // else if($request->has('q') != '' && $request->input('q') == 'associate'){
        //     $q = $request->q;
        //     $cust_txn = AssociateTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->groupBy('customer_id')->paginate(50);
        //     $cust_reward = '';
        //     $asso_reward = '';
        //     foreach ($cust_txn as $key => $txn) {
        //         $cust_reward = CustomerReward::where('customer_id', $txn->customer_id)->sum('amount');
        //         $asso_reward = AssociateReward::where('customer_id', $txn->customer_id)->sum('amount');
        //     }
        //     return view('admin.reports.business_category',compact('cust_txn', 'cust_reward', 'asso_reward', 'q'));
        // }

    }

    public function excel_this_month_business_category(Request $request)
    {
        return Excel::download(new ThisMonthBusinessCategoryReportExport($request),$request->q.'_wise_this_month_business_category_excel_export.xlsx');
    }


    public function last_month_business_category(Request $request)
    {
        // dd($request->all());
        if($request->has('q') != '' && $request->input('q') == 'customer'){
            $q = $request->q;
            $cust_txn = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('deposit_date',[$request->from_date,$request->to_date])->groupBy('customer_id')->paginate(50);
            // dd($cust_txn);
            return view('admin.reports.last_month_business_category',compact('cust_txn', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'associate'){
            $q = $request->q;
            // dd($q);
            $associates = DirectAssociateCommission::whereBetween('created_at',[$request->from_date,$request->to_date])->groupBy('associate_id')->paginate(50);
            return view('admin.reports.this_month_business_category',compact('associates', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'state'){
            $q = $request->q;
            $states = State::whereIn('id',function($q){
                return $q->select('customer_details.state_id')->from('customer_details');
            })->paginate(50);
            // dd($states);
            return view('admin.reports.last_month_business_category',compact('states', 'q'));
        }else if($request->has('q') != '' && $request->input('q') == 'city'){
            $q = $request->q;
            $cities = City::whereIn('id',function($q){
                return $q->select('customer_details.city_id')->from('customer_details');
            })->paginate(50);
            // dd($cities);    
            return view('admin.reports.last_month_business_category',compact('cities', 'q'));
        }

        // else if($request->has('q') != '' && $request->input('q') == 'city'){
            
        // }

        // else if($request->has('q') != '' && $request->input('q') == 'associate'){
        //     $q = $request->q;
        //     $cust_txn = AssociateTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->groupBy('customer_id')->paginate(50);
        //     $cust_reward = '';
        //     $asso_reward = '';
        //     foreach ($cust_txn as $key => $txn) {
        //         $cust_reward = CustomerReward::where('customer_id', $txn->customer_id)->sum('amount');
        //         $asso_reward = AssociateReward::where('customer_id', $txn->customer_id)->sum('amount');
        //     }
        //     return view('admin.reports.business_category',compact('cust_txn', 'cust_reward', 'asso_reward', 'q'));
        // }

    }

    public function excel_last_month_business_category(Request $request)
    {
        return Excel::download(new LastMonthBusinessCategoryReportExport($request),$request->q.'_wise_last_month_business_category_excel_export.xlsx');
    }


    public function associate_payment_list(Request $request)
    {
        // dd($request->all());
        $month = str_pad($request->month,2,0,STR_PAD_LEFT);
        $associate_txn = AssociateTransactions::groupBy('associate_id');
        $year = $request->year?$request->year:'';
        $monthh = $month?$month:'';
        if($request->year != '' && $request->month != '' || $request->associate != ''){
            // dd('fdf');
            if($request->year != '' && $request->month != ''){
                $associate_txn->whereBetween('deposit_date',[date($request->year.'-'.$month.'-'.'01'),date($request->year.'-'.$month.'-'.'31')]);
            }
            if($request->associate != ''){
                $associate_txn->whereIn('associate_id', function($q) use ($request){
                    return $q->select('id')->where('code','like','%'.$request->associate.'%')->orWhere('name','like','%'.$request->associate.'%')->from('users');
                });
            }
        }
        $associate_txn = $associate_txn->paginate(25);
        // dd($associate_txn);
        return view('admin.reports.associate_payment_list',compact('associate_txn','year','monthh'));
    }

    public function excel_associate_payment_list(Request $request)
    {
        // dd($request->all());
        return Excel::download(new AllAssociatePaymentReportExport($request),'associate_payment_excel_export.xlsx');
    }

    public function associate_ladger_balance(Request $request)
    {
        // dd($request->all());
        if($request->input('from_date')!= '' && $request->input('to_date')!= '' && $request->input('associate') != ''){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($request){
                    return $q->whereBetween('deposit_date', [$request->from_date, $request->to_date]);
                });

            
            $transactions->whereIn('associate_id',function($q) use ($request){
                return $q->select('id')->where('code','like','%'.$request->associate.'%')->from('users');
            });
            
            $transactions = $transactions->orderBy('deposit_date')->paginate(50);
            return view('admin.reports.associate_ladger_balance',compact('transactions'));
        }elseif($request->input('associate') != ''){
            $transactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where('transaction_type', '!=', 'interest');

            
            $transactions->whereIn('associate_id',function($q) use ($request){
                return $q->select('id')->where('code','like','%'.$request->associate.'%')->from('users');
            });
            
            $transactions = $transactions->orderBy('deposit_date')->paginate(50);
            return view('admin.reports.associate_ladger_balance',compact('transactions'));
        }
        $transactions = 0;
        return view('admin.reports.associate_ladger_balance',compact('transactions'));
    }

    public function excel_associate_ladger(Request $request)
    {
        // dd($request->all());
        return Excel::download(new AssociateLadgerReportExport($request),'associate_ladger_excel_export.xlsx');
    }

    public function associate_commission_list(Request $request)
    {
        // dd($request->all());
        $month = str_pad($request->month,2,0,STR_PAD_LEFT);
        $associate_reward = '';
        if($request->has('month') && $request->input('month')!= '' && $request->has('year') && $request->input('year')!= ''){
            $associate_reward = AssociateReward::where('month',$month)->where('year',$request->year)->groupBy(['associate_id','month','year'])->orderByDesc('month')->paginate(50);
        }else{
            $associate_reward = AssociateReward::groupBy(['associate_id','month','year'])->paginate(50);
        }
        return view('admin.reports.associate_commission_list',compact('associate_reward'));

        // $monthlyReport = array();
        // $rewards = CustomerReward::groupBy(DB::Raw('CONCAT(year,month)'))->get();
        // foreach($rewards as $reward){
        //     $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
        // }
        // // dd($monthlyReport);
        // return view('admin.reports.monthly_payout',compact('monthlyReport'));
    }


    public function excel_associate_commission_list(Request $request)
    {
        return Excel::download(new AssociateCommissionReportExport($request),'associate_commission_list_excel_export.xlsx');
    }

    public function import_excel_transactions(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'import_txns' => 'required|mimes:xlsx',
            'date' => 'required',
        ]);

        $year = date('Y',strtotime($request->date));
        $month = date('m',strtotime($request->date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
        // dd('$monthlyReport');
        if($request->hasFile('import_txns')){
            $paths = $request->file('import_txns')->getRealPath();
            $date = $request->date;
            // dd($path);
            // $data = new ClientsTransactionsReportImport($date);
            return Excel::import(new ClientsTransactionsReportImport($date),$paths);
            // dd($data);
        }
    }
}
