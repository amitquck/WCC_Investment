<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerDetail;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AllAssociatesReportExport implements FromCollection
{

	public $associate_name = '';
	public $associate_mobile = '';
	public $associate_code = '';
	public $associate_bank_account = '';
	public $id = '';

	public function __construct($request){
		$this->associate_code = $request->associate_code;
		$this->associate_name = $request->associate_name;
		$this->associate_mobile = $request->associate_mobile;
		$this->associate_bank_account = $request->associate_bank_account;
		$this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Code','Name','Father Name','Mobile','Email','Gender','DOB','Age','Nationality','Address One','Address Two','Zipcode','City','State','Country','Account Holder Name','Bank Name','Account No','IFSC Code','Pan Number','Payment Type','Nominee Name','Nominee Age','Nominee DOB','Nominee Gender','Relationship With Applicant','Nominee Address One','Nominee Address Two','Nominee Zipcode','Nominee City','Nominee State','Nominee Country','Member Since'];
    	$users = User::whereLoginType('associate');
    	if($this->associate_name != null){
            $users->where('name','like','%'.$this->associate_name.'%');
        }else if($this->associate_mobile != null){
            $users->where('mobile','like','%'.$this->associate_mobile.'%');
        }else if($this->associate_code != null){
            $users->where('code','like','%'.$this->associate_code.'%');
        }else if($this->associate_bank_account != null){
            $users->whereHas('associatedetail',function($q){
                return $q->where('account_number','like','%'.$this->associate_bank_account.'%');

            });
        }
        
		$db_records = $users->orderByDesc('created_at')->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Code'] = $record->code;
	        $records[$key]['Name'] = $record->name;
	        $records[$key]['Father Name'] = isset($record->associatedetail->father_husband_wife)?$record->associatedetail->father_husband_wife:'N/A';
	        $records[$key]['Mobile'] = $record->mobile;
	        $records[$key]['Email'] = $record->email?$record->email:'N/A';
	        $records[$key]['Gender'] = isset($record->associatedetail->sex)?$record->associatedetail->sex:'N/A';
	        $records[$key]['DOB'] = (isset($record->associatedetail->dob) && $record->associatedetail->dob != '1970-01-01')?$record->associatedetail->dob:'N/A';
	        $records[$key]['Age'] = isset($record->associatedetail->age)?$record->associatedetail->age:'N/A';
	        $records[$key]['Nationality'] = isset($record->associatedetail->nationality)?$record->associatedetail->nationality:'N/A';
	        $records[$key]['Address One'] = isset($record->associatedetail->address_one)?$record->associatedetail->address_one:'N/A';
	        $records[$key]['Address Two'] = isset($record->associatedetail->address_two)?$record->associatedetail->address_two:'N/A';
	        $records[$key]['Zipcode'] = isset($record->associatedetail->zipcode)?$record->associatedetail->zipcode:'N/A';
	        $records[$key]['City'] = isset($record->associatedetail->citycity->name)?$record->associatedetail->citycity->name:'N/A';
	        $records[$key]['State'] = isset($record->associatedetail->statestate->name)?$record->associatedetail->statestate->name:'N/A';
	        $records[$key]['Country'] = isset($record->associatedetail->countrycountry->name)?$record->associatedetail->countrycountry->name:'N/A';
	        $records[$key]['Account Holder Name'] = isset($record->associatedetail->account_holder_name)?$record->associatedetail->account_holder_name:'N/A';
	        $records[$key]['Bank Name'] = isset($record->associatedetail->bank_name)?$record->associatedetail->bank_name:'N/A';
	        $records[$key]['Account No'] = isset($record->associatedetail->account_number)?$record->associatedetail->account_number:'N/A';
	        $records[$key]['IFSC Code'] = isset($record->associatedetail->ifsc_code)?$record->associatedetail->ifsc_code:'N/A';
	        $records[$key]['Pan Number'] = isset($record->associatedetail->pan_no)?$record->associatedetail->pan_no:'N/A';
	        $records[$key]['Payment Type'] = isset($record->associatedetail->payment_type)?$record->associatedetail->payment_type:'N/A';
	        $records[$key]['Nominee Name'] = isset($record->associatedetail->nominee_name)?$record->associatedetail->nominee_name:'N/A';
	        $records[$key]['Nominee Age'] = isset($record->associatedetail->nominee_age)?$record->associatedetail->nominee_age:'N/A';
	        $records[$key]['Nominee DOB'] = isset($record->associatedetail->nominee_dob)?$record->associatedetail->nominee_dob:'N/A';
	        $records[$key]['Nominee Gender'] = isset($record->associatedetail->nominee_sex)?$record->associatedetail->nominee_sex:'N/A';
	        $records[$key]['Relationship With Applicant'] = isset($record->associatedetail->nominee_relation_with_applicable)?$record->associatedetail->nominee_relation_with_applicable:'N/A';
	        $records[$key]['Nominee Address One'] = isset($record->associatedetail->nominee_address_one)?$record->associatedetail->nominee_address_one:'N/A';
	        $records[$key]['Nominee Address Two'] = isset($record->associatedetail->nominee_address_two)?$record->associatedetail->nominee_address_two:'N/A';
	        $records[$key]['Nominee Zipcode'] = isset($record->associatedetail->nominee_zipcode)?$record->associatedetail->nominee_zipcode:'N/A';
	        $records[$key]['Nominee City'] = isset($record->associatedetail->city->name)?$record->associatedetail->city->name:'N/A';
	        $records[$key]['Nominee State'] = isset($record->associatedetail->state->name)?$record->associatedetail->state->name:'N/A';
	        $records[$key]['Nominee Country'] = isset($record->associatedetail->country->name)?$record->associatedetail->country->name:'N/A';
	        $records[$key]['Member Since'] = $record->created_at;
      	}
      	$activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'All Associates Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save(); 
        return collect($records);
    }
}
