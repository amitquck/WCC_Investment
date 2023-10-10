<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 
use Carbon\Carbon;

class CustomerWiseMonthlyReportExport implements FromCollection
{

	public $id = '';

	public function __construct($request){
		$this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	
    	
    	// $interest[] = ['Customer Interest'];
    	// $associate_details[] = ['Associate Name','Associate Mobile','Associate Email','Commission Amount'];
    	
    	$user = User::whereId($this->id)->first();
        // $monthlyReport = array();
        $rewards = $user->customerrewards()->groupBy(DB::Raw('CONCAT(year,month)'))->get();
        foreach($rewards as $reward){
            $monthlyReport[$reward->year.'-'.$reward->month]['interest_amount'] = $reward->sum_monthly_interest;
            $monthlyReport[$reward->year.'-'.$reward->month]['customer'] = $reward->customer;
            $monthlyReport[$reward->year.'-'.$reward->month]['commissions'] = $user->customerassociaterewards()->where('month',$reward->month)->where('year',$reward->year)->whereCustomerId($this->id)->groupBy(DB::Raw('CONCAT(year,month)'),'associate_id')->get();

			
        }


        foreach($monthlyReport as $key => $monthd){
      		$records[] = ['Month-Year',Carbon::parse($key)->format('M-Y')];

      		$records[] = ['Customer Interest','₹ '.($monthd['interest_amount'])];
      		
      		
      		$records[] = ['Associate Name','Associate Mobile','Associate Email','Commission Amount'];
      		foreach($monthd['commissions'] as $key => $commission){
      			$records[] = [$commission->associate->name,$commission->associate->mobile,$commission->associate->email,'₹ '.$commission->customer_sum_monthly_commission];
              
          	}
          	$records[] = [''];
    	}
    //         foreach($monthlyReport as $key => $monthd){
				// $month_year[$key]['Month-Year'] = $key;
		  //       $interest[$key]['Customer Interest'] = $monthd['interest_amount'];
		  //       foreach($monthd['commissions'] as $no => $commission){
		  //       	$no++;
			 //        $associate_details[$no]['Associate Name'] = $commission->associate->name;
			 //        $associate_details[$no]['Associate Mobile'] = $commission->associate->mobile;
			 //        $associate_details[$no]['Associate Email'] = $commission->associate->email;
			 //        $associate_details[$no]['Commission Amount'] = $commission->customer_sum_monthly_commission;
			 //    }
	   //    	}
        return collect($records);
    }
}
