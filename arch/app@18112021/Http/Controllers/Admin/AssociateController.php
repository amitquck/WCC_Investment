<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Zipcode;
use App\Model\ActivityLog;
use App\Model\AssociateDetail;
use App\Model\AssociateReward;
use App\Model\AssociateTransactions;
use App\Model\AssociateCommissionPercentage;
use App\Model\CompanyBank;
use App\Model\EntryLock;
use App\Model\BankTransaction;
use Auth;
use Redirect;
use DB;
use Carbon\Carbon;
use App\User;
use App\Exports\AllAssociatesReportExport;
use App\Exports\CustomerWiseAssociateCommissionReportExport;
use Maatwebsite\Excel\Facades\Excel;

// use App\Model\CustomerService;
class AssociateController extends Controller
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



    public function index(Request $request)
    {
        // dd($request);
        $users = User::whereLoginType('associate');
        $countries = Country::get()->pluck('name','id');
        if($request->associate_name != null && $request->has('associate_name') ){
            $users->where('name','like','%'.$request->associate_name.'%');
        }else if($request->associate_mobile != null && $request->has('associate_mobile')){
            $users->where('mobile','like','%'.$request->associate_mobile.'%');
        }else if($request->associate_code != null && $request->has('associate_code')){
            $users->where('code','like','%'.$request->associate_code.'%');
        }else if($request->associate_bank_account != null && $request->has('associate_bank_account')){
            $users->whereHas('associatedetail',function($q) use ($request){
                return $q->where('account_number','like','%'.$request->associate_bank_account.'%');

            });
        }
        // dd($users->toSql());
        $users = $users->orderByDesc('created_at')->paginate(50);

        return view('admin.associates.index',compact('users','countries'));
    }

    public function addform()
    {
        if(\Auth::user()->login_type == 'superadmin' || \Auth::user()->login_type == 'employee'){
            $users = User::whereLoginType('associate')->orderBy('created_at')->paginate(10);
            $countries = Country::get()->pluck('name','id');
            $banks = CompanyBank::get();
        return view('admin.associates.add',compact('users','countries','banks'));
        }
    }



    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'code' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'mobile' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required','nominee_name' => 'nullable',
            'nominee_dob' => 'nullable',
            'nominee_sex' => 'nullable',
            'nominee_relation_with_applicable' => 'nullable',
            'nominee_address_one' => 'nullable',
            'nominee_address_two' => 'nullable',
            'nominee_zipcode' => 'nullable',
            'nominee_country_id' => 'nullable',
            'nominee_state_id' => 'nullable',
            'nominee_city_id' => 'nullable',
            'nominee_age' => 'nullable',
            'payment_type' => 'required',
        ]);

        // if($request->dob){
        //     $dob = date('Y-m-d',strtotime($request->dob));
        // }

        $associate = new User;
        $associate->login_type = 'associate';
        $associate->name = $request->name;
        $associate->password = isset($request->password)?Hash::make($request->password):123456;
        $associate->mobile = $request->mobile;
        $associate->code = $request->code;
        $associate->email = $request->email;
        $associate->created_by = \Auth::user()->id;
        $associate->status = 1;
        $name ='';
        if($request->has('image')!=null){
                    $image = $request->file('image');
                    $name = uniqid().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('images/associate');
                    $image->move($destinationPath, $name);
                }
        $nominee_name ='';
                     //dd($nominee_image);
                    if($request->has('nominee_image')!=null){
                    $nominee_image = $request->file('nominee_image');
                    $nominee_name = uniqid().'.'.$nominee_image->getClientOriginalExtension();
                    $destinationPath = public_path('images/associate');
                    $nominee_image->move($destinationPath, $nominee_name);
                }

        $associate->save();

        $associate->associatedetail()->create([
            'image' => $name,
            'nominee_image' => $nominee_name,
            'sex' => $request->sex,
            'dob' => $request->dob?date('Y-m-d',strtotime($request->dob)):null,
            'father_husband_wife' => $request->father_husband_wife,
            'mother_name' => $request->mother_name,
            'address_one' => $request->address_one,
            'address_two' => $request->address_two,
            'zipcode' => $request->zipcode,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            // 'bank_id' => $request->bank_id?$request->bank_id:'',
            'bank_name' => $request->bank_name?$request->bank_name:'',
            'branch' => $request->branch,
            'ifsc_code' => $request->ifsc_code,
            'pan_no' => $request->pan_no,
            'payment_type' => $request->payment_type,
            'nominee_name' => $request->nominee_name,
            'nominee_age' => $request->nominee_age,
            'nominee_sex' => $request->nominee_sex,
            'nominee_dob' => $request->nominee_dob?date('Y-m-d',strtotime($request->nominee_dob)):null,
            'nominee_relation_with_applicable' => $request->nominee_relation_with_applicable,
            'nominee_address_one' => $request->nominee_address_one,
            'nominee_address_two' => $request->nominee_address_two,
            'nominee_city_id' => $request->nominee_city_id,
            'nominee_state_id' =>$request->nominee_state_id,
            'nominee_country_id' => $request->nominee_country_id,
            'nominee_zipcode' => $request->nominee_zipcode,

        ]);

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $associate->id;
        $activity_log->statement = $request->name.' ('.$request->code.')  Since Added '.date('d-m-Y');
        $activity_log->action_type = 'Create Associate';
        $activity_log->save();

        return redirect('admin/associate')->with('success','Associate Added Successfully!');
    }


    public function autocomplete(Request $request)
    {
     // dd($request->term);
        if($request->has('term')){
            $result =  User::select('id','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,",", mobile) AS label'))->where('name','like','%'.$request->input('term').'%')->orWhere('mobile','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%')->get();
            // dd($result);
            return response()->json($result);
        }

    }


    // public function autocompleteAssAccount(Request $request)
    // {
    //  // dd($request->term);
    //     if($request->has('term')){
    //         $results =  AssociateDetail::select('associate_id','account_holder_name')->where('account_number','like','%'.$request->input('term').'%')->get();
    //         dd($results->all());
    //         $associate = User::whereId($results)->get();
    //         dd($associate);
    //         return response()->json($associate);
    //     }

    // }

    // public function searchAssociate(Request $request)
    // {
    //     // dd($request);
    //     $associate = User::where('id',$request->id)->first();
    //     return view('admin.associates.index',compact('associate'));
    // }


    public function view(Request $request,$id)
    {
        // dd($id);
        $associate = User::whereId($id)->first();

        $countries = Country::get()->pluck('name','id');
        $states = State::get();
        $cities = City::get();
        return view('admin.associates.view',compact('associate','countries','states','cities'));
    }

    public function edit(Request $request,$id)
    {
        $associate = User::whereId($id)->first();
        $countries = Country::get()->pluck('name','id');
        $states = State::get();
        $cities = City::get();
        $banks = CompanyBank::get();
        return view('admin.associates.edit',compact('associate','countries','states','cities','banks'));
    }


    public function update(Request $request,$id)
    {
        // dd($request->all());
        $associate = User::whereId($id)->first();
        $this->validate($request,[
            'code' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL'.$id,
            'name' => 'required',
            'mobile' => 'required',
        ]);
        // dd($associate);
        $associate->login_type = 'associate';
        $associate->name = $request->name;
       $associate->password = Hash::make($request->password);
        $associate->mobile = $request->mobile;
        $associate->code = $request->code;
        $associate->email = $request->email;
        $associate->created_by = \Auth::user()->id;
        $associate->status = 1;
         $name =$associate->associatedetail->image;
        if($request->has('image')!=null){
                    $image = $request->file('image');
                    $name = uniqid().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('images/associate');
                    $image->move($destinationPath, $name);
                }
        $nominee_name =$associate->associatedetail->nominee_image;
                     //dd($nominee_image);
                    if($request->has('nominee_image')!=null){
                    $nominee_image = $request->file('nominee_image');
                    $nominee_name = uniqid().'.'.$nominee_image->getClientOriginalExtension();
                    $destinationPath = public_path('images/associate');
                    $nominee_image->move($destinationPath, $nominee_name);
                }
        $associate->save();

        $associate->associatedetail()->update([

            'image' => $name,
            'nominee_image' => $nominee_name,
            'sex' => $request->sex,
            'dob' => $request->dob?date('Y-m-d',strtotime($request->dob)):'',
            'father_husband_wife' => $request->father_husband_wife,
            'mother_name' => $request->mother_name,
            'address_one' => $request->address_one,
            'address_two' => $request->address_two,
            'zipcode' => $request->zipcode,
            'payment_type' => $request->payment_type,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            // 'bank_id' => $request->bank_id,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'ifsc_code' => $request->ifsc_code,
            'pan_no' => $request->pan_no,
            'nominee_name' => $request->nominee_name,
            'nominee_age' => $request->nominee_age,
            'nominee_sex' => $request->nominee_sex,
            'nominee_dob' => $request->nominee_dob?date('Y-m-d',strtotime($request->nominee_dob)):'',
            'nominee_relation_with_applicable' => $request->nominee_relation_with_applicable,
            'nominee_address_one' => $request->nominee_address_one,
            'nominee_address_two' => $request->nominee_address_two,
            'nominee_city_id' => $request->nominee_city_id,
            'nominee_state_id' =>$request->nominee_state_id,
            'nominee_country_id' => $request->nominee_country_id,
            'nominee_zipcode' => $request->nominee_zipcode,

        ]);

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $associate->id;
        $activity_log->statement = $request->name.' ('.$request->code.')  Since Updated '.date('d-m-Y');
        $activity_log->action_type = 'Update Associate';
        $activity_log->save();
        return redirect('admin/associate')->with('success','Associate Updated Successfully!');
    }

    public function status($id){
        $associate = User::where('id',$id)->firstOrFail();
        $associate->status = !$associate->status;
        $associate->save();

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $associate->id;
        $activity_log->statement = $associate->name.' ('.$associate->code.') Change Status Since '.date('d-m-Y');
        $activity_log->action_type = 'Change Associate Status';
        $activity_log->save();

        return redirect()->back()->with('success','Status Updated Successfully!');
    }

    public function destroy_confirmation(Request $request){
        // dd($request->all());
        $user = User::whereId($request->id)->first();
        return view('admin.associates.delete_associate',compact('user'));
    }

    public function destroy(Request $request)
    {
        $this->validate($request,[
            'password'=>'required',
        ]);
        $users = User::whereLoginType('superadmin')->first();
        $user =Hash::check($request->password,$users->password);

        $associate = User::where('id',$request->associate_id)->first();

        $associateHasCommission = User::where('id',$request->associate_id)
                    ->whereHas('associatecommission')->count();

        // dd($request->all());
        if(!$associateHasCommission && $user){

            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            $activity_log->user_id = $associate->id;
            $activity_log->statement = $associate->name.' ('.$associate->code.') Deleted Since '.date('d-m-Y');
            $activity_log->action_type = 'Delete';
            $activity_log->save();

            User::where('id',$request->associate_id)->delete();
            AssociateDetail::where('associate_id',$request->associate_id)->delete();
            AssociateReward::where('associate_id',$request->associate_id)->delete();
            AssociateTransactions::where('associate_id',$request->associate_id)->delete();
            AssociateCommissionPercentage::where('associate_id',$request->associate_id)->delete();
            DirectAssociateCommission::where('associate_id',$request->associate_id)->delete();



            return redirect('admin/associate')->with('success','Associate Deleted Successfully!');
        }else{
            return redirect()->back()->with('error','Associate can\'t be deleted due to:  Associateed with any Customer Or Password Is Wrong');
        }
    }
    public function associateAddTransationForm(Request $request ){
        $associate = User::where('id',$request->id)->firstOrFail();
        $companyBanks = CompanyBank::get();
		return view('admin.associates.addtransaction',compact('associate','companyBanks'));
    }

    public function associateAddTransaction(Request $request){
		// dd($request->all());
		$this->validate($request,[
			'associate_id' => 'required|exists:users,id',
			'amount' => 'required|numeric|min:0',
			'deposit_date' => 'required',
			'payment_type' => 'required|min:1',
			'remarks' => 'required|min:1',
		]);
        $year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $year_month = $year.$month;
        $generated_payout = AssociateReward::whereRaw("CONCAT(associate_rewards.year,associate_rewards.month) >= $year_month")->first();
        if($generated_payout){
            return redirect()->back()->with('error',$month.'-'.$year.' Payout Is Already Genereted. Please! Delete Genereted Payout Of '.$month.'-'.$year);
        }
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
		// $customer = User::where('id',$request->customer_id)->firstOrFail();
		$customer_investment = User::whereId($request->associate_id)->first();
		// dd($customer_investment);

		$customer_investment->associatetransactions()->create([
			// 'associate_id' =>$request->associate_id,
			'payment_type' =>$request->payment_type,
			'transaction_type' =>'withdraw',
			'amount' => $request->amount,
			'cr_dr' => 'dr',
			'remarks' => $request->remarks,
			'bank_id' => $request->bank_id,
			'cheque_dd_number' => $request->cheque_dd_number,
			'deposit_date' => date('Y-m-d',strtotime($request->deposit_date)),
			'cheque_dd_date' => isset($request->date)?date('Y-m-d',strtotime($request->date)):Null,
			'status' => 1,
		]);

        if($request->payment_type != 'cash'){
            $txnEntry = new BankTransaction;
            $txnEntry->user_id = auth()->user()->id;
            $txnEntry->amount = $request->amount;
            $txnEntry->cr_dr = 'dr';
            $txnEntry->payment_type = $request->payment_type;
            $txnEntry->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
            $txnEntry->bank_id = $request->bank_id;
			$txnEntry->cheque_dd_number = $request->cheque_dd_number;
			$txnEntry->cheque_dd_date = isset($request->date)?date('Y-m-d',strtotime($request->date)):Null;
			$txnEntry->respective_table_id = DB::getPdo()->lastInsertId();
			$txnEntry->respective_table_name = 'associate_transactions';
			$txnEntry->remarks = $request->remarks;
            $txnEntry->created_by = auth()->user()->id;
            $txnEntry->save();
        }

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $customer_investment->id;
        $activity_log->statement = $customer_investment->name.' ('.$customer_investment->code.') Withdrawl Rs. '. $request->amount.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Delete';
        $activity_log->save();
		return redirect()->back()->with('success','Successfully Added Withdraw Amount');
	}
    public function profile(){
        return view('admin.associates.profile');
    }

    public function changePassword(){
        return view('admin.associates.changePassword');
    }

    public function changePasswordS(Request $request){
        $this->validate($request,['new_password' => 'required|same:cpassword' ]);
         $user = User::whereId(\Auth::user()->id)->first();
            // dd($user);
            $user->password = Hash::make($request->new_password);
            $user->save();
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            $activity_log->user_id = $user->id;
            $activity_log->statement = $user->name.' ('.$user->code.') Change Password Since At '.date('d-m-Y');
            $activity_log->action_type = 'Change Password';
            $activity_log->save();
            return redirect('associate/Profile')->with('success','Password Change Successfully!');

    }
    public function myCommission()
    {
        $associates  =  AssociateReward::whereAssociateId(Auth::user()->id)->paginate();
        $total_commission = 0;
        foreach($associates as $associate){
                $total_commission += $associate->amount;
        }
        // dd($total_commission);
        $transactions = AssociateReward::select(DB::raw("*, CONCAT(year,month) as month_year"))
            ->where('reward_type','commission')
            ->where('associate_id',Auth::user()->id)
            ->get()
            ->unique('month_year');
            // ->pluck('id');
        // $transactions = AssociateReward::where(function($q){
        //   return $q->where('reward_type','commission')->where('associate_id',Auth::user()->id);
        // })
        //     ->orWhereIn('id',$commissions)
            // ->paginate(10);
            // dd($transactions);
        return view('admin/associates/my-commission',compact('associates','total_commission','transactions'));
    }
    public function myCustomer()
    {
        $associates = AssociateCommissionPercentage::whereAssociateId(Auth::user()->id)->paginate();
        return view('admin/associates/my-customers',compact('associates'));
    }
    public function myWithdraw(){
        $u = User::whereLoginType('associate')->whereId(Auth::user()->id)->first();
		$users = [];
		foreach($u->associatetransactions->where('transaction_type','withdraw') as $us){
			array_push($users,$us);
		}
        return view('admin/associates/my-withdraw',compact('users'));
    }

    public function associatetransaction($associateId)
    {

        $user = User::where('id',decrypt($associateId))->where('login_type','associate')->firstOrFail();
        $commissions = AssociateTransactions::select(DB::raw("*, CONCAT(YEAR(deposit_date),MONTH(deposit_date)) as month_year"))
        ->where('transaction_type','commission')
        ->where('associate_id',$user->id)
        ->orderByDesc('deposit_date')
        ->get()
        ->unique('month_year')
        ->pluck('id');
        // $commissions = AssociateTransactions::where('transaction_type','commission')->where('associate_id',$user->id)->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"))->pluck('id');
        $transactions = AssociateTransactions::where(function($q) use ($user){
            return $q->where('transaction_type','withdraw')->where('associate_id',$user->id);
        })
        ->orWhereIn('id',$commissions)
        // ->orWhereIn('id', function($query) use ($user){
        //     $query->select('id')
        //     ->from(with(new AssociateTransactions)->getTable())
        //     ->where('transaction_type','commission')
        //     ->where('associate_id',$user->id)
        //     ->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"));
        // })
        ->orderByDesc('deposit_date')
        // ->toSql();
        ->paginate(10);
        return view('admin.associates.associate_alltransactions',compact('user','transactions'));
    }

    public function associates_excel_export(Request $request)
    {
        return Excel::download(new AllAssociatesReportExport($request),'associates_excel_export.xlsx');
    }


    public function customer_wise_associate_comm($associateID)
    {
        $ass_id = decrypt($associateID);
        $user = User::where('id',$ass_id)->whereStatus(1)->first();
        $associate_txn = AssociateCommissionPercentage::where('associate_id', $ass_id)->groupBy('customer_id')->paginate(20);
        $month = $year = '';
        return view('admin.associates.customer-wise-associate-comm',compact('associate_txn','user','month','year'));
    }


    public function customer_month_wise_associate_comm($associateID,$month,$year)
    {
        // dd($year);
        $ass_id = decrypt($associateID);
        $user = User::where('id',$ass_id)->whereStatus(1)->first();
        $associate_txn = AssociateCommissionPercentage::where('associate_id', $ass_id)->groupBy('customer_id')->paginate(20);
        return view('admin.associates.customer-wise-associate-comm',compact('associate_txn','user','month','year'));
    }

    public function excel_customer_wise_associate_comm(Request $request,$associateID)
    {
        // dd($request->all());
        return Excel::download(new CustomerWiseAssociateCommissionReportExport($request,$associateID),'customer_wise_associate_comm_excel_export.xlsx');
    }

    public function customer_marked_in_associate(Request $request)
    {
        // dd($request->associate_id);
        $marked_customers = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->groupBy('customer_id')->paginate(10);
        $associate = User::where('id',$request->associate_id)->where('login_type', 'associate')->first();
        return view('admin.associates.customer_markedin_associate',compact('marked_customers','associate'));
    }


    public function deposit_in_bulkform()
    {
        $banks = CompanyBank::get();
        return view('admin.associates.bulk_deposit',compact('banks'));
    }

    public function getAssociate(Request $request)
    {
        // dd($request->all());
        // if($request->selectPayment == 'withdraw'){
            // dd('2');
            if($request->has('term')){
                $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,"-", code) AS label'))->where('lock_status',0)->where('login_type','associate')->where(function($q) use ($request){
                    return $q->where('name','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%');
                })->get();
                return response()->json($results);
            }
        // }
    }

    public function asso_bulk_submit_transactions(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'amount' => 'required',
            // 'remarks' => 'required',
        ]);

        $year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $year_month = $year.$month;
        $generated_payout = AssociateReward::whereRaw("CONCAT(associate_rewards.year,associate_rewards.month) >= $year_month")->first();
        if($generated_payout){
            return redirect()->back()->with('error',$month.'-'.$year.' Payout Is Already Genereted. Please! Delete Genereted Payout Of '.$month.'-'.$year);
        }
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }

        foreach($request->associate_id as $key => $associateId){
            if($request->transaction_type[$key] == 'withdraw'){
                if($associateId != NULL){
                    $withdraw = new AssociateTransactions;
                    $withdraw->associate_id = $associateId;
                    $withdraw->amount = $request->amount[$key];
                    $withdraw->cr_dr = 'dr';
                    $withdraw->payment_type = $request->payment_type[$key];
                    $withdraw->transaction_type = 'withdraw';
                    $withdraw->deposit_date = date('Y-m-d',strtotime($request->deposit_date));
                    $withdraw->bank_id = $request->bank_id[$key];
                    $withdraw->cheque_dd_number = $request->cheque_dd_number[$key];
                    $withdraw->remarks = $request->remarks[$key];
                    $withdraw->status = 1;
                    $withdraw->created_by = \Auth::user()->id;
                    $withdraw->created_at = date('Y-m-d H:i:s');
                    $withdraw->save();

                    if($request->payment_type != 'cash'){
                        $txnEntry = new BankTransaction;
                        $txnEntry->user_id = auth()->user()->id;
                        $txnEntry->amount = $request->amount[$key];
                        $txnEntry->cr_dr = 'dr';
                        $txnEntry->payment_type = $request->payment_type[$key];
                        $txnEntry->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
                        $txnEntry->bank_id = $request->bank_id[$key];
                        $txnEntry->cheque_dd_number = $request->cheque_dd_number[$key];
                        // $txnEntry->cheque_dd_date = isset($request->date[$key])?date('Y-m-d',strtotime($request->date[$key])):Null; // Don't Uncomment.
                        $txnEntry->respective_table_id = $withdraw->id;
                        $txnEntry->respective_table_name = 'associate_transactions';
                        $txnEntry->remarks = $request->remarks[$key];
                        $txnEntry->created_by = auth()->user()->id;
                        $txnEntry->save();
                    }

                    $user = User::where('id',$associateId)->first();
                    $activity_log = new ActivityLog;
                    $activity_log->created_by = auth()->user()->id;
                    $activity_log->user_id = $user->id;
                    $activity_log->statement = $user->name.' ('.$user->code.') Withdraw Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');
                    $activity_log->action_type = 'Withdraw';
                    $activity_log->save();
                }
            }
        }
        return redirect()->back()->with('success','Associate Transaction Successfully!');
    }

    public function asso_edit_bulktxn(Request $request)
    {
        // dd('sdf');
        if($request->deposit_date != ''){
            $fromDate = date('Y-m-d 00:00:00',strtotime($request->deposit_date));
            $toDate = date('Y-m-d 23:59:59',strtotime($request->deposit_date));

            $associate_txn = AssociateTransactions::whereBetween('deposit_date', [$fromDate,$toDate])->where('transaction_type', '!=', 'commission')->get();

            $banks = CompanyBank::get();
            return view('admin.associates.edit_bulktxn',compact('banks','associate_txn'));
        }
        $associate_txn = '';
        $banks = CompanyBank::get();
        return view('admin.associates.edit_bulktxn',compact('banks','associate_txn'));
    }

    public function asso_update_bulk_txn(Request $request)
    {
        // dd($request->all());
        foreach($request->associate_id_old as $key => $associateId){
        // dd($associateId);
            $year = date('Y',strtotime($request->deposit_date[$key]));
            $month = date('m',strtotime($request->deposit_date[$key]));
            $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
            if($entrylock != NULL && $entrylock->status == 0){
                return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
            }
            // dump($i);
            if($request->transaction_type[$key] == 'withdraw'){
                if($associateId != NULL){
                    $withdraw = AssociateTransactions::where('associate_id', $associateId)->where('transaction_type', 'withdraw')->where('id', $request->table_id[$key])->first();
                    if($request->associate_id[$key] != null){
                        $withdraw->associate_id = $request->associate_id[$key];
                    }
                    $withdraw->amount = $request->amount[$key];
                    $withdraw->cr_dr = 'dr';
                    $withdraw->payment_type = $request->payment_type[$key];
                    $withdraw->transaction_type = 'withdraw';
                    // $withdraw->deposit_date = $request->deposit_date;
                    $withdraw->bank_id = $request->bank_id[$key];
                    $withdraw->cheque_dd_number = $request->cheque_dd_number[$key];
                    $withdraw->remarks = $request->remarks[$key];
                    $withdraw->status = 1;
                    $withdraw->created_by = \Auth::user()->id;
                    $withdraw->updated_at = date('Y-m-d H:i:s');
                    $withdraw->save();

                    // if($request->associate_id[$key] != null){
                    //     directAssociateCommission($request->associate_id[$key]);
                    // }else{
                    //     directAssociateCommission($withdraw->associate_id);
                    // }

                    $bankTxn = BankTransaction::where('respective_table_id',$request->table_id[$key])->where('respective_table_name', 'associate_transactions')->where('bank_id', $request->bank_id_old[$key])->first();
                    // dd($bankTxn);
                    if($request->payment_type != 'cash' && $bankTxn != NULL){
                        $bankTxn->user_id = auth()->user()->id;
                        $bankTxn->amount = $request->amount[$key];
                        $bankTxn->cr_dr = 'dr';
                        $bankTxn->payment_type = $request->payment_type[$key];
                        // $bankTxn->transaction_date = date('Y-m-d',strtotime($request->deposit_date));
                        $bankTxn->bank_id = $request->bank_id[$key];
                        $bankTxn->cheque_dd_number = $request->cheque_dd_number[$key];
                        // $bankTxn->cheque_dd_date = isset($request->date[$key])?date('Y-m-d',strtotime($request->date[$key])):Null;  // Don't Uncomment.
                        $bankTxn->respective_table_id = $withdraw->id;
                        $bankTxn->respective_table_name = 'associate_transactions';
                        $bankTxn->remarks = $request->remarks[$key];
                        $bankTxn->created_by = auth()->user()->id;
                        $bankTxn->save();
                    }

                    $user = User::where('id',$associateId)->first();
                    $activity_log = new ActivityLog;
                    $activity_log->created_by = auth()->user()->id;
                    $activity_log->user_id = $user->id;
                    $activity_log->statement = $user->name.' ('.$user->code.') Edit Withdraw Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');
                    $activity_log->action_type = 'Edit Withdraw';
                    $activity_log->save();
                }
            }
        }
        return redirect()->back()->with('success','Associate Transaction Updated Successfully!');
    }

    public function singleAssoDeleteBulkTxnForm(Request $request){
        // dd($request->all());
        $ids = explode(',',$request->id);
        // dd($ids[1]);
        $asso_txn = AssociateTransactions::where('id',$ids[0])->where('associate_id',$ids[1])->first();
        return view('admin.associates.per_delete_in_bulktxn',compact('asso_txn'));
    }

    public function singleAssoDeleteBulkTxn(Request $request){
        // dd($request->all());
        $this->validate($request,[
            'password'=>'required',
        ]);

        $users = User::whereLoginType('superadmin')->first();
        $user =Hash::check($request->password,$users->password);
        // dd($user);
        if($user){

            $asso_txn = AssociateTransactions::where('id',$request->table_id)->where('associate_id',$request->associate_id)->first();

            $year = date('Y',strtotime($asso_txn->deposit_date));
            $month = date('m',strtotime($asso_txn->deposit_date));
            $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
            if($entrylock != NULL && $entrylock->status == 0){
                return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
            }

            $user = User::where('id',$request->associate_id)->first();
            $activity_log = new ActivityLog;
            $activity_log->created_by = auth()->user()->id;
            $activity_log->user_id = $user->id;
            $activity_log->statement = $user->name.' ('.$user->code.') Delete On Bulk Transaction Since '. date('d-m-Y');
            $activity_log->action_type = 'Delete On Bulk Transaction';
            $activity_log->save();

            $asso_txn = AssociateTransactions::where('id',$request->table_id)->where('associate_id',$request->associate_id)->delete();
            // if($request->respective_table_id){
            //     $cust_invest = CustomerInvestment::where('id',$request->respective_table_id)->where('associate_id',$request->associate_id)->delete();
            // }
            // dd($asso_txn);
            return redirect()->back()->with('success','Associate Transaction Deleted Successfully!');
        }else{
            return redirect()->back()->with('error','Password is Wrong!');
        }
        return view('admin.associates.edit_bulktxn',compact('asso_txn'));
    }

    public function asso_delete_all_bulktxn(Request $request)
    {
        $this->validate($request,[
            'deposit_date' => 'required',
        ]);
        $deposit_date = date('Y-m-d',strtotime($request->deposit_date));
        // dd($deposit_date);
        $asso_txn = AssociateTransactions::where('transaction_type', '!=', 'commission')->whereBetween('deposit_date',[Carbon::parse($deposit_date)->format('Y-m-d'),Carbon::parse($deposit_date)->format('Y-m-d')])->get();
        // dd($asso_txn);
        foreach($asso_txn as $txn){
            $ass_reward = AssociateReward::where('associate_id',$txn->associate_id)->where('reward_type','commission')->first();
            if(!$ass_reward){

                $user = User::where('id', $txn->associate_id)->first();
                $activity_log = new ActivityLog;
                $activity_log->created_by = auth()->user()->id;
                $activity_log->user_id = $user->id;
                $activity_log->statement = $user->name.' ('.$user->code.') Delete Transaction Since '.date('d-m-Y');
                $activity_log->action_type = 'Delete Transaction';
                $activity_log->save();

                $asso_txn = AssociateTransactions::where('associate_id',$txn->associate_id)->where('transaction_type', '!=', 'commission')->whereBetween('deposit_date',[Carbon::parse($deposit_date)->format('Y-m-d'),Carbon::parse($deposit_date)->format('Y-m-d')])->delete();
            }
        }


        // dd($asso_txn);

        // $asso_invest = CustomerInvestment::whereBetween('deposit_date',[Carbon::parse($deposit_date)->format('Y-m-d'),Carbon::parse($deposit_date)->format('Y-m-d')])->delete();

        return redirect()->back()->with('success', 'Delete All Transaction Which Withdrawl Date '.$deposit_date);
    }

    public function readaddress(Request $request)
    {
        // dd($request->all());
        if(!empty($request->zipcode)){

            // $zipcode = Zipcode::select('z.country','z.state','z.city','c.name' as 'country_name','s.name' as 'state_name','city.name' as 'city_name')
            // ->leftJoin('countries',function($q){

            // })
            $zipcodeData = null;

               $zipcode = Zipcode::where('zipcode',$request->input('zipcode'))->first();
               // dd($zipcode->country);
                $zipcodeData['country'] = $zipcode->country->id;
                $zipcodeData['country_name'] = $zipcode->country->name;
                $zipcodeData['state'] = $zipcode->state->id;
                $zipcodeData['state_name'] = $zipcode->state->name;

                $zipcodeData['city'] = $zipcode->city->id;
                $zipcodeData['city_name'] = $zipcode->city->name;
            // $zipcode="SELECT z.country,z.state,z.city,c.name as country_name,s.name as state_name,city.name as city_name FROM zipcodes z left join countries c on z.country = c.id left join states s on z.state = s.id left join cities city on z.city = city.id where zipcode ='".$_POST['zipcode']."' ";
            // print_r("SELECT z.country,z.state,z.city,c.name as country_name,s.name as state_name,city.name FROM zipcodes z left join countries c on z.country = c.id left join states s on z.state = s.id left join cities city on z.city = city.id    where zipcode ='".$_POST['zipcode']."' ");die();

            // $allzipcode=mysqli_fetch_assoc(mysqli_query($conn,$zipcode));
                        // dd($zipcodeData);
            echo json_encode($zipcodeData);
            exit;
            }
    }
}
