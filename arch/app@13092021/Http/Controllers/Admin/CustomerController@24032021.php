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
	use App\Model\EntryLock;
	use App\Model\ActivityLog;
	use App\Helpers as Helper;
	use App\User;
	use Redirect;
	use DB; 
	use App\Zipcode;
	use App\Model\Country;
	use App\Model\State;
	use App\Model\City;
	use Carbon\Carbon;
	use Maatwebsite\Excel\Facades\Excel;
	use App\Exports\AllCustomerReportExport;
	use App\Model\DirectAssociateCommission;


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
			
		/*  ** Important!! **
			**  This Code is for update No Of Introducer In Associate Comm %. **

			$users = User::where('login_type','customer')->get();
			foreach($users as $key => $user){
				$associates = AssociateCommissionPercentage::where('customer_id', $user->id)->whereStatus(1)->get();
				foreach($associates as $keys => $associate){
					$associate->no_of_introducer = $keys+1;
					$associate->save();
				}
			}

			** Insert Direct Associate Commission for 1st Introducer Total Investment(client) **

			$associates = AssociateCommissionPercentage::where('no_of_introducer',1)->get();
			foreach($associates as $key => $associate){
				$delete_DAC_customer = DirectAssociateCommission::where('customer_id',$associate->customer_id)->delete();

			  	$direct_associate_commission = new DirectAssociateCommission;
			  	$direct_associate_commission->associate_id = $associate->associate_id;
			  	$direct_associate_commission->customer_id = $associate->customer_id;
			  	$direct_associate_commission->total_investment = $associate->customertransaction($associate->customer_id);
			  	$direct_associate_commission->created_by = \Auth()->user()->id;
			  	$direct_associate_commission->created_at = date('Y-m-d H:i:s');
			  	$direct_associate_commission->save();
			}
			dd('DONE');
		*/	
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
		        $users = $users->orderByDesc('created_at')->paginate(10);
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
				// dd('dfh');
				$this->validate($request,[
				'name' => 'required',
				'customer_id' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
				'dob' => 'nullable',
				'sex' => 'nullable',
				'father_husband_wife' => 'nullable',
				'nationality' => 'nullable',
				'email' => 'nullable',//|unique:users,email,NULL,id,deleted_at,NULL
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
				'payment_type' => 'required',
				
			]);
				// dd('dfh');
			if($request->sum_of_commission != 36){
				return redirect()->back()->with('error','Sum Of Commission is not Less Or Greater Than 36');
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
					'payment_type' => $request->payment_type,
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
					'nominee_address_one' => $request->nominee_address_one,
					'nominee_address_two' => $request->nominee_address_two,
					'nominee_city_id' => $request->nominee_city_id,
					'nominee_state_id' =>$request->nominee_state_id,
					'nominee_country_id' => $request->nominee_country_id,
					'nominee_zipcode' => $request->nominee_zipcode,
					'created_by' => $user_id,
					
				]);

				$activity_log = new ActivityLog;
		        $activity_log->created_by = auth()->user()->id;
        		$activity_log->user_id = $customer->id;    
		        $activity_log->statement = $request->name.' ('.$request->code.') Added Since '.date('d-m-Y');  
		        $activity_log->action_type = 'Create';
		        $activity_log->save();

				// dd('fgf');
				if($request->cheque_issue_date){
					foreach($request->cheque_issue_date as $key => $issue_date){
						// dd($request->has('scan_copy'.$key));
						$customerscan = '';
				        if($request->has('scan_copy.'.$key)){
				            $scancopy = $request->file('scan_copy.'.$key);
				            $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
				            // dd($customerscan);
				            $destinationPath = public_path('images/chequeScanCopy');
				            $scancopy->move($destinationPath, $customerscan);
				        }
						$customer->customersecuritycheque()->create([
							'cheque_issue_date' => $issue_date,
							'cheque_maturity_date' => $request->cheque_maturity_date[$key],
							'cheque_bank_name' => $request->cheque_bank_name[$key],
							'cheque_number' => $request->cheque_number[$key],
							'cheque_amount' => $request->cheque_amount[$key],
							'cheque_date' => $request->cheque_date[$key],
							'scan_copy' => $customerscan,
							'status' => 1,
							'created_by' => $user_id,
						]);
					}
				}
				

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
								'no_of_introducer' =>$request->no_of_introducer[$key],
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
			$associates = User::where('login_type','associate')->get();
			// dd($customer->customersecuritycheque);
			return view('admin.customers.edit',compact('customer','countries','states',
				'cities','banks','security_cheque','associates'));

		}

		public function update(Request $request){
			// dd($request->all());
			$id = $request->id;
			$this->validate($request,[
				'name' => 'required',
				'code' => 'required|min:3|unique:users,code,'.$id.',id,deleted_at,NULL',
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
			 // dd($request->all());
			$uploaded = '';	
			if($request->isMethod('post')){
				
				$customer = User::where('id',$request->id)->firstOrFail();
				// dd($customer);
				if($customer->count() > 0){
					$customer->login_type = 'customer';
					$customer->name = $request->name;
					// $customer->password = Hash::make($request->password);
					$customer->mobile = $request->mobile;
					$customer->code = $request->code;
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
			        // $customerscan = '';
			        // if($request->has('scan_copy')){
			        //     $scancopy = $request->file('scan_copy');
			        //     $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
			        //     $destinationPath = public_path('images/chequeScanCopy');
			        //     $scancopy->move($destinationPath, $customerscan);
			        // }
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
						'payment_type' => $request->payment_type,
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
						'nominee_address_one' => $request->nominee_address_one,
						'nominee_address_two' => $request->nominee_address_two,
						'nominee_city_id' => $request->nominee_city_id,
						'nominee_state_id' => $request->nominee_state_id,
						'nominee_country_id' => $request->nominee_country_id,
						'nominee_zipcode' => $request->nominee_zipcode,
						'updated_by' => \Auth::user()->id,
						'updated_at' => date('Y-m-d H:i:s'),
					]);

					$activity_log = new ActivityLog;
			        $activity_log->created_by = auth()->user()->id;
        			$activity_log->user_id = $customer->id;    
			        $activity_log->statement = $request->name.' ('.$request->code.') Updated Since '.date('d-m-Y');  
			        $activity_log->action_type = 'Update';
			        $activity_log->save();

					if($request->cheque_issue_dates || $request->cheque_issue_date){
						foreach($request->ids as $key => $id){
							// dd($request->has('scan_copy'.$key));
							$customerscan = '';
					        if($request->has('scan_copy.'.$key)){
					            $scancopy = $request->file('scan_copy.'.$key);
					            $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
					            // dd($customerscan);
					            $destinationPath = public_path('images/chequeScanCopy');
					            $scancopy->move($destinationPath, $customerscan);
					        }
					        $per_customer = CustomerSecurityCheque::where('customer_id',$request->customer_id[$key])->where('id',$id)->first();
					        if($per_customer){
								$per_customer->cheque_issue_date = $request->cheque_issue_date[$key];
								$per_customer->cheque_maturity_date = $request->cheque_maturity_date[$key];
								$per_customer->cheque_bank_name = $request->cheque_bank_name[$key];
								$per_customer->cheque_amount = $request->cheque_amount[$key];
								$per_customer->cheque_number = $request->cheque_number[$key];
								$per_customer->cheque_date = $request->cheque_date[$key];
								$per_customer->scan_copy = $customerscan;
								$per_customer->status = 1;
								$per_customer->created_by = \Auth::user()->id;
								$per_customer->save();
							}
						}
					}

					if($request->cheque_issue_dates){
						foreach($request->cheque_issue_dates as $key => $issue_date){
							// dd($request->has('scan_copy'.$key));
							$customerscan = '';
					        if($request->has('scan_copys.'.$key)){
					            $scancopy = $request->file('scan_copys.'.$key);
					            $customerscan = time().'.'.$scancopy->getClientOriginalExtension();
					            // dd($customerscan);
					            $destinationPath = public_path('images/chequeScanCopy');
					            $scancopy->move($destinationPath, $customerscan);
					        }
							$customer->customersecuritycheque()->update([
								'cheque_issue_date' => $issue_date,
								'cheque_maturity_date' => $request->cheque_maturity_dates[$key],
								'cheque_bank_name' => $request->cheque_bank_names[$key],
								'cheque_number' => $request->cheque_numbers[$key],
								'cheque_amount' => $request->cheque_amounts[$key],
								'cheque_date' => $request->cheque_dates[$key],
								'scan_copy' => $customerscan,
								'status' => 1,
								'created_by' => \Auth::user()->id,
							]);
						}
					}


					// $customer->customersecuritycheque()->update([
					// 	'cheque_issue_date' => $request->cheque_issue_date,
					// 	'cheque_maturity_date' => $request->cheque_maturity_date,
					// 	'cheque_bank_name' => $request->cheque_bank_name,
					// 	'cheque_amount' => $request->cheque_amount,
					// 	'cheque_date' => $request->cheque_date,
					// 	'scan_copy' => $customerscan,
					// 	'status' => 1,
					// 	'updated_by' => \Auth::user()->id,
					// 	'updated_at' => date('Y-m-d H:i:s'),
					// ]);


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
			$countries = Country::get()->pluck('name','id');
			$states = State::get();
			$cities = City::get();
			return view('admin.customers.view',compact('customer','countries','states','cities'));
		}

		public function status($id){
			$customer = User::where('id',decrypt($id))->firstOrFail();
			$customer->status = !$customer->status;
			$customer->save();

			$activity_log = new ActivityLog;
	        $activity_log->created_by = auth()->user()->id; 
        	$activity_log->user_id = $customer->id;   
	        $activity_log->statement = $customer->name.' ('.$customer->code.') Change Status Since '.date('d-m-Y');  
	        $activity_log->action_type = 'Change Status';
	        $activity_log->save();
			return redirect()->back()->with('success','Status Updated Successfully!');
		}
		
		public function hold_status(Request $request){
			// dd($request->all());
			$this->validate($request,[
				'hold_remarks' => 'required',
			]);
			$customer = User::where('id',$request->customer_id)->firstOrFail();
			$customer->hold_remarks = $request->hold_remarks;
			$customer->hold_status = !$customer->hold_status;
			$customer->save();

			$activity_log = new ActivityLog;
	        $activity_log->created_by = auth()->user()->id;
        	$activity_log->user_id = $customer->id;    
	        $activity_log->statement = $customer->name.' ('.$customer->code.') Change Hold Status Since '.date('d-m-Y');  
	        $activity_log->action_type = 'Hold Status';
	        $activity_log->save();
			return redirect()->back()->with('success','Hold Status Updated Successfully!');
		}

		
		public function hold_remarksForm(Request $request){
			// dd($request->all());
			$customer = User::where('id',$request->id)->firstOrFail();
			return view('admin.customers.hold_remarks',compact('customer'));
			// $customer->hold_status = !$customer->hold_status;
			// $customer->save();
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
				$customer = User::where('id',$request->customer_id)->first();
				$activity_log = new ActivityLog;
		        $activity_log->created_by = auth()->user()->id; 
        		$activity_log->user_id = $customer->id;   
		        $activity_log->statement = $customer->name.' ('.$customer->code.') Deleted Since '.date('d-m-Y');  
		        $activity_log->action_type = 'Delete';
		        $activity_log->save();

				User::where('id',$request->customer_id)->delete();
				CustomerDetail::where('customer_id',$request->customer_id)->delete();
				CustomerInterestPercentage::where('customer_id',$request->customer_id)->delete();
				CustomerReward::where('customer_id',$request->customer_id)->delete();
				CustomerTransactions::where('customer_id',$request->customer_id)->delete();
				CustomerInvestment::where('customer_id',$request->customer_id)->delete();
				AssociateCommissionPercentage::where('customer_id',$request->customer_id)->delete();
				AssociateReward::where('customer_id',$request->customer_id)->delete();
				AssociateTransactions::where('customer_id',$request->customer_id)->delete();

	        	return redirect('admin/customers')->with('success','Customer Deleted Successfully!');
			}else{
	        	return redirect()->back()->with('error','Password is Wrong!');

			}
		}


		public function getZip(Request $request)
		{
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

	public function customerAssociateCommission(Request $request,$customerId){
		// dd($request->all());
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
		$year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
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
		// if(!$customer->customerinvestments->count()){
		// 	$interest_rate->start_date = $request->deposit_date;
		// 	$interest_rate->save();
		// }

		$commissions = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('status',1)->get();//status = active_status
		// dd($customer->associatecommissions);
		// if($customer->associatecommissions->count()){
		// 	foreach ($commissions as $key => $commission) {
		// 		$commission->start_date = $request->deposit_date;
		// 		$commission->save();
		// 	}
		// }
		
		$investment_table = $customer->customerinvestments()->create([
			'amount' => $request->amount,
			'deposit_date' => $request->deposit_date,
			'customer_interest_rate' => $interest_rate->interest_percent,
			'created_by' => $user_id,
		]);
		// dd($investment_table);
		$customer->customertransactions()->create([
			// dd($investment_table),
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
			'respective_table_id' => $investment_table->id,
			'respective_table_name' => 'customer_investments',
		]);

		directAssociateCommission($customer->id);
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id; 
    	$activity_log->user_id = $customer->id;   
        $activity_log->statement = $customer->name.' ('.$customer->code.') Deposit Rs. '.$request->amount.' Since '.date('d-m-Y');  
        $activity_log->action_type = 'Deposit';
        $activity_log->save();	
		return redirect('admin/customers')->with('success',$customer->name.' Successfully Deposit');
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
		// $customer_investment = CustomerInvestment::whereCustomerId(decrypt($id))->first();
		// $customer_investment->customer_interest_rate = $request->customer_invest;
		// $customer_investment->save();
		// dd($request->associate);
		$customer = User::find(decrypt($id));
		if($request->associate_name != NULL || $request->associate_names != NULL){
			if($request->associate_name != NULL && sizeof(array_filter($request->associate_name))){
				foreach($request->associate_name as $key => $value){	
					if($value>0){
						$associate_comm_per = AssociateCommissionPercentage::where('customer_id', $customer->id)->where('associate_id', $value)->whereStatus(1)->first();
						$associate_comm_per->no_of_introducer = $key+1;
						$associate_comm_per->updated_by = \Auth::user()->id;
						$associate_comm_per->updated_at = date('Y-m-d H:i:s');
						$associate_comm_per->save();
					}
				}
				// return redirect()->back()->with('success',$customer->name. ' Successfully Add Commission');
			}

			if($request->associate_names != NULL && sizeof(array_filter($request->associate_names))){
				foreach($request->associate_names as $keys => $values){	
					if($values>0){
						$customer_asso_count = AssociateCommissionPercentage::where('customer_id', $customer->id)->whereStatus(1)->count();
						// dd($associate_comm_per);
						$asso_comm_per = new AssociateCommissionPercentage;
						$asso_comm_per->associate_id = $values;
						$asso_comm_per->customer_id  = $customer->id;
						$asso_comm_per->start_date  = date('Y-m-d');
						$asso_comm_per->commission_percent  = $request->commissions[$keys];
						$asso_comm_per->no_of_introducer  = $customer_asso_count+1;
						$asso_comm_per->status  = 1;
						$asso_comm_per->created_by  = auth()->user()->id;
						$asso_comm_per->created_at  = date("Y-m-d");
						$asso_comm_per->save();
					}
				}
			}
			return redirect()->back()->with('success',$customer->name. ' Successfully Add Commission');
		}
		return redirect()->back()->with('success',' No Changes in Commission');
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
		if($request->amount <= 0){
			return redirect()->back()->with('error','Withdrawl amount shouldn\'t less than 0');
		}
		$year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
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

		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id; 
        $activity_log->user_id = $customer->id;   
        $activity_log->statement = $customer->name.' ('.$customer->code.') Withdrawl Rs.'.$request->amount.' Since '.date('d-m-Y');  
        $activity_log->action_type = 'Withdrawl';
        $activity_log->save();

		return redirect()->back()->with('success','Successfully Added Withdraw Amount');
	}


	public function generatePayout(){
		return view('admin.customers.generate-payout');
	}


	public function payoutGenerates(Request $request)
	{
		// dd($request->all());
		// dd(CustomerReward::where('id',2)->first()->customertransactions);
		$this->validate($request,[
			'month'=>'required|numeric|min:1|not_in:0',
			'year'=>'required|numeric|min:1|not_in:0',
			'posting_date' => 'required',
		]);
		$postingDate = $request->posting_date;
		$month = $request->month;
		$year = $request->year;

        $entrylock = EntryLock::where('month',str_pad($month, 2, '0', STR_PAD_LEFT))->where('year',$year)->first();
        // dd($entrylock);
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
		// CustomerRewardTemp::where('reward_type', 'interest')->get()->each(function($reward){
		// 		$reward->delete();
		// });	//Each for cascade delete where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->
		// AssociateRewardTemp::where('reward_type', 'commission')->get()->each(function($reward){
		// 	$reward->delete();
		// });


		$customers = User::whereHas('customertransactions',function($q) use ($year,$month){
			// dd($q->with('customerdetail'));
		 $q->whereIn('customer_id', function($query){
				 $query->select('customer_id')->from('customer_details')->where('no_interest',0)->where('customer_id',505)->get();
			})->where('customer_id',505)->where('deposit_date', '<=', Carbon::parse($year.'-'.$month)->format('Y-m-t'));
		})->whereLoginType('customer')->where('id',505)->get();
		// dd($customers);
		// dd(date('Y-m-t',strtotime($year.'-'.$month)));
		if(date('Y-m-t',strtotime($year.'-'.$month)) > date('Y-m-d')){
			return redirect()->back()->with('error','Sorry! Can not generate-payout.');
		}

		foreach($customers as $customer){
			$this->calculateCustomerPayout($customer,$month,$year,$postingDate);
		}
		return \Redirect::route('admin.confirmation_gen_payouts', ['month'=>$month,'year'=>$year]);
		
	}

	protected function calculateCustomerPayout($customer,$month,$year,$postingDate){
		CustomerReward::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'interest')->get()->each(function($reward){
				$reward->delete();
		});	//Each for cascade delete
		AssociateReward::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'commission')->get()->each(function($reward){
			$reward->delete();
		});
		CustomerRewardTemp::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'interest')->get()->each(function($reward){
				$reward->delete();
		});	//Each for cascade delete
		AssociateRewardTemp::where('month', Carbon::parse($year.'-'.$month)->format('m'))->where('year', Carbon::parse($year.'-'.$month)->format('Y'))->where('customer_id',$customer->id)->where('reward_type', 'commission')->get()->each(function($reward){
			$reward->delete();
		});	//Each for cascade delete
		$start_date = Carbon::parse($year.'-'.$month.'-01')->format('Y-m-d');
		$customer_transactions = CustomerTransactions::where('deposit_date','>=',Carbon::parse($year.'-'.$month.'-01')->format('Y-m-d'))->where('deposit_date','<=',Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t'))->where('customer_id',505)->orderBy('deposit_date')->get();
			//$customer->id
		if($customer_transactions->count()){
			foreach($customer_transactions as $customer_transaction){
				if(Carbon::parse($customer_transaction->deposit_date)->format('d') == '01' && $customer_transaction->transaction_type == 'withdraw'){
					continue;
				}

				// if($customer_transaction->customer_id=173){
				$end_date = Carbon::parse($customer_transaction->deposit_date)->subDays(1)->format('Y-m-d');

				if($customer_transaction->transaction_type == 'deposit'){//$balance > 0 && 
					$end_date = Carbon::parse($customer_transaction->deposit_date)->format('Y-m-d');
				}

				if($start_date <= $end_date){
					$this->calculation($customer,$start_date,$end_date,$postingDate);
				}
				
				if($customer_transaction->transaction_type == 'deposit'){	//$balance > 0 && 
					$start_date = Carbon::parse($customer_transaction->deposit_date)->addDays(1)->format('Y-m-d');
				}else{
					$start_date = Carbon::parse($customer_transaction->deposit_date)->format('Y-m-d');
				}
				
			}
			if($start_date <= Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t')){
				$this->calculation($customer,$start_date,Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t'),$postingDate);
			}
		}else{
			$end_date = Carbon::parse($year.'-'.$month.'-01')->format('Y-m-t');
			$this->calculation($customer,$start_date,$end_date,$postingDate);
 		}dd('done');
	}

	protected function calculation($customer,$start_date,$end_date,$postingDate){

		$balance = $customer->balance($end_date);
		if($balance > 0){

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
			if(!$customer_interest_percents->count()){
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
			
				if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-28')){
					$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+3;
				}else if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-29')){
						$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+2;
				}else if(Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-t') && Carbon::parse($interest_end_date)->format('Y-m-d') == Carbon::parse($interest_end_date)->format('Y-m-31')){
						$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date));
				}else{
					$payable_days = (Carbon::parse($interest_start_date)->diffInDays($interest_end_date))+1;
				}
				
				// dd($payable_days);
				$oneday_interest_amount = (($balance*$interest_rate/100)/12)/30;

				if($payable_days > 0){
					$amount = $oneday_interest_amount*$payable_days;
				
				    $customerrewards = new CustomerRewardTemp;
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
					$customerrewards->posting_date = $postingDate;
					$customerrewards->save();

					
					$customerTransaction = new CustomerTransactionTemp;
					$customerTransaction->customer_id = $customer->id;
					$customerTransaction->transaction_type = 'interest';
					$customerTransaction->respective_table_id = $customerrewards->id;
					$customerTransaction->respective_table_name = 'customer_rewards';
					$customerTransaction->remarks = 'customer_interest';
					$customerTransaction->deposit_date = $interest_end_date;
					$customerTransaction->posting_date = $postingDate;
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

			$associate_commissions = AssociateCommissionPercentage::where('customer_id',$customer->id)->groupBy('associate_id')->get();
			
			foreach ($associate_commissions as $key => $commission) {
				
				$associate_commission_percents = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('associate_id',$commission->associate_id)->where(function($q) use ($start_date,$end_date){
						return $q->where(function($qu) use ($start_date,$end_date){
								return $qu->where('start_date','>=', $start_date)
										->where('start_date','<=', $end_date);
							})->orWhere(function($qu) use ($start_date,$end_date){
								return $qu->where('end_date','>=', $start_date)
										->where('end_date','<=', $end_date);
							});
				})->get();
				

				$first_commission_start = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('associate_id',$commission->associate_id)->orderBy('start_date')->first();


				$start_date = $original_start_date;
				$end_date = $original_end_date;

				// if($commission->associate_id == 18){
				// 	echo "1";
				// 	dump($associate_commission_percents->count().'-->percent count');
				// 	dump($start_date.'-->original_start_date');
				// 	dump($end_date.'-->original_end_date');
				// }	
				if($associate_commission_percents->count() > 0){
					$generateAfterLoop = false;
					foreach($associate_commission_percents as $key => $associate_commission_percent){
						// if($commission->associate_id == 18){
						// 	echo "2";
						// 	dump($associate_commission_percent->start_date.'-->Commission Start_date');
						// 	dump($original_start_date.'-->original_start_date');
						// }
							
						if($associate_commission_percent->start_date > $original_start_date){	//
							// if($commission->associate_id == 18){
							// 	dump($start_date.'-->Start Date');
							// 	dump($end_date.'-->End Date');
							// }
							if($first_commission_start->id == $associate_commission_percent->id){
								$start_date = $associate_commission_percent->start_date;

							}else{

							

							
								// if($commission->associate_id == 18){
								// 	echo "1";
								// 	dump($original_start_date);
								// 	dump($start_date.'-->Start Date');
								// 	dump($end_date.'-->End Date');
								// }
								$end_date = Carbon::parse($associate_commission_percent->start_date)->subDays(1)->format('Y-m-d');
								$this->calculateAssociateCommissionPercent($start_date,$end_date,$associate_commission_percent->associate_id,$customer,$associate_commission_percent->commission_percent,$balance,$postingDate);
								
								$start_date = $associate_commission_percent->start_date;
							}
							
							
							
						}
						if($associate_commission_percents->count() == ($key + 1) && $end_date <= $original_end_date){
							$generateAfterLoop = true;
						}
						// if($commission->associate_id == 18){
						// 	echo "1";
						// 	dump($associate_commission_percents->count().'-->'. ($key + 1));
						// 	dump($end_date.'-->'. ($original_end_date));
						// }
						
					}

					if($generateAfterLoop){	//$start_date <= $original_end_date
						// if($commission->associate_id == 18){
						// 	echo "2";
						// 	dump($start_date.'-->Start Date');
						// 	dump($original_end_date.'-->Original End Date');
						// }
						$this->calculateAssociateCommissionPercent($start_date,$original_end_date,$associate_commission_percent->associate_id,$customer,$associate_commission_percent->commission_percent,$balance,$postingDate);
					}
				}else {
					// dump($start_date);
					// dump($commission->associate_id);
					$associate_commission_percent = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('associate_id',$commission->associate_id)->where(function($q) use ($start_date,$end_date){
							return $q
							->where('start_date','<', $start_date)
							->where(function($qu) use ($start_date,$end_date){
								return $qu
								->where('end_date','>', $start_date)
								->orWhereNull('end_date');
							});
							
					})
					->first();
					// dump(AssociateCommissionPercentage::where('customer_id',$customer->id)->where('associate_id',$commission->associate_id)->where(function($q) use ($start_date,$end_date){
					// 		return $q
					// 		->where('start_date','<', $start_date)
					// 		->where(function($qu) use ($start_date,$end_date){
					// 			return $qu
					// 			->where('end_date','>', $start_date)
					// 			->orWhereNull('end_date');
					// 		});
							
					// })
					// ->toSql());

					if($associate_commission_percent){
						if($commission->associate_id == 18){
							echo "3";
							dump($start_date.'-->Start Date');
							dump($end_date.'-->End Date');
						}
						$this->calculateAssociateCommissionPercent($start_date,$end_date,$associate_commission_percent->associate_id,$customer,$associate_commission_percent->commission_percent,$balance,$postingDate);
					}
				}

				
			}
		}
	}

	protected function calculateAssociateCommissionPercent($start_date,$end_date,$associateId,$customer,$commission_rate,$balance,$postingDate){
		// $associate_commission_percent = AssociateCommissionPercentage::where('customer_id',$customer->id)->where('associate_id',$associateId)->where('start_date','<=', $start_date)->orderByDesc('start_date')->first();

		

		// if($associate_commission_percent){
			// $commission_rate = $associate_commission_percent->commission_percent;
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
		
		
		    $associaterewards = new AssociateRewardTemp;
		    // dd($associate_commission_percent->associate_id);
			$associaterewards->customer_id = $customer->id;
			$associaterewards->associate_id = $associateId;
			$associaterewards->month = Carbon::parse($start_date)->format('m');
			$associaterewards->year = Carbon::parse($start_date)->format('Y');
			$associaterewards->reward_type = 'commission';
			$associaterewards->payable_days = $payable_days;
			$associaterewards->amount = $amount;
			$associaterewards->start_date = $start_date;
			$associaterewards->end_date = $end_date;
			$associaterewards->total_amount = $balance;
			$associaterewards->commission_percent = $commission_rate;
			$associaterewards->posting_date = $postingDate;
			$associaterewards->save();

			
			// dd($customerTransaction);
			$associateTransaction = new AssociateTransactionTemp;
			$associateTransaction->associate_id = $associateId;
			$associateTransaction->customer_id = $customer->id;
			$associateTransaction->transaction_type = 'commission';
			$associateTransaction->respective_table_id = $associaterewards->id;
			$associateTransaction->respective_table_name = 'associate_rewards';
			$associateTransaction->remarks = 'associate_commission';
			$associateTransaction->deposit_date = $end_date;
			$associateTransaction->posting_date = $postingDate;
			$associateTransaction->amount = $amount;
			$associateTransaction->cr_dr = 'cr';
			$associateTransaction->payment_type = 'null';
			$associateTransaction->status = '1';
			$associateTransaction->save();
			return true;
		}
		// }
		return false;
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
    	// dd('zfs');
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
	public function viewMyInvestment()
	{
		$user_txns = CustomerTransactions::whereCustomerId(Auth::user()->id)->where('transaction_type', 'deposit')->paginate(10);
		return view('admin/customers/customer-my-investment',compact('user_txns'));
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

		$year = date('Y',strtotime($cust_interst->start_date));
        $month = date('m',strtotime($cust_interst->start_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }


		$cust_interst->interest_percent = $request->update_customer_interest; 
		$cust_interst->updated_by = $user_id;
		$cust_interst->updated_at = date('Y-m-d');
		$cust_interst->save();

		$user = User::where('id',$request->customer_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id; 
        $activity_log->user_id = $user->id;   
        $activity_log->statement = $user->name.' ('.$user->code.') Update Interest Rate ('. $request->update_customer_interest .') Since '. date('d-m-Y');  
        $activity_log->action_type = 'Update Interest Rate';
        $activity_log->save();
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
		$user_id = auth()->user()->id;
		$associatecommission = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->where('id',$request->table_id)->first();

		$year = date('Y',strtotime($associatecommission->start_date));
        $month = date('m',strtotime($associatecommission->start_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
		// dd($entrylock);
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }


		$associatecommission->commission_percent = $request->update_associate_commission; 
		$associatecommission->updated_by = $user_id;
		$associatecommission->updated_at = date('Y-m-d');
		$associatecommission->save();

		$user = User::where('id',$request->associate_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $user->id;    
        $activity_log->statement = $user->name.' ('.$user->code.') Update Associate Commission Rate ('.$request->update_associate_commission.') Since '.date('d-m-Y');  
        $activity_log->action_type = 'Create';
        $activity_log->save();

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
		$year = date('Y',strtotime($request->applicable_date));
        $month = date('m',strtotime($request->applicable_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
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

		$user = User::where('id',$request->customer_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $user->id;    
        $activity_log->statement = $user->name.' ('.$user->code.') Add New Customer Interest Rate ('.$request->customer_interest.') Since '.date('d-m-Y');  
        $activity_log->action_type = 'Add New Interest Rate';
        $activity_log->save();


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

		$year = date('Y',strtotime($request->start_date));
        $month = date('m',strtotime($request->start_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }

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
		$commission->no_of_introducer = $ass_commission->no_of_introducer;
		$commission->associate_id = $request->associate_id;
		$commission->start_date = $request->start_date;
		$commission->commission_percent = $request->associate_withdrawl;
		$commission->status = 1;
		$commission->created_by = $user_id;
		$commission->updated_by = $user_id;
		$commission->save();

		$user = User::where('id',$request->associate_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $user->id;    
        $activity_log->statement = $user->name.' ('.$user->code.') Add New Associate Commission Rate ('.$request->associate_withdrawl.') '.date('d-m-Y');  
        $activity_log->action_type = 'Add New Associate Commission Rate';
        $activity_log->save();

		return redirect()->back()->with('success','New Commission Rate Applicable on Associate Successfully!');
	}

	public function associateCommissionDestroyForm(Request $request){
		// dd($request->all());
		$ids = explode(',',$request->id);
		// dd($ids[1]);
		$customerInterest = AssociateCommissionPercentage::where('associate_id',$ids[1])->where('customer_id',$ids[0])->where('status',1)->first();//status = active_status
		// dd($customerInterest);
    	return view('admin.customers.delete_commission',compact('customerInterest'));
	}

	public function associateCommissionDestroy(Request $request){
		// dd($request->all());
		$this->validate($request,[
			'password'=>'required',
		]);
		
		$users = User::whereLoginType('superadmin')->first();
		$user =Hash::check($request->password,$users->password);
		
		if($user){

			$customerInterest = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->where('id',$request->table_id)->where('status',1)->first();

			$year = date('Y',strtotime($customerInterest->start_date));
	        $month = date('m',strtotime($customerInterest->start_date));
	        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
	        if($entrylock != NULL && $entrylock->status == 0){
	            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
	        }

			$associate = User::where('id',$request->associate_id)->first();
			$activity_log = new ActivityLog;
	        $activity_log->created_by = auth()->user()->id;
        	$activity_log->user_id = $associate->id;    
	        $activity_log->statement = $associate->name.' ('.$associate->code.') Delete Associate From Edit Commission Since '.date('d-m-Y');  
	        $activity_log->action_type = 'Delete Associate';
	        $activity_log->save();

			$customerInterest = AssociateCommissionPercentage::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->where('id',$request->table_id)->where('status',1)->delete();//status = active_status

			DirectAssociateCommission::where('associate_id',$request->associate_id)->where('customer_id',$request->customer_id)->delete();

			$associates = AssociateCommissionPercentage::where('customer_id', $request->customer_id)->whereStatus(1)->get();
			foreach($associates as $keys => $associate){
				$associate->no_of_introducer = $keys+1;
				$associate->save();
			}

        	return redirect()->back()->with('success','Associate Commission Deleted Successfully!');
		}else{
        	return redirect()->back()->with('error','Password is Wrong!');

		}
	}

	public function getCustomers(Request $request){
		// dd($request->all());
        if($request->has('term')){
            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,"-", code) AS label'))->where('name','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%')->where('login_type','customer')->get();
            return response()->json($results);
        }
    }

    public function deleteCustomerDeposit($id){
		// dd(decrypt($id));
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
		// dd($month);
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
		$dated = date($year.'-'.$month.'-01');
		$datet = date($year.'-'.$month.'-31');

		$cust_rewards = CustomerReward::where('month',$month)->where('year',$year)->get();
		foreach ($cust_rewards as $key => $reward) {
			$user = User::where('id', $reward->customer_id)->first();
			$activity_log = new ActivityLog;
			$activity_log->created_by = auth()->user()->id;  
			$activity_log->user_id = $user->id;  
			$activity_log->statement = $user->name.' ('.$user->code.') Delete Generated Payout Of'.$month.'-'.$year.' Since '. date('d-m-Y');  
			$activity_log->action_type = 'Delete Generated Payout';
			$activity_log->save();
		}

		$asso_rewards = AssociateReward::where('month',$month)->where('year',$year)->get();
		foreach ($asso_rewards as $key => $reward) {
			$user = User::where('id', $reward->associate_id)->first();
			$activity_log = new ActivityLog;
			$activity_log->created_by = auth()->user()->id;  
			$activity_log->user_id = $user->id;  
			$activity_log->statement = $user->name.' ('.$user->code.') Delete Generated Payout Of'.$month.'-'.$year.' Since '. date('d-m-Y');  
			$activity_log->action_type = 'Delete Generated Payout';
			$activity_log->save();
		}

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

		$year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }


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

		directAssociateCommission($request->customer_id);
		$user = User::where('id', $request->customer_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        $activity_log->user_id = $user->id;  
        $activity_log->statement = $user->name.' ('.$user->code.') Edit Deposit Since '.date('d-m-Y');  
        $activity_log->action_type = 'Edit Deposit';
        $activity_log->save();
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
		$year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }
		$customer_txn = CustomerTransactions::where('customer_id',$request->customer_id)->where('id',$request->table_id)->first();
		$customer_txn->amount = $request->customer_withdraw;
		$customer_txn->deposit_date = $request->deposit_date;
		$customer_txn->updated_at = date('Y-m-d');
		$customer_txn->updated_by = \Auth::user()->id;
		$customer_txn->save();
		
		$user = User::where('id', $request->customer_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        $activity_log->user_id = $user->id;    
        $activity_log->statement = $user->name.' ('.$user->code.') Edit Withdraw Since '.date('d-m-Y');  
        $activity_log->action_type = 'Edit Withdraw';
        $activity_log->save();
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
			// 'remarks' => 'required',
		]);

		$year = date('Y',strtotime($request->deposit_date));
        $month = date('m',strtotime($request->deposit_date));
        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
        if($entrylock != NULL && $entrylock->status == 0){
            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
        }

		foreach($request->customer_id as $key => $customerId){
			if($request->transaction_type[$key] == 'deposit'){
				if($customerId != NULL){

					$cust_interest_percent = CustomerInterestPercentage::where('customer_id',$customerId)->first();
					if($cust_interest_percent){
						$cust_investment = new CustomerInvestment;
						$cust_investment->customer_id = $customerId;
						$cust_investment->amount = $request->amount[$key];
						$cust_investment->deposit_date = $request->deposit_date;
						$cust_investment->customer_interest_rate = $cust_interest_percent->interest_percent?$cust_interest_percent->interest_percent:Null;
						$cust_investment->created_by = \Auth::user()->id;
						$cust_investment->created_at = date('Y-m-d');
						$cust_investment->save();
					}
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
					$deposit->respective_table_id = $cust_investment->id;
					$deposit->respective_table_name = 'customer_investments';
					$deposit->status = 1;
					$deposit->created_by = \Auth::user()->id;
					$deposit->created_at = date('Y-m-d');
					$deposit->save();

					$user = User::where('id',$customerId)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id; 
        			$activity_log->user_id = $user->id;   
					$activity_log->statement = $user->name.' ('.$user->code.') Deposit Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Deposit';
					$activity_log->save();
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

					$user = User::where('id',$customerId)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id;
        			$activity_log->user_id = $user->id;    
					$activity_log->statement = $user->name.' ('.$user->code.') Withdraw Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Withdraw';
					$activity_log->save();
				}
			}
		}
		return redirect()->back()->with('success','Customers Transaction Successfully!');
	}

	public function confirmationPayout($month,$year)
	{
		// dump($year);	
		// dd($month);	
		 $month = str_pad($month, 2, '0', STR_PAD_LEFT);
		 // dd($month);
		 $commissions = AssociateRewardTemp::where('reward_type','commission')->groupBy(DB::raw("CONCAT(year,month)"),'associate_id')->pluck('id'); 
        // dump($commissions);
		 $Associatetransactions = AssociateRewardTemp::select('id','associate_id','customer_id','amount','month','year','start_date','end_date','payable_days','total_amount','reward_type','commission_percent','created_by','updated_by','created_at','updated_at')->where(function($q) use ($month,$year){
	            return $q->where('reward_type','commission')->where('month',$month)->where('year',$year);
	        })
        ->whereIn('id',$commissions);
        // ->toSql();
        // dd($Associatetransactions);
        // ->orderByDesc('deposit_date')
        // ->paginate(20);
        // ->get();
        $interest = CustomerRewardTemp::where('reward_type','interest')->groupBy(DB::raw("CONCAT(year,month)"),'customer_id')->pluck('id'); 
        // dump($interest);
        $transactions = CustomerRewardTemp::select('id',DB::raw('0 as associate_id'),'customer_id','amount','month','year','start_date','end_date','payable_days','total_amount','reward_type','interest_percent','created_by','updated_by','created_at','updated_at')->where(function($q) use ($month,$year){
            return $q->where('reward_type', 'interest')->where('month',$month)->where('year',$year);
        })
        ->whereIn('id',$interest);
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

        $combine_transactions = $transactions->union($Associatetransactions);
        
        // dd($combine_transactions->toSql());//->orderByDesc('created_at')
        $transactions = $combine_transactions->paginate(50);
        // dd($transactions);
        return view('admin.customers.confirmation_generated_payouts',compact('transactions','month','year'));
	}


	public function confirmPayout($month,$year)
	{
		

		$f_date = date($year.'-'.$month.'-01');

		$l_date = date($year.'-'.$month.'-31');
 		// dd($l_date);
		// CustomerReward::where('reward_type', 'interest')->get()->each(function($reward){
		// 		$reward->delete();
		// });
		// AssociateReward::where('reward_type', 'commission')->get()->each(function($reward){
		// 	$reward->delete();
		// });
		$cust_reward_temps = CustomerRewardTemp::where('month',$month)->where('year',$year)->orderBy('id')->get();
		// dd($cust_reward_temps);
		foreach($cust_reward_temps as $cust_temp){
			$cust_reward = new CustomerReward;
			$cust_reward->customer_id = $cust_temp->customer_id;
			$cust_reward->amount = $cust_temp->amount;
			$cust_reward->month = $cust_temp->month;
			$cust_reward->year = $cust_temp->year;
			$cust_reward->start_date = $cust_temp->start_date;
			$cust_reward->end_date = $cust_temp->end_date;
			$cust_reward->payable_days = $cust_temp->payable_days;
			$cust_reward->total_amount = $cust_temp->total_amount;
			$cust_reward->interest_percent = $cust_temp->interest_percent;
			$cust_reward->reward_type = $cust_temp->reward_type;
			$cust_reward->posting_date = $cust_temp->posting_date;
			$cust_reward->created_at = $cust_temp->created_at;
			$cust_reward->created_by = $cust_temp->created_by;
			$cust_reward->updated_at = $cust_temp->updated_at;
			$cust_reward->updated_by = $cust_temp->updated_by;
			$cust_reward->save();

			$cust_txn_temp = CustomerTransactionTemp::whereBetween('deposit_date', [$f_date, $l_date])->where('respective_table_id', $cust_temp->id)->where('respective_table_name','customer_rewards')->first();
			// dd($cust_txn_temp->customer_id);
			// foreach($cust_txn_temps as $cust_txn_temp){
				if($cust_txn_temp != NULL){
					$cust_txn = new CustomerTransactions;
					$cust_txn->customer_id = $cust_txn_temp->customer_id;
					$cust_txn->amount = $cust_txn_temp->amount;
					$cust_txn->cr_dr = $cust_txn_temp->cr_dr;
					$cust_txn->payment_type = $cust_txn_temp->payment_type;
					$cust_txn->transaction_type = $cust_txn_temp->transaction_type;
					$cust_txn->deposit_date = $cust_txn_temp->deposit_date;
					$cust_txn->posting_date = $cust_txn_temp->posting_date;
					$cust_txn->cheque_dd_date = $cust_txn_temp->cheque_dd_date;
					$cust_txn->bank_id = $cust_txn_temp->bank_id;
					$cust_txn->cheque_dd_number = $cust_txn_temp->cheque_dd_number;
					$cust_txn->respective_table_id = $cust_reward->id;
					$cust_txn->respective_table_name = $cust_txn_temp->respective_table_name;
					$cust_txn->remarks = $cust_txn_temp->remarks;
					$cust_txn->status = $cust_txn_temp->status;
					$cust_txn->created_at = $cust_txn_temp->created_at;
					$cust_txn->updated_at = $cust_txn_temp->updated_at;
					$cust_txn->created_by = $cust_txn_temp->created_by;
					$cust_txn->updated_by = $cust_txn_temp->updated_by;
					$cust_txn->save();

					$user = User::where('id', $cust_txn_temp->customer_id)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id;  
					$activity_log->user_id = $cust_txn_temp->customer_id;   
					$activity_log->statement = $user->name.' ('.$user->code.') - Generate Interest Rs. ('. $cust_txn_temp->amount .') of '.date('m-Y',strtotime($cust_txn_temp->deposit_date)).' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Generate Interest';
					$activity_log->save();
				}

			// }
			$cust_txn_temp = CustomerTransactionTemp::whereBetween('deposit_date', [$f_date, $l_date])->where('respective_table_id', $cust_temp->id)->where('respective_table_name','customer_rewards')->forceDelete();			
		}
		$cust_reward_temps = CustomerRewardTemp::where('month',$month)->where('year',$year)->forceDelete();

		

		$asso_reward_temps = AssociateRewardTemp::where('month',$month)->where('year',$year)->orderBy('id')->get();
		foreach($asso_reward_temps as $asso_temp){
			// dd($asso_temp);
			$asso_reward = new AssociateReward;
			$asso_reward->associate_id = $asso_temp->associate_id;
			$asso_reward->customer_id = $asso_temp->customer_id;
			$asso_reward->amount = $asso_temp->amount;
			$asso_reward->month = $asso_temp->month;
			$asso_reward->year = $asso_temp->year;
			$asso_reward->start_date = $asso_temp->start_date;
			$asso_reward->end_date = $asso_temp->end_date;
			$asso_reward->payable_days = $asso_temp->payable_days;
			$asso_reward->total_amount = $asso_temp->total_amount;
			$asso_reward->commission_percent = $asso_temp->commission_percent;
			$asso_reward->reward_type = $asso_temp->reward_type;
			$asso_reward->posting_date = $asso_temp->posting_date;
			$asso_reward->created_at = $asso_temp->created_at;
			$asso_reward->created_by = $asso_temp->created_by;
			$asso_reward->updated_at = $asso_temp->updated_at;
			$asso_reward->updated_by = $asso_temp->updated_by;
			$asso_reward->save();

			$asso_txn_temp = AssociateTransactionTemp::whereBetween('deposit_date', [$f_date, $l_date])->where('respective_table_id', $asso_temp->id)->where('respective_table_name','associate_rewards')->first();
			// dd($asso_txn_temp);
			// foreach($asso_txn_temps as $asso_txn_temp){
				if($asso_txn_temp != NULL){
					$asso_txn = new AssociateTransactions;
					$asso_txn->associate_id = $asso_txn_temp->associate_id;
					$asso_txn->customer_id = $asso_txn_temp->customer_id;
					$asso_txn->amount = $asso_txn_temp->amount;
					$asso_txn->cr_dr = $asso_txn_temp->cr_dr;
					$asso_txn->payment_type = $asso_txn_temp->payment_type;
					$asso_txn->transaction_type = $asso_txn_temp->transaction_type;
					$asso_txn->deposit_date = $asso_txn_temp->deposit_date;
					$asso_txn->posting_date = $asso_txn_temp->posting_date;
					$asso_txn->cheque_dd_date = $asso_txn_temp->cheque_dd_date;
					$asso_txn->bank_id = $asso_txn_temp->bank_id;
					$asso_txn->cheque_dd_number = $asso_txn_temp->cheque_dd_number;
					$asso_txn->respective_table_id = $asso_txn_temp->respective_table_id;
					$asso_txn->respective_table_name = $asso_txn_temp->respective_table_name;
					$asso_txn->remarks = $asso_txn_temp->remarks;
					$asso_txn->status = $asso_txn_temp->status;
					$asso_txn->created_at = $asso_txn_temp->created_at;
					$asso_txn->updated_at = $asso_txn_temp->updated_at;
					$asso_txn->created_by = $asso_txn_temp->created_by;
					$asso_txn->updated_by = $asso_txn_temp->updated_by;
					$asso_txn->save();

					$user = User::where('id', $asso_txn_temp->associate_id)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id;  
					$activity_log->user_id = $asso_txn_temp->associate_id;   
					$activity_log->statement = $user->name.' ('.$user->code.') - Generate Commission Rs. ('. $asso_txn_temp->amount .') of '.date('m-Y',strtotime($asso_txn_temp->deposit_date)).' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Generate Commission';
					$activity_log->save();
				}
			// }
			$asso_txn_temp = AssociateTransactionTemp::whereBetween('deposit_date', [$f_date, $l_date])->where('respective_table_id', $asso_temp->id)->where('respective_table_name','associate_rewards')->forceDelete();
		}
		$asso_reward_temps = AssociateRewardTemp::where('month',$month)->where('year',$year)->forceDelete();

		

		return redirect('admin/customer/generate-payout')->with('success','Payout Confirmation Successfull!');
	}


	// public function customer_activitylog($customerId)
	// {
	// 	// dd($id);
	// 	// $customer_id = decrypt($customerId);
	// 	// $customer_txn = CustomerTransactions::where('customer_id',decrypt($customerId))->orderBy('created_at')->paginate(50);
	// 	$Associatetransactions = AssociateTransactions::select('id','associate_id','customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($customerId){
 //                return $q->where('customer_id', decrypt($customerId));
 //            });
         
 //        $transactions = CustomerTransactions::select('id',DB::raw('0 as associate_id'),'customer_id','amount','payment_type','cr_dr','status','cheque_dd_number','bank_id','cheque_dd_date','deposit_date','transaction_type','respective_table_id','respective_table_name','remarks','created_by','updated_by','created_at','updated_at')->where(function($q) use ($customerId){
 //                return $q->where('customer_id', decrypt($customerId));
 //            });
        

 //        $combine_transactions = $transactions->union($Associatetransactions);
 //         // dd($combine_transactions->toSql());
 //        $transactions = $combine_transactions->orderBy('created_at')->paginate(50);
	// 	return view('admin.customers.activitylog',compact('transactions'));
	// }


	public function customer_activitylog($customerId)
	{
		// dump(decrypt($customerId));
		$activitylogs = ActivityLog::where('user_id',decrypt($customerId))->paginate(50);
		// dd($activitylogs);
		return view('admin.customers.activitylog',compact('activitylogs'));
	}



	public function delete_deposit_withdraw(Request $request)
	{
		$ids = explode(',',$request->id);
		// dd($ids[1]);
		$cust_txn = CustomerTransactions::where('id',$ids[1])->where('customer_id',$ids[0])->first();
		return view('admin.customers.delete_deposit_withdraw',compact('cust_txn'));
	}

	public function customer_delete_deposit_withdraw(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'password'=>'required',
		]);
			
		$users = User::whereLoginType('superadmin')->first();
		$user =Hash::check($request->password,$users->password);
		// dd($user);
		if($user){
			$cust_txn = CustomerTransactions::where('id',$request->table_id)->where('customer_id',$request->customer_id)->first();
			$year = date('Y',strtotime($cust_txn->deposit_date));
	        $month = date('m',strtotime($cust_txn->deposit_date));
	        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
	        if($entrylock != NULL && $entrylock->status == 0){
	            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
	        }
			$userDelete = User::where('id', $request->customer_id)->first();
			$activity_log = new ActivityLog;
	        $activity_log->created_by = auth()->user()->id; 
        	$activity_log->user_id = $userDelete->id;   
	        $activity_log->statement = $userDelete->name.' ('.$userDelete->code.') Delete'.$cust_txn->transaction_type.' Since '.date('d-m-Y');  
	        $activity_log->action_type = 'Delete '.ucwords($cust_txn->transaction_type);
	        $activity_log->save();

			$cust_txn = CustomerTransactions::where('id',$request->table_id)->where('customer_id',$request->customer_id)->delete();
			if($request->respective_table_id){
				$cust_invest = CustomerInvestment::where('id',$request->respective_table_id)->where('customer_id',$request->customer_id)->delete();
			}
			directAssociateCommission($request->customer_id);
			// dd($cust_txn);
					
			return redirect()->back()->with('success','Customer Deleted Successfully!');
		}else{
        	return redirect()->back()->with('error','Password is Wrong!');
		}
		return view('admin.customers.delete_deposit_withdraw',compact('cust_txn'));
	}

	public function customers_excel_export(Request $request)
    {
    	// dd($request->all());
        return Excel::download(new AllCustomerReportExport($request),'customers_excel_export.xlsx');
    }

    public function edit_bulktxn(Request $request)
	{	
		// $cust_investments = CustomerInvestment::get();
		// foreach($cust_investments as $key => $invest){
		// 	$txns = CustomerTransactions::where('respective_table_id',NULL)->where('respective_table_name',NULL)->get();
		// 	foreach($txns as $keys => $txn){
		// 		$txn->respective_table_id = $invest->id;
		// 		$txn->respective_table_name = 'customer_investments';
		// 		$txn->updated_at = date('Y-m-d H:i:s');
		// 		$txn->save();
		// 	}
			
		// }
		// dd();
		
		if($request->created_at != ''){
			$fromDate = date('Y-m-d 00:00:00',strtotime($request->created_at));
			$toDate = date('Y-m-d 23:59:59',strtotime($request->created_at));
			// dump($fromDate);
			// dump($toDate);
			$customer_txn = CustomerTransactions::whereBetween('created_at', [$fromDate,$toDate])->where('transaction_type', '!=', 'interest')->get();
			// dd($customer_txn);
			$banks = CompanyBank::get();
			return view('admin.customers.edit_bulktxn',compact('banks','customer_txn'));
		}
		$customer_txn = '';
		$banks = CompanyBank::get();
		return view('admin.customers.edit_bulktxn',compact('banks','customer_txn'));
	}


	public function update_bulk_txn(Request $request)
	{
		// dd($request->all());
		// foreach($request->customer_id as $key => $customerId){
		// 	dump($customerId);
		// }
		// 
		// $i = 1;
		foreach($request->customer_id as $key => $customerId){
// dd($request->deposit_date[$key]);
			$year = date('Y',strtotime($request->deposit_date[$key]));
	        $month = date('m',strtotime($request->deposit_date[$key]));
	        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
	        if($entrylock != NULL && $entrylock->status == 0){
	            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
	        }
			// dump($i);
			if($request->transaction_type[$key] == 'deposit'){
				if($customerId != NULL){

					$cust_interest_percent = CustomerInterestPercentage::where('customer_id',$customerId)->first();
					$cust_investment = CustomerInvestment::where('customer_id', $customerId)->where('id', $request->respective_table_id[$key])->first();
					if($cust_investment){
						// $cust_investment->customer_id = $customerId;
						$cust_investment->amount = $request->amount[$key];
						// $cust_investment->deposit_date = $request->deposit_date;
						$cust_investment->customer_interest_rate = $cust_interest_percent->interest_percent?$cust_interest_percent->interest_percent:Null;
						$cust_investment->created_by = \Auth::user()->id;
						$cust_investment->updated_at = date('Y-m-d');
						$cust_investment->save();
					}


					$deposit = CustomerTransactions::where('customer_id', $customerId)->where('id',$request->table_id[$key])->first();
					// $deposit->customer_id = $customerId;
					$deposit->amount = $request->amount[$key];
					$deposit->cr_dr = 'cr';
					$deposit->payment_type = $request->payment_type[$key];
					$deposit->transaction_type = 'deposit';
					// $deposit->deposit_date = $request->deposit_date;
					$deposit->bank_id = $request->bank_id[$key];
					$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
					$deposit->cheque_dd_date = $request->cheque_dd_date[$key];
					$deposit->remarks = $request->remarks[$key];
					$deposit->status = 1;
					$deposit->created_by = \Auth::user()->id;
					$deposit->updated_at = date('Y-m-d');
					$deposit->save();

					$user = User::where('id',$customerId)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id;  
        			$activity_log->user_id = $user->id;   
					$activity_log->statement = $user->name.' ('.$user->code.') Edit Deposit Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Edit Deposit';
					$activity_log->save();
				}
			}else{
				if($customerId != NULL){
					$deposit = CustomerTransactions::where('customer_id', $customerId)->first();
					// $deposit->customer_id = $customerId;
					$deposit->amount = $request->amount[$key];
					$deposit->cr_dr = 'dr';
					$deposit->payment_type = $request->payment_type[$key];
					$deposit->transaction_type = 'withdraw';
					// $deposit->deposit_date = $request->deposit_date;
					$deposit->bank_id = $request->bank_id[$key];
					$deposit->cheque_dd_number = $request->cheque_dd_number[$key];
					$deposit->remarks = $request->remarks[$key];
					$deposit->status = 1;
					$deposit->created_by = \Auth::user()->id;
					$deposit->updated_at = date('Y-m-d');
					$deposit->save();

					$user = User::where('id',$customerId)->first();
					$activity_log = new ActivityLog;
					$activity_log->created_by = auth()->user()->id;
        			$activity_log->user_id = $user->id;     
					$activity_log->statement = $user->name.' ('.$user->code.') Edit Withdraw Rs. '. $request->amount[$key] .' Since '. date('d-m-Y');  
					$activity_log->action_type = 'Edit Withdraw';
					$activity_log->save();
				}
			}
			// $i++;
		}
		return redirect()->back()->with('success','Customers Transaction Updated Successfully!');
	}

	public function deleteOneInBulkTxnForm(Request $request){
		// dd($request->all());
		$ids = explode(',',$request->id);
		// dd($ids[1]);
		$cust_txn = CustomerTransactions::where('id',$ids[0])->where('customer_id',$ids[1])->first();
		return view('admin.customers.delete_deposit_withdraw',compact('cust_txn'));
	}

	public function deleteOneInBulkTxn(Request $request){
		// dd($request->all());
		$this->validate($request,[
			'password'=>'required',
		]);
			
		$users = User::whereLoginType('superadmin')->first();
		$user =Hash::check($request->password,$users->password);
		// dd($user);
		if($user){

			$cust_txn = CustomerTransactions::where('id',$request->table_id)->where('customer_id',$request->customer_id)->first();

			$year = date('Y',strtotime($cust_txn->deposit_date));
	        $month = date('m',strtotime($cust_txn->deposit_date));
	        $entrylock = EntryLock::where('month',$month)->where('year',$year)->first();
	        if($entrylock != NULL && $entrylock->status == 0){
	            return redirect()->back()->with('error',$month.'-'.$year.' Entry is lock');
	        }

			$user = User::where('id',$request->customer_id)->first();
			$activity_log = new ActivityLog;
			$activity_log->created_by = auth()->user()->id;
        	$activity_log->user_id = $user->id;     
			$activity_log->statement = $user->name.' ('.$user->code.') Delete On Bulk Transaction Since '. date('d-m-Y');  
			$activity_log->action_type = 'Delete On Bulk Transaction';
			$activity_log->save();

			$cust_txn = CustomerTransactions::where('id',$request->table_id)->where('customer_id',$request->customer_id)->delete();
			if($request->respective_table_id){
				$cust_invest = CustomerInvestment::where('id',$request->respective_table_id)->where('customer_id',$request->customer_id)->delete();
			}
			// dd($cust_txn);
			return redirect()->back()->with('success','Customer Transaction Deleted Successfully!');
		}else{
        	return redirect()->back()->with('error','Password is Wrong!');
		}
		return view('admin.customers.edit_bulktxn',compact('cust_txn'));
	}

	public function getCustomersForBulk(Request $request){
		// dd($request->all());
		if($request->selectPayment == 'deposit'){
			// dd($request->selectPayment == 'deposit');
	        if($request->has('term')){
	            $results =  User::select('id','status','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,"-", code) AS label'))->where('status',1)->where('name','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%')->where('login_type','customer')->get();
	            return response()->json($results);
	        }
	    }elseif($request->selectPayment == 'withdraw'){
	    	// dd('2');
	        if($request->has('term')){
	            $results =  User::select('id','code','mobile',DB::raw('name as text'),DB::raw('CONCAT(name,"-", code) AS label'))->where('name','like','%'.$request->input('term').'%')->orWhere('code','like','%'.$request->input('term').'%')->where('login_type','customer')->get();
	            return response()->json($results);
	        }
	    }
    }


    public function activity_log()
    {
    	$activitylogs = ActivityLog::orderByDesc('created_at')->paginate(50);
    	return view('admin.customers.activitylog',compact('activitylogs'));
    }


    public function no_interest_form(Request $request)
    {
    	$customer = User::where('id',$request->id)->firstOrFail();
		return view('admin.customers.no_interest',compact('customer'));
    }

    public function no_interest(Request $request)
    {
    	// dd($request->all());
    	$this->validate($request,[
    		'no_interest_remarks' => 'required',
    	]);

    	$customer = CustomerDetail::where('customer_id',$request->customer_id)->firstOrFail();
    	// dd($customers);
    	$customer->no_interest_remarks = $request->no_interest_remarks;
		$customer->no_interest = !$customer->no_interest;
		$customer->save();

		$user = User::where('id', $request->customer_id)->first();
		$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
    	$activity_log->user_id = $user->id;    
        $activity_log->statement = $user->name.' ('.$user->code.') Change No Interest Since '.date('d-m-Y');  
        $activity_log->action_type = 'No Interest';
        $activity_log->save();

		return redirect()->back()->with('success','No Interest Updated Successfully!');
    }

    public function delete_all_bulktxn(Request $request)
    {
    	// dd($request);
    	$this->validate($request,[
    		'created_at' => 'required',
    	]);

    	$cust_txn = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('created_at',[Carbon::parse($request->created_at)->format('Y-m-d 00:00:01'),Carbon::parse($request->created_at)->format('Y-m-d 23:23:59')])->get();

		foreach($cust_txn as $txn){
			$user = User::where('id', $txn->customer_id)->first();
			$activity_log = new ActivityLog;
	        $activity_log->created_by = auth()->user()->id;
	    	$activity_log->user_id = $user->id;    
	        $activity_log->statement = $user->name.' ('.$user->code.') Delete Transaction Since '.date('d-m-Y');  
	        $activity_log->action_type = 'Delete Transaction';
	        $activity_log->save();
		}

    	$cust_txn = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('created_at',[Carbon::parse($request->created_at)->format('Y-m-d 00:00:01'),Carbon::parse($request->created_at)->format('Y-m-d 23:23:59')])->delete();
    	// dd($cust_txn);

    	$cust_invest = CustomerInvestment::whereBetween('created_at',[Carbon::parse($request->created_at)->format('Y-m-d 00:00:01'),Carbon::parse($request->created_at)->format('Y-m-d 23:23:59')])->delete();

    	return redirect()->back()->with('success', 'Delete All Transaction Which Deposit Or Withdrawl Entry Date '.$request->created_at);
    }

}
