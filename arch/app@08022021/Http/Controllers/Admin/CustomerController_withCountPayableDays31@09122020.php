<?php 
	namespace App\Http\Controllers\Admin;
	use Illuminate\Support\Collection;
	use Illuminate\Http\Request;	
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Auth;
	use App\Model\CustomerDetail;
	use App\Model\CustomerReward;
	use App\Model\AssociateReward;
	use App\Model\CustomerInvestment;
	use App\Model\CustomerTransactions;
	use App\Model\AssociateTransactions;
	use App\Helpers as Helper;
	use App\User;
	use Redirect;
	use DB; 
	use App\Zipcode;
	use App\Model\Country;
	use App\Model\State;
	use App\Model\City;
	use Carbon\Carbon;


	class CustomerController extends Controller
	{
		public function index(){
	      	if(\Auth::user()->login_type == 'superadmin'){
				$users = User::whereLoginType('customer')->orderBy('created_at')->paginate(10);
				$countries = Country::get()->pluck('name','id');
	        	return view('admin.customers.index',compact('users','countries'));
	        }
		}

		public function add()
		{
			$countries = Country::get()->pluck('name','id');
        	return view('admin.customers.add',compact('countries'));
		}
		public function store(Request $request){
				$this->validate($request,[
				'name' => 'required',
				'customer_id' => 'required',
				'dob' => 'nullable',
				'sex' => 'nullable',
				'father_husband_wife' => 'nullable',
				'nationality' => 'nullable',
				'email' => 'required|email',
				'mobile' => 'required',
				'address_one' => 'nullable',
				'address_two' => 'nullable',
				'country_id' => 'nullable',
				'state_id' => 'nullable',
				'city_id' => 'nullable',
				'zipcode' => 'nullable',
				'account_holder_name' => 'nullable',
				'bank_name' => 'nullable',
				'account_number' => 'nullable',
				'ifsc_code' => 'nullable',
				'nominee_name' => 'nullable',
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
				
			]);
				

			$customer = new User;
			$customer->login_type = 'customer';
			$customer->name = $request->name;
			// $customer->password = Hash::make($request->password);
			$customer->mobile = $request->mobile;
			$customer->code = $request->customer_id;
			$customer->email = $request->email;
			$customer->created_by = \Auth::user()->id;
			$customer->status = 1;
			$customer->save();	
			// dump($customer->customerdetails());
			// dd($customer);
			$customer->customerdetails()->create([
				'dob' => date('Y-m-d',strtotime($request->dob)),
				'age' => $request->age,
				'nationality' => $request->nationality,
				'sex' => $request->sex,
				'father_husband_wife' => $request->father_husband_wife,
				'address_one' => $request->address_one,
				'address_two' => $request->address_two,
				'city_id' => $request->city_id,
				'state_id' => $request->state_id,
				'country_id' => $request->country_id,
				'zipcode' => $request->zipcode,
				'account_holder_name' => $request->account_holder_name,
				'bank_name' => $request->bank_name,
				'account_number' => $request->account_number,
				'ifsc_code' => $request->ifsc_code,
				'nominee_name' => $request->nominee_name,
				'nominee_age' => $request->nominee_age,
				'nominee_sex' => $request->nominee_sex,
				'nominee_dob' => date('Y-m-d',strtotime($request->nominee_dob)),
				'nominee_relation_with_applicable' => $request->nominee_relation_with_applicable,
				'nominee_address_one' => $request->address_one,
				'nominee_address_two' => $request->address_two,
				'nominee_city_id' => $request->city_id,
				'nominee_state_id' =>$request->state_id,
				'nominee_country_id' => $request->nominee_country_id,
				'nominee_zipcode' => $request->nominee_zipcode,
				
			]);
			// dd($customer->customerdetail());
		return redirect()->route('admin.customer_commission', [encrypt($customer->id)])->withSuccess('Customer Successfully Added');

		}

		public function edit(Request $request,$id){
			// dd($id);
			$countries = Country::get();
			$states = State::get();
			$cities = City::get();
			$customer = User::where('id',decrypt($id))->firstOrFail();
			// dd($customer->customerdetails);
			return view('admin.customers.edit',compact('customer','countries','states',
				'cities'));

		}

		public function update(Request $request){
			// dd($request->id);
			$this->validate($request,[
				'name' => 'required',
				'customer_id' => 'required',
				'dob' => 'nullable',
				'sex' => 'nullable',
				'father_husband_wife' => 'nullable',
				'nationality' => 'nullable',
				'email' => 'required|email',
				'mobile' => 'required',
				'address_one' => 'nullable',
				'address_two' => 'nullable',
				'country_id' => 'nullable',
				'state_id' => 'nullable',
				'city_id' => 'nullable',
				'zipcode' => 'nullable',
				'account_holder_name' => 'nullable',
				'bank_name' => 'nullable',
				'account_number' => 'nullable',
				'ifsc_code' => 'nullable',
				'nominee_name' => 'nullable',
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
				
			]);
			$uploaded = '';	
			if($request->isMethod('post')){
				
				$customer = User::where('id',$request->id)->firstOrFail();
				if($customer->count() > 0){
					$customer->login_type = 'customer';
					$customer->name = $request->name;
					// $customer->password = Hash::make($request->password);
					$customer->mobile = $request->mobile;
					$customer->code = $request->customer_id;
					$customer->email = $request->email;
					$customer->created_by = \Auth::user()->id;
					$customer->status = 1;
					$customer->save();
					// $uploaded = true;	
					// dump($customer->customerdetails());
					// if($customer->customerdetails()->count() > 0){
						$customer->customerdetails()->update([
						'dob' => date('Y-m-d',strtotime($request->dob)),
						'age' => $request->age,
						'nationality' => $request->nationality,
						'sex' => $request->sex,
						'father_husband_wife' => $request->father_husband_wife,
						'address_one' => $request->address_one,
						'address_two' => $request->address_two,
						'city_id' => $request->city_id,
						'state_id' => $request->state_id,
						'country_id' => $request->country_id,
						'zipcode' => $request->zipcode,
						'account_holder_name' => $request->account_holder_name,
						'bank_name' => $request->bank_name,
						'account_number' => $request->account_number,
						'ifsc_code' => $request->ifsc_code,
						'nominee_name' => $request->nominee_name,
						'nominee_age' => $request->nominee_age,
						'nominee_sex' => $request->nominee_sex,
						'nominee_dob' => date('Y-m-d',strtotime($request->nominee_dob)),
						'nominee_relation_with_applicable' => $request->nominee_relation_with_applicable,
						'nominee_address_one' => $request->address_one,
						'nominee_address_two' => $request->address_two,
						'nominee_city_id' => $request->city_id,
						'nominee_state_id' => $request->state_id,
						'nominee_country_id' => $request->nominee_country_id,
						'nominee_zipcode' => $request->nominee_zipcode,
						
					]);
					// }else{
					// 	$customer->customerdetails()->create([
					// 	'dob' => date('Y-m-d',strtotime($request->dob)),
					// 	'age' => $request->age,
					// 	'nationality' => $request->nationality,
					// 	'sex' => $request->sex,
					// 	'father_husband_wife' => $request->father_husband_wife,
					// 	'address_one' => $request->address_one,
					// 	'address_two' => $request->address_two,
					// 	'city_id' => $request->city_id,
					// 	'state_id' => $request->state_id,
					// 	'country_id' => $request->country_id,
					// 	'zipcode' => $request->zipcode,
					// 	'account_holder_name' => $request->account_holder_name,
					// 	'bank_name' => $request->bank_name,
					// 	'account_number' => $request->account_number,
					// 	'ifsc_code' => $request->ifsc_code,
					// 	'nominee_name' => $request->nominee_name,
					// 	'nominee_age' => $request->nominee_age,
					// 	'nominee_sex' => $request->nominee_sex,
					// 	'nominee_dob' => date('Y-m-d',strtotime($request->nominee_dob)),
					// 	'nominee_relation_with_applicable' => $request->nominee_relation_with_applicable,
					// 	'nominee_address_one' => $request->address_one,
					// 	'nominee_address_two' => $request->address_two,
					// 	'nominee_city_id' => $request->city_id,
					// 	'nominee_state_id' => $request->state_id,
					// 	'nominee_country_id' => $request->nominee_country_id,
					// 	'nominee_zipcode' => $request->nominee_zipcode,
						
					// ]);
					// }					
						
					// $uploaded = true;
					return redirect('admin/customers')->with('success','Customer Updated Successfully!');
				}
				// if($uploaded ==  true){
					
				// }else{
				// 	return redirect('admin/customers')->with('info','Nothing Change');
				// }
				
				return redirect('admin/customers')->with('error','Something Went Wrong');
			}
			
		}
		public function view(Request $request,$id)
		{
			// dd($id);
			$customer = User::whereId(decrypt($id))->first();
			return view('admin.customers.view',compact('customer'));
		}
		public function status($id){
			$customer = User::where('id',decrypt($id))->firstOrFail();
			$customer->status = !$customer->status;
			$customer->save();
			return redirect()->back()->with('success','Status Updated Successfully!');
		}

		public function destroy($id){
			$customer = User::where('id',decrypt($id))->delete();
			CustomerDetail::where('customer_id',decrypt($id))->delete();
        	return redirect()->back()->with('success','Customer Deleted Successfully!');
		}


		public function getZip(Request $request){
			// dd($request->all());
        $zipcodeData = null;
        if($request->has('zip') && strlen($request->zip) == 6){
            $zipcode = Zipcode::whereZipcode($request->zip)->first();

				// dd($zipcode->country->name);
                $zipcodeData['country'] = $zipcode->country->id;
                $zipcodeData['country_name'] = $zipcode->country->name;
                $zipcodeData['state'] = $zipcode->state->id;
                $zipcodeData['state_name'] = $zipcode->state->name;
                $zipcodeData['city'] = $zipcode->city->id;
                $zipcodeData['city_name'] = $zipcode->city->name;
            
        }
        echo json_encode($zipcodeData);
        exit;
    }
	public function customerCommission($customerId){
		$customer = User::find(decrypt($customerId));
		$associate = User::whereLoginType('associate')->get();
		// dd($associate);
		
        if ($customer === null) {
           return redirect('/admin/customers')->withError('Customer Not Found');
        }
        //dd($product->productvariants());
        return view('admin/customers/customer_commission',['customer' => $customer,'associate'=>$associate]);
	}
	public function commissionStore(Request $request,$id){
		// dd($request->customer_invest);
		$this->validate($request,[
			'customer_invest' => 'required|not_in:0',
			'associate.*' =>'required|not_in:0|distinct',
			'commission.*'=>'required|not_in:0',
			'amount' => 'required|numeric|min:0',
			'deposit_date' => 'required',
			'payment_type' => 'required|min:1',
			'remarks' => 'required|min:1',
			
			],['associate.*.required'=>'The associate field is required.','associate.*.not_in'=>'The associate field is required.','associate.*.distinct'=>'The associate field has a duplicate value.','commission.*.required'=>'The commission field is required.','commission.*.not_in'=>'The selected Commission is invalid.']);
		if($request->sum_of_commission > 36){
			return redirect()->back()->with('error','Sum Of Commission is not Greater Than 36');
		}
		if($id === null){
			return redirect('admin/customers')->with('error','Invalid Request');
		}
		$customer = User::find($id);
		
		// dd($customer);

		$customer->customerinvestments()->create([
			'amount' => $request->amount,
			'deposit_date' => $request->deposit_date,
			'customer_interest_rate' => $request->customer_invest,
		]);

		// dd($customer);
		$customer_investment = CustomerInvestment::whereCustomerId($id)->first();

		foreach($request->associate as $key => $value){
			// dump($request->commission[$key]);
			$customer_investment->associatecommissions()->create([
				'associate_id' => $value,
				'customer_id' => $customer_investment->customer_id,
				// 'interest_amount' => $request->customer_invest,
				'commission_percent' =>$request->commission[$key],
				'status' => 1,
			]);
			
		}
		// $customer = User::where('id',$request->customer_id)->firstOrFail();
		// dd($customer);
		
		$customer_investment->customertransactions()->create([
			'customer_id' => $customer_investment->customer_id,
			'payment_type' =>$request->payment_type,
			'transaction_type' =>'deposit',
			'amount' => $request->amount,
			'cr_dr' => 'cr',
			'remarks' => $request->remarks,
			'bank_name' => $request->bank_name,
			'cheque_dd_number' => $request->cheque_dd_number,
			'deposit_date' => $request->deposit_date,
			'cheque_dd_date' => isset($request->date)?$request->date:Null,
			'status' => 1,
		]);
		return redirect('admin/customers')->with('success',$customer->name. ' Successfully Deposit & Distributed Commission Also');
		

	}
	public function searchAssociateCommission(Request $request){
		$search = explode(',',$request->get('term'));
			$search = end($search);
				// dd($search);
			$result = 'App\User'::select('id',DB::raw('name as label'))->where('name', 'LIKE', '%'. $search. '%')->whereLoginType('associate')->get();
          return response()->json($result);
	}
	public function customerDepositForm(Request $request){
			$customer = User::where('id',$request->id)->firstOrFail();
			// $profitMasters = Profit::whereStatus(1)->firstOrFail();
			// dd($profitMasters->profit_percent);
			return view('admin.customers.addtransaction',compact('customer'));
		}

	// public function customerDeposit(Request $request){
	// 	// dd($request->all());
	// 	$this->validate($request,[
	// 		'customer_id' => 'required|exists:users,id',
	// 		'amount' => 'required|numeric|min:0',
	// 		'deposit_date' => 'required',
	// 		'payment_type' => 'required|min:1',

	// 		'remarks' => 'required|min:1',
	// 	]);

	// 	$customer = User::where('id',$request->customer_id)->firstOrFail();
	// 	// dd($customer);
		
	// 	$customer->customertransactions()->create([
	// 		'payment_type' =>$request->payment_type,
	// 		'transaction_type' =>'deposit',
	// 		'amount' => $request->amount,
	// 		'cr_dr' => 'cr',
	// 		'remarks' => $request->remarks,
	// 		'bank_name' => $request->bank_name,
	// 		'cheque_dd_number' => $request->cheque_dd_number,
	// 		'deposit_date' => $request->deposit_date,
	// 		'cheque_dd_date' => isset($request->date)?$request->date:Null,
	// 		'status' => 1,
	// 	]);
	// 	return redirect()->back()->with('success','Successfully Added Investment Amount');
	// }

	public function customerWithdrawForm(Request $request)
	{
		$customer = User::where('id',$request->id)->firstOrFail();
		return view('admin.customers.addwithdraw',compact('customer'));
	}

	public function customerWithdraw(Request $request){
		// dd($request->all());
		$this->validate($request,[
			'customer_id' => 'required|exists:users,id',
			'amount' => 'required|numeric|min:0',
			'deposit_date' => 'required',
			'payment_type' => 'required|min:1',
			'remarks' => 'required|min:1',
		]);

		// $customer = User::where('id',$request->customer_id)->firstOrFail();
		$customer_investment = CustomerInvestment::whereCustomerId($request->customer_id)->first();
		// dd($customer);
		
		$customer_investment->customertransactions()->create([
			'customer_id' => $customer_investment->customer_id,
			'payment_type' =>$request->payment_type,
			'transaction_type' =>'withdraw',
			'amount' => $request->amount,
			'cr_dr' => 'dr',
			'remarks' => $request->remarks,
			'bank_name' => $request->bank_name,
			'cheque_dd_number' => $request->cheque_dd_number,
			'deposit_date' => $request->deposit_date,
			'cheque_dd_date' => isset($request->date)?$request->date:Null,
			'status' => 1,
		]);
		return redirect()->back()->with('success','Successfully Added Withdraw Amount');
	}


		public function generatePayout(){
			return view('admin.customers.generate-payout');
		}

		public function payoutGenerates(Request $request)
		{
			$this->validate($request,[
				'month'=>'required|numeric|min:1|not_in:0',
			]);
			
			$month = $request->month;
			$year = $request->year;
			
			// $customers = User::whereLoginType('customer')->get();
			// dd(date('Y-m-t',strtotime($year.'-'.$month)));
			if(date('Y-m-t',strtotime($year.'-'.$month)) > date('Y-m-d')){
				return redirect()->back()->with('error','Sorry! Can not generate-payout.');
			}
			$customer_investment = CustomerInvestment::where('deposit_date','<=',date('Y-m-t',strtotime($year.'-'.$month.'-01')))->get();
			
			foreach($customer_investment as $investment){
				$start_date = Carbon::parse($year.'-'.$month.'-01')->format('Y-m-d');
				$end_date = Carbon::parse($year.'-'.$month.'-30')->format('Y-m-d');

				
				if(Carbon::parse($investment->deposit_date)->format('Y-m') == Carbon::parse($year.'-'.$month)->format('Y-m')){
					$start_date = $investment->deposit_date;
					// dd($start_date);
				}
				// dd($investment->customertransactions->where('cr_dr','dr'));
				$customer_withdrawl = $investment->customertransactions->where('cr_dr','dr')->where('deposit_date','<=',$end_date)->where('deposit_date','>=',$start_date);
				// dd($customer_withdrawl);
				if($customer_withdrawl->count()){
					// dd('zdgsdg');
					foreach ($customer_withdrawl as $key => $withdrawl) {
						if(($start_date < $withdrawl->deposit_date)){
							$this->calculation($investment,$start_date,Carbon::parse($withdrawl->deposit_date)->subDays(1)->format('Y-m-d'));
						}
						$start_date = $withdrawl->deposit_date;
					}
					$this->calculation($investment,$start_date,$end_date);

				}else{
					// dd($start_date);
					$this->calculation($investment,$start_date,$end_date);
				}
			}
			return redirect()->back()->with('success','Successfully Generated Payouts Of '.Carbon::parse($year.'-'.$month)->format('Y-M'));
		}



		protected function calculation($investment,$start_date,$end_date){
			$interest_rate = $investment->customer_interest_rate;
			$balance = $investment->balance($end_date);
			// dd($balance);
			if($balance > 0){
				// $aa =  
				// dump($start_date); 
				// dump($end_date);
				$payable_days = (Carbon::parse($start_date)->diffInDays($end_date))+1;
				// dd($payable_days);
				$oneday_interest_amount = (($balance*$interest_rate/100)/12)/30;

				$amount = $oneday_interest_amount*$payable_days;
				$customerrewards = new CustomerReward;
				$customerrewards->customer_id = $investment->customer_id;
				$customerrewards->customer_investment_id = $investment->id;
				$customerrewards->amount = $amount;
				$customerrewards->month = Carbon::parse($start_date)->format('m');
				$customerrewards->year = Carbon::parse($start_date)->format('Y');
				$customerrewards->start_date = $start_date;
				$customerrewards->end_date = $end_date;
				$customerrewards->total_amount = $balance;
				$customerrewards->interest_percent = $investment->customer_interest_rate;
				$customerrewards->reward_type = 'interest';
				$customerrewards->save();

				$customerTransaction = new CustomerTransactions;
				$customerTransaction->customer_id = $investment->customer_id;
				$customerTransaction->customer_investment_id = $investment->id;
				$customerTransaction->amount = $amount;
				$customerTransaction->cr_dr = 'cr';
				$customerTransaction->payment_type = 'null';
				$customerTransaction->transaction_type = 'interest';
				$customerTransaction->deposit_date = $end_date;
				$customerTransaction->respective_table_id = $customerrewards->id;
				$customerTransaction->respective_table_name = 'customer_rewards';
				$customerTransaction->remarks = 'customer_interest';
				$customerTransaction->status = '1';
				$customerTransaction->save();

				foreach($investment->associatecommissions as $associate_reward){

					// $amount = ($balance*$associate_reward->commission_percent)/100;
					$amount = (($balance*$associate_reward->commission_percent/100)/12)/30;

					$associaterewards = new AssociateReward;
					$associaterewards->associate_id = $associate_reward->associate_id;
					$associaterewards->customer_id = $associate_reward->customer_id;
					$associaterewards->customer_investment_id = $associate_reward->customer_investment_id;
					$associaterewards->amount = $amount;
					$associaterewards->month = Carbon::parse($start_date)->format('m');
					$associaterewards->year = Carbon::parse($start_date)->format('Y');
					$associaterewards->start_date = $start_date;
					$associaterewards->end_date = $end_date;
					$associaterewards->total_amount = $balance;
					$associaterewards->commission_percent = $associate_reward->commission_percent;
					$associaterewards->reward_type = 'commission';
					$associaterewards->save();

					$customerTransaction = new AssociateTransactions;
					$customerTransaction->associate_id = $associate_reward->associate_id;
					$customerTransaction->customer_id = $associate_reward->customer_id;
					$customerTransaction->amount = $amount;
					$customerTransaction->cr_dr = 'cr';
					$customerTransaction->payment_type = 'null';
					$customerTransaction->transaction_type = 'commission';
					$customerTransaction->deposit_date = $end_date;
					$customerTransaction->respective_table_id = $associaterewards->id;
					$customerTransaction->respective_table_name = 'associate_rewards';
					$customerTransaction->remarks = 'associate_commission';
					$customerTransaction->status = '1';
					$customerTransaction->save();
				}
			}
		}

		// public function payoutGenerates(Request $request)
		// {
		// 	$this->validate($request,[
		// 		'month'=>'required|numeric|min:1|not_in:0',
		// 	]);
			
		// 	$month = $request->month;
		// 	$year = $request->year;
		// 	$start_date = 1;
		// 	$customers = User::whereLoginType('customer')->get();
		// 	$customer_transactions = CustomerTransactions::all();

		// 	foreach($customers as $customer){
		// 		$totalcr =	$customer->customertransactions->where('cr_dr','cr')->sum('amount');
		// 		$totaldr =	$customer->customertransactions->where('cr_dr','dr')->sum('amount');
		// 		$total = $totalcr-$totaldr;
		// 		foreach($customer->customerinvestments as $investment ){
		// 			// dump($investment);
		// 			$customer_interest_percent = $total*$investment->customer_interest_rate/100;
		// 		}
		// 		foreach ($customer_transactions as $transaction) {
		// 			if($transaction->cr_dr == 'cr'){
		// 				if(date('Y-m',strtotime($transaction->deposit_date)) != date('Y-m')){
		// 					// $total
		// 				}
		// 			}else if($transaction->cr_dr == 'dr'){
		// 				if(date('Y-m',strtotime($transaction->deposit_date)) != date('Y-m')){
							
		// 				}
		// 			}
		// 		}
		// 	}
		// 	// $associate
		// 	return redirect()->back()->with('success','Successfully Generated Payouts Of '.$month);
		// }

	
}
 ?>