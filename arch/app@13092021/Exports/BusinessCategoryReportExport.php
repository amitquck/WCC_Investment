<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\DirectAssociateCommission;
use App\Model\AssociateCommissionPercentage;
use App\Model\State;
use App\Model\ActivityLog;
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
            $cust_txn = CustomerTransactions::select('*',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))->groupBy('customer_id')->orderByDesc('cust_balance');
            $db_records = $cust_txn->get();
            $records[] = ['Customer Code','Customer Name','Business Amount'];
            foreach($db_records as $key => $record){
                if($record->cust_balance != 0){
    				$key++;
    		        $records[$key]['Customer Code'] = $record->customers->code;
    		        $records[$key]['Customer Name'] = $record->customers->name;
    		        $records[$key]['Business Amount'] = $record->cust_balance;
                }
	      	}

        }else if($this->q != '' && $this->q == 'associate'){
            $q = $this->q;

            $associates = AssociateCommissionPercentage::select(
                'customer_transactions.customer_id',
                'associate_commission_percentages.associate_id',
                DB::raw(
                    'SUM(COALESCE(CASE WHEN cr_dr = "cr" THEN amount ELSE 0 END, 0))
                    - SUM(COALESCE(CASE WHEN cr_dr = "dr" THEN amount ELSE 0 END, 0)) cust_balance'))
            ->leftJoin('customer_transactions',
                function($join){
                    $join->on(
                        'associate_commission_percentages.customer_id',
                        '=',
                        'customer_transactions.customer_id');
                })->where('associate_commission_percentages.no_of_introducer','=', 1)
                    ->where('associate_commission_percentages.status','=', 1)
                    ->where('customer_transactions.deleted_at')
                    ->groupBy(
                    'associate_commission_percentages.associate_id'
                )->orderByDesc('cust_balance');

            $db_records = $associates->get();
            
            $records[] = ['Associate Code','Associate Name','Business Amount'];
            foreach($db_records as $key => $record){
				if($record->cust_balance != 0){
                    $key++;
    		        $records[$key]['Associate Code'] = $record->associate->code;
    		        $records[$key]['Associate Name'] = $record->associate->name;
    		        $records[$key]['Business Amount'] = $record->cust_balance;
	      	    }
            }

        }else if($this->q != '' && $this->q == 'state'){
            $q = $this->q;
            $states = CustomerTransactions::select('customer_transactions.*','customer_details.state_id',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))
                    ->leftJoin('customer_details', function($join){
                        $join->on('customer_details.customer_id', '=', 'customer_transactions.customer_id');
                    })->groupBy('customer_details.state_id')->orderByDesc('cust_balance');
            $db_records = $states->get();
            $records[] = ['State','Business Amount'];
            foreach($db_records as $key => $record){
				if($record->getState != '' && $record->cust_balance != 0){
                    $key++;
    		        $records[$key]['State'] = $record->getState->name;
    		        $records[$key]['Business Amount'] = $record->cust_balance;
                }
	      	}
            
        }else if($this->q != '' && $this->q == 'city'){
            $q = $this->q;
            $cities = CustomerTransactions::select('customer_transactions.*','customer_details.city_id',DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN transaction_type = "withdraw" THEN -1 END )) AS cust_balance'))
                    ->leftJoin('customer_details', function($join){
                        $join->on('customer_details.customer_id', '=', 'customer_transactions.customer_id');
                    })->whereBetween('deposit_date',[$this->from_date,$this->to_date])->groupBy('customer_details.city_id')->orderByDesc('cust_balance');
            $db_records = $cities->get();
            $records[] = ['City','Business Amount'];
            foreach($db_records as $key => $record){
                if($record->getCity != '' && $record->cust_balance != 0){
    				$key++;
    		        $records[$key]['City'] = $record->getCity->name;
    		        $records[$key]['Business Amount'] = $record->cust_balance;
                }
	      	}
        }
          $activity_log = new ActivityLog;
          $activity_log->created_by = auth()->user()->id;  
          // $activity_log->user_id = $user->id;    
          $activity_log->statement = 'Business Category Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
          $activity_log->action_type = 'Excel Export';
          $activity_log->save();
        return collect($records);
    }
}
