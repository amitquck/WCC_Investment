<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\AssociateDetail;
use App\Model\AssociateReward;
use App\Model\AssociateTransactions;
use App\Model\AssociateCommissionPercentage;
use App\Model\CompanyBank;
use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;

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
        $users = $users->orderBy('created_at')->paginate(10);
        
        return view('admin.associates.index',compact('users','countries'));
    }

    public function addform()
    {
        if(\Auth::user()->login_type == 'superadmin'){
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
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'mobile' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
        ]);
        // dd('ssrb');
        $associate = new User;
        $associate->login_type = 'associate';
        $associate->name = $request->name;
        $associate->password = isset($request->password)?Hash::make($request->password):123456;
        $associate->mobile = $request->mobile;
        $associate->code = $request->code;
        $associate->email = $request->email;
        $associate->created_by = \Auth::user()->id;
        $associate->status = 1;
        $name = '';
        if($request->has('image')){
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('images/associate');
            $image->move($destinationPath, $name);
        }
        $associate->save();

        $associate->associatedetail()->create([
            'image' => $name,
            'sex' => $request->sex,
            'dob' => $request->dob,
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
            
        ]);
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
        return view('admin.associates.view',compact('associate'));
    }

    public function edit(Request $request,$id)
    {
        $associate = User::whereId($id)->first();
        $countries = Country::get();
        $states = State::get();
        $cities = City::get();
        $banks = CompanyBank::get(); 
        return view('admin.associates.edit',compact('associate','countries','states','cities','banks'));  
    }


    public function update(Request $request,$id)
    {
        
        $associate = User::whereId($id)->whereCode($request->code)->first();
        // $this->validate($request,[
        //     'code' => 'required|string|max:255',
        //     'name' => 'required|string|max:255',
        //     'mobile' => 'required|unique:users',
        // ]);
        // dd($associate);
        $associate->login_type = 'associate';
        $associate->name = $request->name;
        // $associate->password = Hash::make($request->password);
        $associate->mobile = $request->mobile;
        $associate->code = $request->code;
        $associate->email = $request->email;
        $associate->created_by = \Auth::user()->id;
        $associate->status = 1;
        $name = '';
        if($request->has('image')){
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('images/associate');
            $image->move($destinationPath, $name);
        }
       
        $associate->save();

        $associate->associatedetail()->update([

            'image' => $name,
            'sex' => $request->sex,
            'dob' => $request->dob,
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
            // 'bank_id' => $request->bank_id,
            'bank_name' => $request->bank_name,
            'branch' => $request->branch,
            'ifsc_code' => $request->ifsc_code,
            'pan_no' => $request->pan_no,
            
        ]);
        return redirect('admin/associate')->with('success','Associate Updated Successfully!');
    }

    public function status($id){
        $associate = User::where('id',$id)->firstOrFail();
        $associate->status = !$associate->status;
        $associate->save();
        return redirect()->back()->with('success','Status Updated Successfully!');
    }

    public function destroy($id){
        $associate = User::where('id',$id)
                    ->whereHas('associatecommission')->count();
                    // dd($associate);
        if(!$associate){
            User::where('id',$id)->delete();
            AssociateDetail::where('associate_id',$id)->delete();
            AssociateReward::where('associate_id',$id)->delete();
            AssociateTransactions::where('associate_id',$id)->delete();
            AssociateCommissionPercentage::where('associate_id',$id)->delete();
            return redirect()->back()->with('success','Associate Deleted Successfully!');
        }else{
            return redirect()->back()->with('error','Associate can\'t be deleted due to:  Associateed with any Customer');
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
			'deposit_date' => $request->deposit_date,
			'cheque_dd_date' => isset($request->date)?$request->date:Null,
			'status' => 1,
		]);
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
}
