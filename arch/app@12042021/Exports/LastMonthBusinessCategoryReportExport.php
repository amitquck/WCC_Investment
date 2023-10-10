<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\DirectAssociateCommission;
use App\Model\ActivityLog;
use App\Model\State;
use App\Model\City;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class LastMonthBusinessCategoryReportExport implements FromCollection
{

	public $q = '';
	public $from_date = '';
	public $to_date = '';

	public function __construct($request){
		$this->q = $request->q;
		$this->from_date = $request->from_date;
		$this->to_date = $request->to_date;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
        if($this->q != '' && $this->q == 'customer'){
            $q = $this->q;
            $cust_txn = CustomerTransactions::where('transaction_type', '!=', 'interest')->whereBetween('deposit_date',[$this->from_date,$this->to_date])->groupBy('customer_id');
            $db_records = $cust_txn->get();
            $records[] = ['Customer Code','Customer Name','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['Customer Code'] = $record->customers->code;
		        $records[$key]['Customer Name'] = $record->customers->name;
		        $records[$key]['Business Amount'] = $record->customer_wise_last_month_business?$record->customer_wise_last_month_business:'0.00';
	      	}

        }else if($this->q != '' && $this->q == 'associate'){
            $q = $this->q;
            $associates = DirectAssociateCommission::whereBetween('created_at',[$this->from_date,$this->to_date])->groupBy('associate_id');
            $db_records = $associates->get();
            
            $records[] = ['Associate Code','Associate Name','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['Associate Code'] = $record->associate->code;
		        $records[$key]['Associate Name'] = $record->associate->name;
		        $records[$key]['Business Amount'] = $record->associate_wise_last_month_business?$record->associate_wise_last_month_business:'0.00';
	      	}

        }else if($this->q != '' && $this->q == 'state'){
            $q = $this->q;
            $states = State::whereIn('id',function($q){
                return $q->select('customer_details.state_id')->from('customer_details');
            });
            $db_records = $states->get();
            $records[] = ['State','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['State'] = $record->name;
		        $records[$key]['Business Amount'] = $record->state_wise_last_month_business?$record->state_wise_last_month_business:'0.00';
	      	}
            
        }else if($this->q != '' && $this->q == 'city'){
            $q = $this->q;
            $cities = City::whereIn('id',function($q){
                return $q->select('customer_details.city_id')->from('customer_details');
            });
            $db_records = $cities->get();
            $records[] = ['City','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['City'] = $record->name;
		        $records[$key]['Business Amount'] = $record->city_wise_last_month_business?$record->city_wise_last_month_business:'0.00';
	      	}
        }
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Last Month Business Category Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save(); 
        return collect($records);
    }
}
