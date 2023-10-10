<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerDetail;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AllCustomerReportExport implements FromCollection
{

	public $state_id = '';
	public $city_id = '';
	public $id = '';

	public function __construct($request){
		$this->state_id = $request->state_id;
		$this->city_id = $request->city_id;
		$this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Code','Name','Father Name','Mobile','Email','Gender','DOB','Age','Nationality','Address One','Address Two','Zipcode','City','State','Country','Account Holder Name','Bank Name','Account No','IFSC Code','Pan Number','Payment Type','Nominee Name','Nominee Age','Nominee DOB','Nominee Gender','Relationship With Applicant','Nominee Address One','Nominee Address Two','Nominee Zipcode','Nominee City','Nominee State','Nominee Country','Member Since'];
    	$users = User::whereLoginType('customer');
    	if($this->state_id != null){
	        $users->whereHas('customerdetails',function($q){
                return $q->where('state_id',$this->state_id);
            });
        }else if($this->city_id != null){
            $users->whereHas('customerdetails',function($q){
                return $q->where('city_id',$this->city_id);

            });
        }
        
		$db_records = $users->orderByDesc('created_at')->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Code'] = $record->code;
	        $records[$key]['Name'] = $record->name;
	        $records[$key]['Father Name'] = isset($record->customerdetails->father_husband_wife)?$record->customerdetails->father_husband_wife:'N/A';
	        $records[$key]['Mobile'] = $record->mobile;
	        $records[$key]['Email'] = $record->email?$record->email:'N/A';
	        $records[$key]['Gender'] = isset($record->customerdetails->sex)?$record->customerdetails->sex:'N/A';
	        $records[$key]['DOB'] = isset($record->customerdetails->dob)?$record->customerdetails->dob:'N/A';
	        $records[$key]['Age'] = isset($record->customerdetails->age)?$record->customerdetails->age:'N/A';
	        $records[$key]['Nationality'] = isset($record->customerdetails->nationality)?$record->customerdetails->nationality:'N/A';
	        $records[$key]['Address One'] = isset($record->customerdetails->address_one)?$record->customerdetails->address_one:'N/A';
	        $records[$key]['Address Two'] = isset($record->customerdetails->address_two)?$record->customerdetails->address_two:'N/A';
	        $records[$key]['Zipcode'] = isset($record->customerdetails->zipcode)?$record->customerdetails->zipcode:'N/A';
	        $records[$key]['City'] = isset($record->customerdetails->citycity->city_id)?$record->customerdetails->citycity->city_id:'N/A';
	        $records[$key]['State'] = isset($record->customerdetails->statestate->state_id)?$record->customerdetails->statestate->state_id:'N/A';
	        $records[$key]['Country'] = isset($record->customerdetails->countrycountry->country_id)?$record->customerdetails->countrycountry->country_id:'N/A';
	        $records[$key]['Account Holder Name'] = isset($record->customerdetails->account_holder_name)?$record->customerdetails->account_holder_name:'N/A';
	        $records[$key]['Bank Name'] = isset($record->customerdetails->bank)?$record->customerdetails->bank:'N/A';
	        $records[$key]['Account No'] = isset($record->customerdetails->account_number)?$record->customerdetails->account_number:'N/A';
	        $records[$key]['IFSC Code'] = isset($record->customerdetails->ifsc_code)?$record->customerdetails->ifsc_code:'N/A';
	        $records[$key]['Pan Number'] = isset($record->customerdetails->pan_no)?$record->customerdetails->pan_no:'N/A';
	        $records[$key]['Payment Type'] = isset($record->customerdetails->payment_type)?$record->customerdetails->payment_type:'N/A';
	        $records[$key]['Nominee Name'] = isset($record->customerdetails->nominee_name)?$record->customerdetails->nominee_name:'N/A';
	        $records[$key]['Nominee Age'] = isset($record->customerdetails->nominee_age)?$record->customerdetails->nominee_age:'N/A';
	        $records[$key]['Nominee DOB'] = isset($record->customerdetails->nominee_dob)?$record->customerdetails->nominee_dob:'N/A';
	        $records[$key]['Nominee Gender'] = isset($record->customerdetails->nominee_sex)?$record->customerdetails->nominee_sex:'N/A';
	        $records[$key]['Relationship With Applicant'] = isset($record->customerdetails->nominee_relation_with_applicable)?$record->customerdetails->nominee_relation_with_applicable:'N/A';
	        $records[$key]['Nominee Address One'] = isset($record->customerdetails->nominee_address_one)?$record->customerdetails->nominee_address_one:'N/A';
	        $records[$key]['Nominee Address Two'] = isset($record->customerdetails->nominee_address_two)?$record->customerdetails->nominee_address_two:'N/A';
	        $records[$key]['Nominee Zipcode'] = isset($record->customerdetails->nominee_zipcode)?$record->customerdetails->nominee_zipcode:'N/A';
	        $records[$key]['Nominee City'] = isset($record->customerdetails->city->nominee_city_id)?$record->customerdetails->city->nominee_city_id:'N/A';
	        $records[$key]['Nominee State'] = isset($record->customerdetails->state->nominee_state_id)?$record->customerdetails->state->nominee_state_id:'N/A';
	        $records[$key]['Nominee Country'] = isset($record->customerdetails->country->nominee_country_id)?$record->customerdetails->country->nominee_country_id:'N/A';
	        $records[$key]['Member Since'] = $record->created_at;
      	}
      	$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'All Customer Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save(); 
        return collect($records);
    }
}
