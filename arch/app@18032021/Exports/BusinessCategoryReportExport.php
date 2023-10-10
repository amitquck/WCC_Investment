<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\DirectAssociateCommission;
use App\Model\State;
use App\Model\City;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class BusinessCategoryReportExport implements FromCollection
{

	public $q = '';

	public function __construct($request){
		$this->q = $request->q;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
        if($this->q != '' && $this->q == 'customer'){
            $q = $this->q;
            $cust_txn = CustomerTransactions::where('cr_dr','cr')->where('transaction_type','deposit')->groupBy('customer_id');
            $db_records = $cust_txn->get();
            $records[] = ['Customer Code','Customer Name','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['Customer Code'] = $record->customers->code;
		        $records[$key]['Customer Name'] = $record->customers->code;
		        $records[$key]['Business Amount'] = $record->customer_wise_business?$record->customer_wise_business:'0.00';
	      	}

        }else if($this->q != '' && $this->q == 'associate'){
            $q = $this->q;
            $associates = DirectAssociateCommission::groupBy('associate_id');
            $db_records = $associates->get();
            
            $records[] = ['Associate Code','Associate Name','Business Amount'];
            foreach($db_records as $key => $record){
				$key++;
		        $records[$key]['Associate Code'] = $record->associate->code;
		        $records[$key]['Associate Name'] = $record->associate->code;
		        $records[$key]['Business Amount'] = $record->associate_wise_business?$record->associate_wise_business:'0.00';
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
		        $records[$key]['Business Amount'] = $record->state_wise_business?$record->state_wise_business:'0.00';
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
		        $records[$key]['Business Amount'] = $record->city_wise_business?$record->city_wise_business:'0.00';
	      	}
        }

        return collect($records);
    }
}
