<?php 
	namespace App\Http\Controllers\Admin;
	use Illuminate\Support\Collection;
	use Illuminate\Http\Request;	
	use App\Http\Controllers\Controller;
	use Illuminate\Support\Facades\Hash;
	use Illuminate\Support\Facades\Auth;
	use App\Model\CustomerDetail;
	use App\Model\CustomerReward;
	use App\Model\CustomerRewardTemp;
	use App\Model\AssociateReward;
	use App\Model\AssociateRewardTemp;
	use App\Model\CustomerInvestment;
	use App\Model\CustomerTransactions;
	use App\Model\CustomerTransactionTemp;
	use App\Model\AssociateTransactions;
	use App\Model\AssociateTransactionTemp;
	use App\Model\CustomerInterestPercentage;
	use App\Model\AssociateCommissionPercentage;
	use App\Model\CustomerSecurityCheque;
	use App\Model\CompanyBank;
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
		// public function index(){
	 //      	if(\Auth::user()->login_type == 'superadmin' || \Auth::user()->login_type == 'employee'){
		// 		$users = User::whereLoginType('customer')->orderBy('created_at')->paginate(10);
		// 		$countries = Country::get()->pluck('name','id');
	 //        	return view('admin.customers.index',compact('users','countries'));
	 //        }
		// }
		public function index(Request $request){
			// dd($request->all());
	      	if(\Auth::user()->login_type == 'superadmin' || \Auth::user()->login_type == 'employee'){
				$users = User::whereLoginType('customer');
		        $countries = Country::get()->pluck('name','id');
		        if($request->customer_name != null && $request->has('customer_name') ){
		            $users->where('name','like','%'.$request->customer_name.'%');
		        }else if($request->customer_mobile != null && $request->has('customer_mobile')){
		            $users->where('mobile','like','%'.$request->customer_mobile.'%');
		        }else if($request->customer_id != null && $request->has('customer_id')){
		            $users->where('code','like','%'.$request->customer_id.'%');
		        }else if($request->customer_bank_account != null && $request->has('customer_bank_account')){
		            $users->whereHas('customerdetails',function($q) use ($request){
		                return $q->where('account_number','like','%'.$request->customer_bank_account.'%');

		            });
		        }else if($request->state_id != null && $request->has('state_id')){
			        $users->whereHas('customerdetails',function($q) use ($request){
		                return $q->where('state_id',$request->state_id);
		            });
		        }else if($request->city_id != null && $request->has('city_id')){
		            $users->whereHas('customerdetails',function($q) use ($request){
		                return $q->where('city_id',$request->city_id);

		            });
		        }else if($request->customer_deposit != null && $request->has('customer_deposit')){
		            $users->whereHas('customertransactions',function($q) use ($request){
		                return $q->where('amount','like','%'.$request->customer_deposit.'%')->where('cr_dr','cr');

		            });
		        }
		        // dd($users->toSql());
		        $users = $users->orderBy('created_at')->paginate(10);
		        $states = State::all();
		        return view('admin.customers.index',compact('users','countries','states'));
	        }
		}

		public function add()
		{
			$countries = Country::get()->pluck('name','id');
			$associates = User::where('login_type','associate')->get();
        	$banks = CompanyBank::get(); 
        	return view('admin.customers.add',compact('countries','associates','banks'));
		}
		public function store(Request $request){
			// dd($request->all());
			$user_id = \Auth::user()->id;
				$this->validate($request,[
				'name' => 'required',
				'customer_id' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
				'dob' => 'nullable',
				'sex' => 'nullable',
				'father_husband_wife' => 'nullable',
				'nationality' => 'nullable',
				'email' => 'nullable',
				'mobile' => 'required',
				'customer_invest' => 'required',
				'address_one' => 'nullable',
				'address_two' => 'nullable',
				'country_id' => 'nullable',
				'state_id' => 'nullable',
				'city_id' => 'nullable',
				'zipcode' => 'nullable',
				'account_holder_name' => 'nullable',
				'bank_id' => 'nullable',
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
				'password' => 'required|confirmed',
				
			]);
			if($request->sum_of_commission > 36){
				return redirect()->back()->with('error','Sum Of Commission is not Greater Than 36');
			}

			DB::beginTransaction();

			try{
				$customer = new User;
				$customer->login_type = 'customer';
				$customer->name = $request->name;
				$customer->password = Hash::make($request->password);
				$customer->mobile = $request->mobile;
				$customer->code = $request->customer_id;
				$customer->email = $request->email;
				$customer->created_by = $user_id;
				$customer->status = 1;
				$customer->save();	
				// dump($customer->customerdetails());
				// dd($customer);
				$name = '';
		        if($request->has('image')){
		            $image = $request->file('image');
		            $name = time().'.'.$image->getClientOriginalExtension();
		            $destinationPath = public_path('images/customer');
		            $image->move($destinationPath, $name);
		        }

		        $customerscan = '';
		        if($request->has('scan_copy')){
		            $scancopy = $request->file('scan_copy');
		            $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
		            $destinationPath = public_path('images/chequeScanCopy');
		            $scancopy->move($destinationPath, $customerscan);
		        }
				$customer->customerdetails()->create([
					'image' => $name,
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
					// 'bank_id' => $request->bank_id?$request->bank_id:'',
					'bank_name' => $request->bank_name?$request->bank_name:'',
					'account_number' => $request->account_number,
					'ifsc_code' => $request->ifsc_code,
            		'pan_no' => $request->pan_no,
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
					'created_by' => $user_id,
					
				]);

				
					$customer->customersecuritycheque()->create([
						'cheque_issue_date' => $request->cheque_issue_date,
						'cheque_maturity_date' => $request->cheque_maturity_date,
						'cheque_bank_name' => $request->cheque_bank_name,
						'cheque_amount' => $request->cheque_amount,
						'scan_copy' => $customerscan,
						'status' => 1,
						'created_by' => $user_id,
					]);
				

				$customer->customerinterestpercents()->create([
					'interest_percent' => $request->customer_invest,
					'start_date' => date('Y-m-d'),
					'active_status' => 1,
					'created_by' => $user_id,
				]);
				//In next line - $request->associate_name in that place use $request->associate for autocomplete.
				if(sizeof(array_filter($request->associate_name))){
					foreach($request->associate_name as $key => $value){
						if($value>0){
							$customer->associatecommissions()->create([
								'associate_id' => $value,
								'customer_id' => $customer->id,
								'start_date' => date('Y-m-d'),
								'commission_percent' =>$request->commission[$key],
								'status' => 1,
								'created_by' => $user_id,
							]);
						}
					}
				}

				DB::commit();
			}catch(\Exception $e){
				dd($e->getMessage());
				DB::rollback();

			}
			
			// dd($customer->customerdetail());
		return redirect()->route('admin.customer_commission', [encrypt($customer->id)])->withSuccess('Customer Successfully Added');

		}

		public function edit(Request $request,$id){
			// dd($id);
			$countries = Country::get()->pluck('name','id');
			$states = State::get();
			$cities = City::get();
			$customer = User::where('id',decrypt($id))->firstOrFail();
			$security_cheque = CustomerSecurityCheque::where('customer_id',$customer->id)->first();
			// dd($security_cheque);
			$banks = CompanyBank::get();
			// dd($customer->customerdetails);
			return view('admin.customers.edit',compact('customer','countries','states',
				'cities','banks','security_cheque'));

		}

		public function update(Request $request){
			// dd($request->id);
			$this->validate($request,[
				'name' => 'required',
				'customer_id' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
				'dob' => 'nullable',
				'sex' => 'nullable',
				'father_husband_wife' => 'nullable',
				'nationality' => 'nullable',
				'email' => 'nullable',
				'mobile' => 'required',
				'address_one' => 'nullable',
				'address_two' => 'nullable',
				'country_id' => 'nullable',
				'state_id' => 'nullable',
				'city_id' => 'nullable',
				'zipcode' => 'nullable',
				'account_holder_name' => 'nullable',
				'bank_id' => 'nullable',
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
					$customer->updated_by = \Auth::user()->id;
					$customer->updated_at = date('Y-m-d H:i:s');
					$customer->status = 1;
					$customer->save();
					// $uploaded = true;	
					// dump($customer->customerdetails());
					// if($customer->customerdetails()->count() > 0){
					$name = '';
			        if($request->has('image')){
			            $image = $request->file('image');
			            $name = time().'.'.$image->getClientOriginalExtension();
			            $destinationPath = public_path('images/customer');
			            $image->move($destinationPath, $name);
			        }
			        $customerscan = '';
			        if($request->has('scan_copy')){
			            $scancopy = $request->file('scan_copy');
			            $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
			            $destinationPath = public_path('images/chequeScanCopy');
			            $scancopy->move($destinationPath, $customerscan);
			        }
					$customer->customerdetails()->update([
						'image' => $name,
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
						// 'bank_id' => $request->bank_id,
						'bank_name' => $request->bank_name,
						'account_number' => $request->account_number,
						'ifsc_code' => $request->ifsc_code,
	        			'pan_no' => $request->pan_no,
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
						'updated_by' => \Auth::user()->id,
						'updated_at' => date('Y-m-d H:i:s'),
					]);
					$customer->customersecuritycheque()->update([
						'cheque_issue_date' => $request->cheque_issue_date,
						'cheque_maturity_date' => $request->cheque_maturity_date,
						'cheque_bank_name' => $request->cheque_bank_name,
						'cheque_amount' => $request->cheque_amount,
						'scan_copy' => $customerscan,
						'status' => 1,
						'updated_by' => \Auth::user()->id,
						'updated_at' => date('Y-m-d H:i:s'),
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
					// 	'bank_id' => $request->bank_id,
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
		
		public function hold_status($id){
			$customer = User::where('id',decrypt($id))->firstOrFail();
			$customer->hold_status = !$customer->hold_status;
			$customer->save();
			return redirect()->back()->with('success','Hold Status Updated Successfully!');
		}

		public function destroy_confirmation(Request $request){
			// dd($request->all());
			$user = User::whereId($request->id)->first();
			
			// if($user){
			// 	return redirect('admin')
			// }
			return view('admin.customers.delete_customer',compact('user'));
		}

		public function destroy(Request $request){
			// dump($request->all());
			$this->validate($request,[
				'password'=>'required',
			]);
			
			$users = User::whereLoginType('superadmin')->first();
			$user =Hash::check($request->password,$users->password);
			
			if($user){
				User::where('id',$request->customer_id)->delete();
				CustomerDetail::where('customer_id',$request->customer_id)->delete();
				CustomerInterestPercentage::where('customer_id',$request->customer_id)->delete();
				CustomerReward::where('customer_id',$request->customer_id)->delete();
				CustomerTransactions::where('customer_id',$request->customer_id)->delete();
				CustomerInvestment::where('customer_id',$request->customer_id)->delete();
				AssociateCommissionPercentage::where('customer_id',$request->customer_id)->delete();
				AssociateReward::where('customer_id',$request->customer_id)->delete();
				AssociateTransactions::where('customer_id',$request->customer_id)->delete();
	        	return redirect()->back()->with('success','Customer Deleted Successfully!');
			}else{
	        	return redirect()->back()->with('error','Password is Wrong!');

			}
		}


		public function getZip(Request $request){
			// dd($request->all());
        $zipcodeData = null;
        if($request->has('zip') && strlen($request->zip) == 6){
            $zipcode = Zipcode::getCSC($request->zip)->first();

				// dd($zipcode->country);
                $zipcodeData['country'] = $zipcode->country->id;
                $zipcodeData['country_name'] = $zipcode->country;
                $zipcodeData['state'] = $zipcode->state;
                $zipcodeData['state_name'] = $zipcode->state;
                $zipcodeData['city'] = $zipcode->city;
                $zipcodeData['city_name'] = $zipcode->city;
            
        }
        // dd($zipcodeData);
        return $zipcodeData;
        // exit;
    }
	public function customerCommission($customerId){
		$customer = User::find(decrypt($customerId));
		$associate = User::whereLoginType('associate')->get();
        if ($customer === null) {
           return redirect('/admin/customers')->withError('Customer Not Found');
        }
        $companyBank = CompanyBank::get();
        return view('admin/customers/customer_commission',['customer' => $customer,'associate'=>$associate, 'companyBank'=>$companyBank]);
	}

	public function customerAssociateCommission($customerId){
		$customer = User::find(decrypt($customerId));
		// dd($customer->id);
		$customer_interest_percent = CustomerInterestPercentage::where('customer_id',decrypt($customerId))->where('end_date',Null)->where('active_status',1)->first();

		if($customer_interest_percent == null){
			return redirect('/admin/customers')->with('error','Customer Not Found');
		}

		// $customer_rewards = CustomerReward::where('month', '>=', Carbon::parse($customer_interest_percent->start_date)->month)->where('year', '>=', Carbon::parse($customer_interest_percent->start_date)->year)->first();
		// dd($customer_rewards);

		$associate = User::whereLoginType('associate')->get();
        if ($associate === null) {
           return redirect('/admin/customers')->withError('Customer Not Found');
        }

		// $associate_commission_percent = AssociateCommissionPercentage::where('customer_id',decrypt($customerId))->where('end_date',Null)->where('status',1)->first();
		// dd($associate_commission_percent);

		// if($associate_commission_percent == null){
		// 	return view('admin/customers/add_customer_associate_percent',['customer' => $customer,'associate'=>$associate,'customer_interest_percent'=>$customer_interest_percent]);
		// }

		// $associate_reward = AssociateReward::where('month', '>=', Carbon::parse($associate_commission_percent->start_date)->month)->where('year', '>=', Carbon::parse($associate_commission_percent->start_date)->year)->first();
		// dd($associate_reward);
		// if($customer_rewards == true){
		// 	dd('vd');
		// }else{
		// 	dd('das');
		// }
		
        return view('admin/customers/add_customer_associate_percent',['customer' => $customer,'associate'=>$associate,'customer_interest_percent'=>$customer_interest_percent]);
	}

	public function investmentStore(Request $request,$id)
	{
		// dd($request->all());
		if($request->amount <= 0){
			return redirect()->back()->with('error','Deposit amount shouldn\'t less than 0');
		}
		$user_id = \Auth::user()->id;
		// dd($user_id);
		$this->validate($request,[
			'amount' => 'required|numeric|min:0',
			'deposit_date' => 'required',
			'payment_type' => 'required|min:1',
			'remarks' => 'required|min:1',
			
			]);

		$customer = User::find(decrypt($id));
		// dd($customer);
		$interest_rate = CustomerInterestPercentage::where('customer_id',$customer->id)->where('active_status',1)->first();
		// dd($interest_rate);
		if(!$customer->customerinvestments->count()){
			$interest_rate->start_date = $request->deposit_date;
			$interest_rate->save();
		}

		$commissions = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('status',1)->get();//status = active_status
		// dd($customer->associatecommissions);
		if($customer->associatecommissions->count()){
			foreach ($commissions as $key => $commission) {
				$commission->start_date = $request->deposit_date;
				$commission->save();
			}
		}
		
		$customer->customerinvestments()->create([
			'amount' => $request->amount,
			'deposit_date' => $request->deposit_date,
			'customer_interest_rate' => $interest_rate->interest_percent,
			'created_by' => $user_id,
		]);

		$customer->customertransactions()->create([
			'customer_id' => $customer->id,
			'payment_type' =>$request->payment_type,
			'transaction_type' =>'deposit',
			'amount' => $request->amount,
			'cr_dr' => 'cr',
			'remarks' => $request->remarks,
			'bank_id' => $request->bank_id,
			'cheque_dd_number' => $request->cheque_dd_number,
			'deposit_date' => $request->deposit_date,
			'cheque_dd_date' => isset($request->date)?$request->date:Null,
			'status' => 1,
			'created_by' => $user_id,
		]);
		return redirect('admin/customers')->with('success',$customer->name. ' Successfully Deposit');

	}


	public function commissionStore(Request $request,$id){
		// dd($request->all());
		// dd(array_filter($request->associate));
		$this->validate($request,[
			'customer_invest' => 'required|numeric|min:0',
			
			]);
		if($request->sum_of_commission > 36){
			return redirect()->back()->with('error','Sum Of Commission is not Greater Than 36');
		}
		if($id === null){
			return redirect('admin/customers')->with('error','Invalid Request');
		}
		// dd($request->customer_invest);
		$customer = User::find(decrypt($id));
		// $customer_investment = CustomerInvestment::whereCustomerId(decrypt($id))->first();
		// $customer_investment->customer_interest_rate = $request->customer_invest;
		// $customer_investment->save();
		// dd($request->associate);
		if(sizeof(array_filter($request->associate_name))){
			foreach($request->associate_name as $key => $value){
				if($value>0){
					$customer->associatecommissions()->create([
						'associate_id' => $value,
						'customer_id' => $customer->id,
						'start_date' => date('Y-m-d'),//$customer_investment->deposit_date
						// 'interest_amount' => $request->customer_invest,
						'commission_percent' =>$request->commission[$key],
						'status' => 1,
					]);
				}
			}
		}
		return redirect('admin/customers')->with('success',$customer->name. ' Successfully Add Commission');
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

        	$banks = CompanyBank::get();
			return view('admin.customers.addtransaction',compact('customer','banks'));
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
	// 		'bank_id' => $request->bank_id,
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
		$companyBank = CompanyBank::get();
		return view('admin.customers.addwithdraw',compact('customer','companyBank'));
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
		$customer = User::whereId($request->customer_id)->first();
		// dd($customer);
		
		$customer->customertransactions()->create([
			'customer_id' => $request->customer_id,
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


	public function generatePayout(){
		return view('admin.customers.generate-payout');
	}

	public function payoutGenerates(Request $request)
	{
		// dd(CustomerReward::where('id',2)->first()->customertransactions);
		$this->validate($request,[
			'month'=>'required|numeric|min:1|not_in:0',
			'year'=>'required|numeric|min:1|not_in:0',
		]);
		
		$month = $request->month;
		$year = $request->year;
		
		$customers = User::whereHas('customertransactions',function($q) use ($year,$month){
			return $q->where('deposit_date', '<=', Carbon::parse($year.'-'.$month)->format('Y-m-t'));
		})->whereLoginType('customer')->get();
		
		// dd(date('Y-m-t',strtotime($year.'-'.$month)));
		if(date('Y-m-t',strtotime($year.'-'.$month)) > date('Y-m-d')){
			return redirect()->back()->with('error','Sorry! Can not generate-payout.');
		}

		foreach($customers as $customer){
			$this->calculateCustomerPayout($customer,$month,$year);
		}
		return redirect()->back()->with('success','Successfully Generated Payouts Of '.Carbon::parse($year.'-'.$month)->format('Y-M'))->withInput();
	}

	protected function calculateCustomerPayout($customer,$month,$year){

		CustomerReward::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'interest')->get()->each(function($reward){
				$reward->delete();
		});	//Each for cascade delete
		AssociateReward::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'commission')->get()->each(function($reward){
			$reward->delete();
		});	//Each for cascade delete

		// CustomerTransactions::where('deposit_date', Carbon::parse($year.'-'.$month)->format('Y-m'))->where('customer_id',$customer->id)->where('transaction_type', 'interest')->delete();
		// $deposit_date = '2020-09-05';
		// $end_date = Carbon::parse($deposit_date)->subDays(1)->format('Y-m-d');
		// $balance = $customer->balance($end_date);
		// dump($start_date);
		// dump($end_date);     
		// dd($balance);
		$start_date = Carbon::parse($year.'-'.$month.'-01')->format('Y-m-d');
		
		$customer_transactions = CustomerTransactions::where('deposit_date','>=',Carbon::parse($year.'-'.$month.'-01')->format('Y-m-d'))->where('deposit_date','<=',Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t'))->where('customer_id',$customer->id)->get();
		if($customer_transactions->count()){
			foreach($customer_transactions as $customer_transaction){
				// if($customer_transaction->customer_id=34){
				// dd($customer_transaction->deposit_date);
				
				$end_date = Carbon::parse($customer_transaction->deposit_date)->subDays(1)->format('Y-m-d');
				
				//$balance = $customer->balance($end_date);
				// dd($balance);
				if($customer_transaction->transaction_type == 'deposit'){	//$balance > 0 && 
					$end_date = Carbon::parse($customer_transaction->deposit_date)->format('Y-m-d');
				}
				// dump($start_date);
				// dd($end_date);
				$this->calculation($customer,$start_date,$end_date);
				
				if($customer_transaction->transaction_type == 'deposit'){	//$balance > 0 && 
					$start_date = Carbon::parse($customer_transaction->deposit_date)->addDays(1)->format('Y-m-d');
					// dump($start_date);
				}else{
					$start_date = Carbon::parse($customer_transaction->deposit_date)->format('Y-m-d');
					// dump($start_date);
				}
				
			}
			// 	// dump($customer_id);
			// 	// dump($start_date);
			// 	dump($end_date);
			// }
			// 	dd();
			if($start_date <= Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t')){
				$this->calculation($customer,$start_date,Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t'));
			}
		
	}else{
			$end_date = Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t');
			$this->calculation($customer,$start_date,$end_date);
		}
	}

	protected function calculation($customer,$start_date,$end_date){
		// $interest_rate = $investment->customer_interest_rate;
		// dump($start_date);
		// dd('vhjh');
		// dump($end_date);
		// dd($end_date);
		$balance = $customer->balance($end_date);
		// dd($balance);
		if($balance > 0){

			// dd($balance);
			$original_start_date = $interest_start_date = $start_date;
			$original_end_date = $interest_end_date = $end_date;
			$customer_interest_percents = CustomerInterestPercentage::where('customer_id',$customer->id)->where(function($q) use ($interest_start_date,$interest_end_date){
					return $q->where(function($qu) use ($interest_start_date,$interest_end_date){
							return $qu->where('start_date','>=', $interest_start_date)
									->where('start_date','<=', $interest_end_date);
						})->orWhere(function($qu) use ($interest_start_date,$interest_end_date){
							return $qu->where('end_date','>=', $interest_start_date)
									->where('end_date','<=', $interest_end_date);
						});
			})->get();
			// dd($customer_interest_percents);
			if(!$customer_interest_percents->count()){
				// $customer_interest_percents = CustomerInterestPercentage::where('active_status',1)->where('customer_id',$customer->id)->get();

				$customer_interest_percents = CustomerInterestPercentage::where('customer_id',$customer->id)->where(function($q) use ($start_date,$end_date){
					return $q->where(function($qu) use ($start_date,$end_date){
							return $qu->where('start_date','<', $start_date)
									->where('end_date','>', $start_date);
						});
				})->get();
				// dd($customer_interest_percents);
				if(!$customer_interest_percents->count()){
				 	$customer_interest_percents = CustomerInterestPercentage::where('active_status',1)->where('customer_id',$customer->id)->get();
				}
			}

			foreach($customer_interest_percents as $key => $customer_interest_percent){
				$swap_dates = false;
			
				if($original_start_date > $customer_interest_percent->start_date){
					$interest_start_date = $original_start_date;
				}

				if($customer_interest_percent->end_date == null){
					$interest_end_date = $original_end_date;
				}else if($customer_interest_percent->end_date != null && $customer_interest_percent->end_date < $original_end_date){
					$interest_end_date = $customer_interest_percent->end_date;
					$swap_dates = true;
				} 

				$interest_rate = $customer_interest_percent->interest_percent;
			
				// $a = "2020-10-05";
				// $b = "2020-10-06";
				// dd(Carbon::parse($interest_end_date)->format('Y-m-d'));

				if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-28')){
					$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+3;
				}else if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-29')){
						$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+2;
				}else if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-31')){
						$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date));
				// dd($payable_days);
				}else{
					$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+1;
				}
				
				// dd($payable_days);
				$oneday_interest_amount = (($balance*$interest_rate/100)/12)/30;

				if($payable_days > 0){
					$amount = $oneday_interest_amount*$payable_days;
				
				    $customerrewards = new CustomerReward;
					$customerrewards->customer_id = $customer->id;
					$customerrewards->month = Carbon::parse($interest_start_date)->format('m');
					$customerrewards->year = Carbon::parse($interest_start_date)->format('Y');
					$customerrewards->reward_type = 'interest';
					$customerrewards->amount = $amount;
					$customerrewards->start_date = $interest_start_date;
					$customerrewards->end_date = $interest_end_date;
					$customerrewards->payable_days = $payable_days;
					$customerrewards->total_amount = $balance;
					$customerrewards->interest_percent = $interest_rate;
					$customerrewards->save();

					
					// dd($customerTransaction);
					$customerTransaction = new CustomerTransactions;
					$customerTransaction->customer_id = $customer->id;
					$customerTransaction->transaction_type = 'interest';
					$customerTransaction->respective_table_id = $customerrewards->id;
					$customerTransaction->respective_table_name = 'customer_rewards';
					$customerTransaction->remarks = 'customer_interest';
					$customerTransaction->deposit_date = $interest_end_date;
					$customerTransaction->amount = $amount;
					$customerTransaction->cr_dr = 'cr';
					$customerTransaction->payment_type = 'null';
					$customerTransaction->status = '1';
					$customerTransaction->save();
				}
				if($swap_dates){
					$interest_start_date = Carbon::parse($interest_end_date)->addDays(1)->format('Y-m-d');
					$interest_end_date = $original_end_date;
				}


			}





			$associate_commission_percents = AssociateCommissionPercentage::where('customer_id',$customer->id)->where(function($q) use ($start_date,$end_date){
					return $q->where(function($qu) use ($start_date,$end_date){
							return $qu->where('start_date','>=', $start_date)
									->where('start_date','<=', $end_date);
						})->orWhere(function($qu) use ($start_date,$end_date){
							return $qu->where('end_date','>=', $start_date)
									->where('end_date','<=', $end_date);
						});
			})->get();
			
			if(!$associate_commission_percents->count()){
				// $associate_commission_percents = AssociateCommissionPercentage::where('status',1)->where('customer_id',$customer->id)->get();//status = active_status

				$associate_commission_percents = AssociateCommissionPercentage::where('customer_id',$customer->id)->where(function($q) use ($start_date,$end_date){
					return $q->where(function($qu) use ($start_date,$end_date){
							return $qu->where('start_date','<', $start_date)
									->where('end_date','>', $start_date);
						});
				})->get();

				
				if(!$associate_commission_percents->count()){
				 	$associate_commission_percents = AssociateCommissionPercentage::where('status',1)->where('customer_id',$customer->id)->get();//status = active_status
				}
			}

			foreach($associate_commission_percents as $key => $associate_commission_percent){
				$swap_dates = false;
				if($original_start_date > $associate_commission_percent->start_date){
					$start_date = $original_start_date;
				}
				if($associate_commission_percent->end_date == null){
					$end_date = $original_end_date;
				}else if($associate_commission_percent->end_date != null && $associate_commission_percent->end_date < $original_end_date){
					$end_date = $associate_commission_percent->end_date;
					$swap_dates = true;
				} 
				// dd($associate_commission_percent->associate_id);
				$commission_rate = $associate_commission_percent->commission_percent;
				// dd($commission_rate);
				// dd($commission_rate);




				if(Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-t') && Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-28')){

					$payable_days = (Carbon::parse($start_date)->diffInDays($end_date))+3;

				}else if(Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-t') && Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-29')){

						$payable_days = (Carbon::parse($start_date)->diffInDays($end_date))+2;

				}else if(Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-t') && Carbon::parse($end_date)->format('Y-m-d') == Carbon::parse($end_date)->format('Y-m-31')){

						$payable_days = (Carbon::parse($start_date)->diffInDays($end_date));
				
				}else{

					$payable_days = (Carbon::parse($start_date)->diffInDays($end_date))+1;
				}
				
				
				$oneday_commission_amount = (($balance*$commission_rate/100)/12)/30;

				if($payable_days > 0){
					$amount = $oneday_commission_amount*$payable_days;
				
				
				    $associaterewards = new AssociateReward;
				    // dd($associate_commission_percent->associate_id);
					$associaterewards->customer_id = $customer->id;
					$associaterewards->associate_id = $associate_commission_percent->associate_id;
					$associaterewards->month = Carbon::parse($start_date)->format('m');
					$associaterewards->year = Carbon::parse($start_date)->format('Y');
					$associaterewards->reward_type = 'commission';
					$associaterewards->payable_days = $payable_days;
					$associaterewards->amount = $amount;
					$associaterewards->start_date = $start_date;
					$associaterewards->end_date = $end_date;
					$associaterewards->total_amount = $balance;
					$associaterewards->commission_percent = $commission_rate;
					$associaterewards->save();

					
					// dd($customerTransaction);
					$associateTransaction = new AssociateTransactions;
					$associateTransaction->associate_id = $associate_commission_percent->associate_id;
					$associateTransaction->customer_id = $customer->id;
					$associateTransaction->transaction_type = 'commission';
					$associateTransaction->respective_table_id = $associaterewards->id;
					$associateTransaction->respective_table_name = 'associate_rewards';
					$associateTransaction->remarks = 'associate_commission';
					$associateTransaction->deposit_date = $end_date;
					$associateTransaction->amount = $amount;
					$associateTransaction->cr_dr = 'cr';
					$associateTransaction->payment_type = 'null';
					$associateTransaction->status = '1';
					$associateTransaction->save();
				}
				if($swap_dates){
					$start_date = Carbon::parse($end_date)->addDays(1)->format('Y-m-d');
					$end_date = $original_end_date;
				}


			}



			// foreach($investment->associatecommissions as $associate_reward){

			// 	// $amount = ($balance*$associate_reward->commission_percent)/100;
			// 	$amount = (($balance*$associate_reward->commission_percent/100)/12)/30;

			// 	$associaterewards = AssociateReward::where('month', Carbon::parse($start_date)->format('m'))->where('year', Carbon::parse($start_date)->format('Y'))->where('associate_id',$associate_reward->associate_id)->where('customer_id',$associate_reward->customer_id)->where('customer_investment_id', $associate_reward->customer_investment_id)->where('reward_type', 'commission')->first();

			// 	if(!$associaterewards){
			// 		$associaterewards = new AssociateReward;
			// 		$associaterewards->associate_id = $associate_reward->associate_id;
			// 		$associaterewards->customer_id = $associate_reward->customer_id;
			// 		$associaterewards->customer_investment_id = $associate_reward->customer_investment_id;
			// 		$associaterewards->month = Carbon::parse($start_date)->format('m');
			// 		$associaterewards->year = Carbon::parse($start_date)->format('Y');
			// 		$associaterewards->reward_type = 'commission';
			// 	}

			// 	$associaterewards->amount = $amount;
			// 	$associaterewards->start_date = $start_date;
			// 	$associaterewards->end_date = $end_date;
			// 	$associaterewards->total_amount = $balance;
			// 	$associaterewards->commission_percent = $associate_reward->commission_percent;
			// 	$associaterewards->save();

			// 	$customerTransaction = AssociateTransactions::where('deposit_date', Carbon::parse($end_date)->format('Y-m'))->where('associate_id',$associate_reward->associate_id)->where('customer_id',$associate_reward->customer_id)->where('customer_investment_id', $associate_reward->customer_investment_id)->where('transaction_type', 'interest')->first();

			// 	if(!$associaterewards){
			// 		$customerTransaction = new AssociateTransactions;
			// 		$customerTransaction->associate_id = $associate_reward->associate_id;
			// 		$customerTransaction->customer_id = $associate_reward->customer_id;
			// 		$customerTransaction->customer_investment_id = $associate_reward->customer_investment_id;
			// 		$customerTransaction->respective_table_id = $associaterewards->id;
			// 		$customerTransaction->respective_table_name = 'associate_rewards';
			// 		$customerTransaction->remarks = 'associate_commission';
			// 		$customerTransaction->transaction_type = 'commission';
			// 	}
				
			// 	$customerTransaction->amount = $amount;
			// 	$customerTransaction->cr_dr = 'cr';
			// 	$customerTransaction->payment_type = 'null';
			// 	$customerTransaction->deposit_date = $end_date;
			// 	$customerTransaction->status = '1';
			// 	$customerTransaction->save();
			// }

		}
	}

	// protected function calculation($investment,$start_date,$end_date){
	// 	$interest_rate = $investment->customer_interest_rate;
	// 	$balance = $investment->balance($end_date);
	// 	if($balance > 0){
	// 		$payable_days = (Carbon::parse($start_date)->diffInDays($end_date))+1;
	// 		if($payable_days > 30){
	// 			$payable_days = 30;
	// 		}
	// 		$oneday_interest_amount = (($balance*$interest_rate/100)/12)/30;
	// 		$amount = $oneday_interest_amount*$payable_days;

	// 		$customerrewards = CustomerReward::where('month', Carbon::parse($start_date)->format('m'))->where('year', Carbon::parse($start_date)->format('Y'))->where('customer_id',$investment->customer_id)->where('customer_investment_id', $investment->id)->where('reward_type', 'interest')->first();
	// 		// dd($customerrewards);
	// 		if (!$customerrewards) {
	// 		    $customerrewards = new CustomerReward;
	// 			$customerrewards->customer_id = $investment->customer_id;
	// 			$customerrewards->customer_investment_id = $investment->id;
	// 			$customerrewards->month = Carbon::parse($start_date)->format('m');
	// 			$customerrewards->year = Carbon::parse($start_date)->format('Y');
	// 			$customerrewards->reward_type = 'interest';
	// 		}
			
	// 		$customerrewards->amount = $amount;
	// 		$customerrewards->start_date = $start_date;
	// 		$customerrewards->end_date = $end_date;
	// 		$customerrewards->total_amount = $balance;
	// 		$customerrewards->interest_percent = $investment->customer_interest_rate;
	// 		$customerrewards->save();

	// 		$customerTransaction = CustomerTransactions::where('deposit_date', Carbon::parse($end_date)->format('Y-m'))->where('customer_id',$investment->customer_id)->where('customer_investment_id', $investment->id)->where('transaction_type', 'interest')->first();
	// 		// dd($customerTransaction);
	// 		if(!$customerrewards){
	// 			$customerTransaction = new CustomerTransactions;
	// 			$customerTransaction->customer_id = $investment->customer_id;
	// 			$customerTransaction->customer_investment_id = $investment->id;
	// 			$customerTransaction->transaction_type = 'interest';
	// 			$customerTransaction->respective_table_id = $customerrewards->id;
	// 			$customerTransaction->respective_table_name = 'customer_rewards';
	// 			$customerTransaction->remarks = 'customer_interest';
	// 		}
	// 		$customerTransaction->deposit_date = $end_date;
	// 		$customerTransaction->amount = $amount;
	// 		$customerTransaction->cr_dr = 'cr';
	// 		$customerTransaction->payment_type = 'null';
	// 		$customerTransaction->status = '1';
	// 		$customerTransaction->save();

	// 		foreach($investment->associatecommissions as $associate_reward){

	// 			// $amount = ($balance*$associate_reward->commission_percent)/100;
	// 			$amount = (($balance*$associate_reward->commission_percent/100)/12)/30;

	// 			$associaterewards = AssociateReward::where('month', Carbon::parse($start_date)->format('m'))->where('year', Carbon::parse($start_date)->format('Y'))->where('associate_id',$associate_reward->associate_id)->where('customer_id',$associate_reward->customer_id)->where('customer_investment_id', $associate_reward->customer_investment_id)->where('reward_type', 'commission')->first();

	// 			if(!$associaterewards){
	// 				$associaterewards = new AssociateReward;
	// 				$associaterewards->associate_id = $associate_reward->associate_id;
	// 				$associaterewards->customer_id = $associate_reward->customer_id;
	// 				$associaterewards->customer_investment_id = $associate_reward->customer_investment_id;
	// 				$associaterewards->month = Carbon::parse($start_date)->format('m');
	// 				$associaterewards->year = Carbon::parse($start_date)->format('Y');
	// 				$associaterewards->reward_type = 'commission';
	// 			}

	// 			$associaterewards->amount = $amount;
	// 			$associaterewards->start_date = $start_date;
	// 			$associaterewards->end_date = $end_date;
	// 			$associaterewards->total_amount = $balance;
	// 			$associaterewards->commission_percent = $associate_reward->commission_percent;
	// 			$associaterewards->save();

	// 			$customerTransaction = AssociateTransactions::where('deposit_date', Carbon::parse($end_date)->format('Y-m'))->where('associate_id',$associate_reward->associate_id)->where('customer_id',$associate_reward->customer_id)->where('customer_investment_id', $associate_reward->customer_investment_id)->where('transaction_type', 'interest')->first();

	// 			if(!$associaterewards){
	// 				$customerTransaction = new AssociateTransactions;
	// 				$customerTransaction->associate_id = $associate_reward->associate_id;
	// 				$customerTransaction->customer_id = $associate_reward->customer_id;
	// 				$customerTransaction->customer_investment_id = $associate_reward->customer_investment_id;
	// 				$customerTransaction->respective_table_id = $associaterewards->id;
	// 				$customerTransaction->respective_table_name = 'associate_rewards';
	// 				$customerTransaction->remarks = 'associate_commission';
	// 				$customerTransaction->transaction_type = 'commission';
	// 			}
				
	// 			$customerTransaction->amount = $amount;
	// 			$customerTransaction->cr_dr = 'cr';
	// 			$customerTransaction->payment_type = 'null';
	// 			$customerTransaction->deposit_date = $end_date;
	// 			$customerTransaction->status = '1';
	// 			$customerTransaction->save();
	// 		}
	// 	}
	// }

		

	public function profile(){
        return view('admin.customers.profile');
    }
    
    public function changePassword(){
    	dd('zfs');
        return view('admin.customers.changePassword');
    }

    public function changePasswordS(Request $request){
        $this->validate($request,['new_password' => 'required|same:cpassword' ]);
         $user = User::whereId(\Auth::user()->id)->first();
            // dd($user);
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect('customer/Profile')->with('success','Password Change Successfully!');

    }
	public function transactionHistory(){
		$u = User::whereLoginType('customer')->whereId(Auth::user()->id)->first();
		$users = [];
		foreach($u->customertransactions as $us){
			array_push($users,$us);
		}

		$interest = CustomerTransactions::select(DB::raw("*, CONCAT(YEAR(deposit_date),MONTH(deposit_date)) as month_year"))
    	->where('transaction_type','interest')
   		->where('customer_id',Auth::user()->id)
   		->orderByDesc('deposit_date')
   		->get()
        ->unique('month_year')
        ->pluck('id');
        $transactions = CustomerTransactions::where(function($q){
            return $q->where('transaction_type','!=','interest')->where('customer_id',Auth::user()->id);
        })
        ->orWhereIn('id',$interest)
        ->orderByDesc('deposit_date')
        ->paginate(10);

		
		return view('admin/customers/customer-transaction-history',compact('users','transactions'));
	}
	public function viewMyInvestment(){
		$users = 'App\Model\CustomerInvestment'::whereCustomerId(Auth::user()->id)->paginate();

		return view('admin/customers/customer-my-investment',compact('users'));
	}
	public function viewInvestmentTransaction($id){

	$customer_investment_id = decrypt($id);
	$users = 'App\Model\CustomerTransactions'::whereCustomerInvestmentId($customer_investment_id)->paginate(10);
	$credit =0;
	$debit =0;
	foreach($users as $key => $user){
		$credit = $user::where('cr_dr','cr')->whereCustomerInvestmentId($customer_investment_id)->sum('amount');
		$debit = $user::where('cr_dr','dr')->whereCustomerInvestmentId($customer_investment_id)->sum('amount');
	}
	
	
	// dd($credit);
	return view('admin/customers/customer-investment-transactions',compact('users','credit','debit'));

	}

	public function customer_investments(Request $request)
	{
		// $invester_id = $request->id;

		// dd(decrypt($request->id));
		$customer_deposits = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit');

		// $id = isset($request->id)?decrypt($request->id):$request->id;
		// dd($id);
		if($request->has('id') && $request->id != '' ){
			$customer_deposits->where('customer_id',$request->id);
			// dd($customer_deposits);
		}
		$customer_deposits = $customer_deposits->paginate(20);
		// dd($customer_deposits);
		return view('admin.customers.admin_customer_investment_report',compact('customer_deposits'));
	}

	public function autocompleteInterest(Request $request)
	{
		if($request->has('term')){
            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,",", mobile) AS label'))->where('name','like','%'.$request->input('term').'%')->get();
            return response()->json($results);
        }
	}

	// ->map(function($customer){
 //            	$customer->id=encrypt($customer->id);
 //            	return $customer;
 //            })

	// public function searchCustomerInvest(Request $request)
	// {
	// 	// dd($request->id);
	// 	$customer_deposits = CustomerTransactions::where('customer_id',$request->id)->where('cr_dr','cr')->where('transaction_type','deposit')->get();
	// 	// dd($customer_deposits);
	// 	return view('admin.customers.admin_customer_investment_report',compact('customer_deposits'));
	// }

	public function customer_interest_invest($customerId)
	{
		$user = User::where('id',decrypt($customerId))->where('login_type','customer')->firstOrFail();
    	// dd($user);

    	$interest = CustomerTransactions::select(DB::raw("*, CONCAT(YEAR(deposit_date),MONTH(deposit_date)) as month_year"))
    	->where('transaction_type','interest')
   		->where('customer_id',$user->id)
   		->orderByDesc('deposit_date')
   		->get()
        ->unique('month_year')
        ->pluck('id');

// dd($interest);
        // $interest = CustomerTransactions::where('transaction_type','interest')->where('customer_id',$user->id)->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"))->pluck('id'); 
        // dd($interest);
        // select(DB::raw('*, max(created_at) as created_at')) 
        $transactions = CustomerTransactions::where(function($q) use ($user){
            return $q->where('transaction_type','!=','interest')->where('customer_id',$user->id);
        })
        ->orWhereIn('id',$interest)
        // ->orWhereIn('id', function($query) use ($user){
        //     $query->select('id')
        //     ->from(with(new CustomerTransactions)->getTable())
        //     ->where('transaction_type','reward')
        //     ->where('customer_id',$user->id)
        //     ->groupBy(DB::raw("CONCAT(YEAR(deposit_date),MONTH(deposit_date))"));
        // })
        ->orderByDesc('deposit_date')
        // ->toSql();
        ->paginate(10);
        // dd($transactions);
		return view('admin.customers.admin_customer_alltransactions',compact('transactions','user'));
	}

	public function updateInterestRateForm(Request $request){
		// dd($request->all());
		// $customer = User::where('id',$request->id)->where('login_type','customer')->firstOrFail();

		$ids = explode(',',$request->id);
		// dd($ids);
		$customer = User::where('id',$ids[0])->where('login_type','customer')->firstOrFail();
		$cust_int_per = CustomerInterestPercentage::where('id',$ids[1])->firstOrFail();


		return view('admin.customers.customer_edit_interest',compact('customer','cust_int_per'));
	}


	public function updateInterestRate(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'update_customer_interest' => 'required',
		]);
		$user_id = \Auth()->user()->id;
		$cust_interst = CustomerInterestPercentage::where('customer_id',$request->customer_id)->where('id',$request->table_id)->first();
		$cust_interst->interest_percent = $request->update_customer_interest; 
		$cust_interst->updated_by = $user_id;
		$cust_interst->updated_at = date('Y-m-d');
		$cust_interst->save();
		return redirect()->back()->with('success','Update Interest Rate Successfully!');
	}


	public function updateCommissionForm(Request $request)
	{
		// dd($request->all());
		$ids = explode(',',$request->id);
		// dd($ids);
		$customer = User::where('id',$ids[0])->where('login_type','customer')->firstOrFail();
		$associate = User::where('id',$ids[1])->where('login_type','associate')->firstOrFail();
		$associate_comm_per = AssociateCommissionPercentage::where('id',$ids[2])->firstOrFail();

		return view('admin.customers.update_associate_commission',compact('associate','customer','associate_comm_per'));
	}

	public function updateCommission(Request $request)
	{
		// dd($request->all());

		$this->validate($request,[
			'update_associate_commission' => 'required',
		]);

		$associatecommission = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->where('id',$request->table_id);
		$associatecommission->commission_percent = $request->update_associate_commission; 
		$associatecommission->updated_by = $user_id;
		$associatecommission->updated_at = date('Y-m-d');
		$associatecommission->save();
		return redirect()->back()->with('success','Update Commission Percent Successfully!');
	}


	public function editInterestForm(Request $request)
	{
		// dd($request->id);
		$customer = User::where('id',$request->id)->where('login_type','customer')->firstOrFail();
		return view('admin.customers.edit_interest',compact('customer'));
	}

	public function editInterest(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'customer_interest' => 'required',
			'applicable_date' => 'required',
		]);
		$user_id = \Auth()->user()->id;
		$cust_interst = CustomerInterestPercentage::where('customer_id',$request->customer_id)->where('active_status',1)->first();
		if($cust_interst === null){
			return redirect()->back()->with('error','Customer Not Found');
		}else if($cust_interst->start_date > $request->applicable_date){
			return redirect()->back()->with('error','Applicable date is not valid');
		}
		$cust_interst->active_status = 0;
		$cust_interst->end_date = Carbon::parse($request->applicable_date)->subDays(1)->format('Y-m-d');
		$cust_interst->updated_at = date('Y-m-d');
		$cust_interst->save();

		$interst = new CustomerInterestPercentage;
		$interst->customer_id = $request->customer_id;
		$interst->start_date = $request->applicable_date;
		$interst->interest_percent = $request->customer_interest;
		$interst->active_status = 1;
		$interst->created_by = $user_id;
		$interst->updated_by = $user_id;
		$interst->save();
		return redirect()->back()->with('success','New Interest Rate Applicable on Customer Successfully!');
	}

	public function customerinterestdestroy($id){
		// dd(decrypt($id));
		$customerInterest = CustomerInterestPercentage::where('customer_id',decrypt($id))->where('end_date',null)->where('active_status',1)->delete();
    	return redirect()->back()->with('success','Customer Interest Deleted Successfully!');
	}


	public function editAssociateWithdrawlForm(Request $request)
	{
		$ids = explode(',',$request->id);
		// dd($ids);
		$customer = User::where('id',$ids[0])->where('login_type','customer')->firstOrFail();
		$associate = User::where('id',$ids[1])->where('login_type','associate')->firstOrFail();
		return view('admin.customers.edit_associate_withdrawl',compact('associate','customer'));
	}


	public function editCommission(Request $request)
	{
		// dd($request->all());

		$this->validate($request,[
			'associate_withdrawl' => 'required',
			'start_date' => 'required',
		]);

		$customer_interest_percent = CustomerInterestPercentage::select('interest_percent')->where('customer_id',$request->customer_id)->where('active_status',1)->firstOrFail();

		$totalassociatecommission = AssociateCommissionPercentage::where('associate_id','!=',$request->associate_id)->where('customer_id',$request->customer_id)->where('status',1)->sum('commission_percent');
		if(($totalassociatecommission+$request->associate_withdrawl+$customer_interest_percent->interest_percent)>36){
			return redirect()->back()->with('error','Sum of Interest and Commission can not be greater than 36!');
		}

		$user_id = \Auth()->user()->id;

		$ass_commission = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->where('status',1)->first();//status = active_status
		// dd($ass_commission);
		if($ass_commission === null){
			return redirect()->back()->with('error','Associate Not Found');
		}
		$ass_commission->status = 0;
		$ass_commission->end_date = Carbon::parse($request->start_date)->subDays(1)->format('Y-m-d');
		$ass_commission->updated_at = date('Y-m-d');
		$ass_commission->save();

		$commission = new AssociateCommissionPercentage;
		$commission->customer_id = $ass_commission->customer_id;
		$commission->associate_id = $request->associate_id;
		$commission->start_date = $request->start_date;
		$commission->commission_percent = $request->associate_withdrawl;
		$commission->status = 1;
		$commission->created_by = $user_id;
		$commission->updated_by = $user_id;
		$commission->save();
		return redirect()->back()->with('success','New Commission Rate Applicable on Associate Successfully!');
	}

	public function associateCommissionDestroy($id){
		// dd(decrypt($id));
		$customerInterest = AssociateCommissionPercentage::where('associate_id',decrypt($id))->where('status',1)->delete();//status = active_status
		// dd($customerInterest);
    	return redirect()->back()->with('success','Associate Commission Deleted Successfully!');
	}
	 public function getCustomers(Request $request){
        if($request->has('term')){
            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,"-", code) AS label'))->where('name','like','%'.$request->input('term').'%')->where('login_type','customer')->get();
            return response()->json($results);
        }
    }

    public function deleteCustomerDeposit($id){
		dd(decrypt($id));
		CustomerInterestPercentage::where('customer_id',decrypt($id))->delete();
		CustomerReward::where('customer_id',decrypt($id))->delete();
		CustomerTransactions::where('customer_id',decrypt($id))->delete();
		CustomerInvestment::where('customer_id',decrypt($id))->delete();
		AssociateCommissionPercentage::where('customer_id',decrypt($id))->delete();
		AssociateReward::where('customer_id',decrypt($id))->delete();
		AssociateTransactions::where('customer_id',decrypt($id))->delete();
    	return redirect()->back()->with('success','Customer Deleted Successfully!');
	}


	public function security_cheque()
	{
		$security_cheque = CustomerSecurityCheque::where('cheque_issue_date', '!=', NULL)->paginate(100);
		// dd($security_cheque);
		// $users = User::where('login_type','customer')->paginate(20);
		// $cheques = '';
		// foreach ($users as $key => $user) {
		// 	$cheques = $user->customersecuritycheque;
		// }
		return view('admin.customersecuritycheques.cheque_index',compact('security_cheque'));
	}


	

	public function security_cheque_alert()
	{
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d',strtotime($start_date.'+7 day'));
		// dd($end_date);
		$security_cheque_count = CustomerSecurityCheque::whereBetween('cheque_maturity_date',[$start_date,$end_date])->where('cheque_issue_date', '!=', NULL)->count();
		// dd($security_cheque_count);
		$security_cheque = CustomerSecurityCheque::whereBetween('cheque_maturity_date',[$start_date,$end_date])->where('cheque_issue_date', '!=', NULL)->paginate(100);
		// dd($security_cheque);
		return view('admin.customersecuritycheques.security_cheque_alert',compact('security_cheque_count','security_cheque'));
	}

	public function generatedPayouts(){
		$payouts = CustomerReward::groupBy('year','month')->paginate(12);
		// dd($payouts);
		return view('admin.customers.generated-payouts',compact('payouts'));
	}


	public function delete_gen_payouts($month,$year){
		$dated = date($year.'-'.$month.'-01');
		$datet = date($year.'-'.$month.'-t');
		CustomerTransactions::whereBetween('deposit_date', [$dated, $datet])->where('transaction_type','interest')->delete();
		CustomerReward::where('month',$month)->where('year',$year)->delete();
		AssociateReward::where('month',$month)->where('year',$year)->delete();
		AssociateTransactions::whereBetween('deposit_date', [$dated, $datet])->where('transaction_type','commission')->delete();
		return redirect()->back()->with('success','All Transactions Are Deleted Successfully'.$month.'-'.$year);
	}


	public function on_hold()
	{
		$customers = User::where('hold_status',0)->paginate(20);
		return view('admin.customers.on_hold',compact('customers'));
	}


	public function edit_depositForm(Request $request)
	{
		// dd($request->all());
		$ids = explode(',',$request->id);
		// dd($ids[1]);
		$customer = CustomerTransactions::where('customer_id',$ids[0])->where('id',$ids[1])->where('cr_dr','cr')->where('transaction_type','deposit')->first();
		// dd($customer);
		$customer_invest = CustomerInvestment::where('customer_id',$request->id)->first();
		// dd($customers);
		return view('admin.customers.edit_deposit',compact('customer','customer_invest'));
	}


	public function edit_deposit(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'customer_deposit' => 'required',
		]);
		$customer = CustomerTransactions::where('customer_id',$request->customer_id)->where('id',$request->table_id)->first();
		$customer->amount = $request->customer_deposit;
		$customer->deposit_date = $request->deposit_date;
		$customer->updated_at = date('Y-m-d');
		$customer->updated_by = \Auth::user()->id;
		$customer->save();
		$cust_investment = CustomerInvestment::where('customer_id', $request->customer_id)->first();
		$cust_investment->amount = $request->customer_deposit;
		$cust_investment->deposit_date = $request->deposit_date;
		$cust_investment->updated_at = date('Y-m-d');
		$cust_investment->updated_by = \Auth::user()->id;
		$cust_investment->save();
		return redirect()->back()->with('success','Deposit Amount Updated Successfully! ');
	}


	public function edit_withdrawForm(Request $request)
	{
		// dd($request->all());
		$ids = explode(',',$request->id);
		// dd($ids[0]);
		$customer_withdraw = CustomerTransactions::where('customer_id',$ids[0])->where('id',$ids[1])->where('cr_dr','dr')->where('transaction_type','withdraw')->first();
		// dd($customers);
		return view('admin.customers.edit_withdraw',compact('customer_withdraw'));
	}

	public function edit_withdraw(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'customer_withdraw' => 'required',
		]);
		$customer_txn = CustomerTransactions::where('customer_id',$request->customer_id)->where('id',$request->table_id)->first();
		$customer_txn->amount = $request->customer_withdraw;
		$customer_txn->deposit_date = $request->deposit_date;
		$customer_txn->updated_at = date('Y-m-d');
		$customer_txn->updated_by = \Auth::user()->id;
		$customer_txn->save();
		
		return redirect()->back()->with('success','Withdraw Amount Updated Successfully!');
	}


	public function deposit_in_bulkform()
	{	
		$banks = CompanyBank::get();
		return view('admin.customers.bulk_deposit',compact('banks'));
	}

	// public function bulk_submit_transactions(Request $request)
	// {
	// 	dd($request->all());
	// 	$this->validate($request,[
	// 		'amount' => 'required',
	// 	]);

	// 	if($request->transaction_type == 'deposit'){
	// 		foreach($request->customer_id as $key => $invest){
	// 			if($invest != NULL){
	// 				$deposit = new CustomerTransactions;
	// 				$deposit->customer_id = $invest;
	// 				$deposit->amount = $request->amount[$key];
	// 				$deposit->cr_dr = 'cr';
	// 				$deposit->payment_type = $request->payment_type[$key];
	// 				$deposit->transaction_type = 'deposit';
	// 				$deposit->deposit_date = $request->deposit_date;
	// 				$deposit->bank_id = $request->bank_id[$key];
	// 				$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
	// 				$deposit->cheque_dd_date = $request->cheque_dd_date[$key];
	// 				$deposit->remarks = $request->remarks[$key];
	// 				$deposit->status = 1;
	// 				$deposit->created_by = \Auth::user()->id;
	// 				$deposit->created_at = date('Y-m-d');
	// 				$deposit->save();
	// 			}
	// 		}
	// 		return redirect()->back()->with('success','Customers Invest Successfully!');
	// 	}else{
	// 		foreach($request->customer_id as $key => $withdraw){
	// 			if($withdraw != NULL){
	// 				$deposit = new CustomerTransactions;
	// 				$deposit->customer_id = $withdraw;
	// 				$deposit->amount = $request->amount[$key];
	// 				$deposit->cr_dr = 'dr';
	// 				$deposit->payment_type = $request->payment_type[$key];
	// 				$deposit->transaction_type = 'withdraw';
	// 				$deposit->deposit_date = $request->deposit_date;
	// 				$deposit->bank_id = $request->bank_id[$key];
	// 				$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
	// 				$deposit->remarks = $request->remarks[$key];
	// 				$deposit->status = 1;
	// 				$deposit->created_by = \Auth::user()->id;
	// 				$deposit->created_at = date('Y-m-d');
	// 				$deposit->save();
	// 			}
	// 		}
	// 		return redirect()->back()->with('success','Customers Withdraw Successfully!');
	// 	}
	// }

	public function bulk_submit_transactions(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'amount' => 'required',
		]);

		foreach($request->customer_id as $key => $customerId){
			if($request->transaction_type[$key] == 'deposit'){
				if($customerId != NULL){
					$deposit = new CustomerTransactions;
					$deposit->customer_id = $customerId;
					$deposit->amount = $request->amount[$key];
					$deposit->cr_dr = 'cr';
					$deposit->payment_type = $request->payment_type[$key];
					$deposit->transaction_type = 'deposit';
					$deposit->deposit_date = $request->deposit_date;
					$deposit->bank_id = $request->bank_id[$key];
					$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
					$deposit->cheque_dd_date = $request->cheque_dd_date[$key];
					$deposit->remarks = $request->remarks[$key];
					$deposit->status = 1;
					$deposit->created_by = \Auth::user()->id;
					$deposit->created_at = date('Y-m-d');
					$deposit->save();
					$cust_interest_percent = CustomerInterestPercentage::where('customer_id',$customerId)->first();
					// dump($cust_interest_percent);
					$cust_investment = new CustomerInvestment;
					$cust_investment->customer_id = $customerId;
					$cust_investment->amount = $request->amount[$key];
					$cust_investment->deposit_date = $request->deposit_date;
					$cust_investment->customer_interest_rate = $cust_interest_percent->interest_percent?$cust_interest_percent->interest_percent:Null;
					$cust_investment->created_by = \Auth::user()->id;
					$cust_investment->created_at = date('Y-m-d');
					$cust_investment->save();
				}
			}else{
				if($customerId != NULL){
					$deposit = new CustomerTransactions;
					$deposit->customer_id = $customerId;
					$deposit->amount = $request->amount[$key];
					$deposit->cr_dr = 'dr';
					$deposit->payment_type = $request->payment_type[$key];
					$deposit->transaction_type = 'withdraw';
					$deposit->deposit_date = $request->deposit_date;
					$deposit->bank_id = $request->bank_id[$key];
					$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
					$deposit->remarks = $request->remarks[$key];
					$deposit->status = 1;
					$deposit->created_by = \Auth::user()->id;
					$deposit->created_at = date('Y-m-d');
					$deposit->save();
				}
			}
		}
		return redirect()->back()->with('success','Customers Transaction Successfully!');
	}
}
