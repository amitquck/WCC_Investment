<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
use App\Model\ActivityLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AssociatePercentWiseBalanceReportExport implements FromCollection
{

	public $month = '';
	public $year = '';
	public $customer = '';
    public $associate = '';

	public function __construct($request){
		$this->month = str_pad($request->month,2,0,STR_PAD_LEFT);
		$this->year = $request->year;
		$this->customer = $request->customer;
        $this->associate = $request->associate;
	}

    /**
    * @return \Illuminate\Support\Collection, 'Associate Breakage'
    */
    public function collection()
    {
    	$records = [];
        $customers = User::where('login_type', 'customer');
        if($this->customer != ''){
            $customers->where('name','like','%'.$this->customer.'%')->orWhere('code','like','%'.$this->customer.'%');
        }elseif($this->associate != ''){
            $associates = User::where('name','like','%'.$this->associate.'%')->orWhere('code','like','%'.$this->associate.'%')->where('login_type','associate')->pluck('id');
            $customers->whereHas('associatecommissions', function($q) use ($associates){
                return $q->whereIn('associate_id', $associates);
            });
        }
		$db_records = $customers->get();
        $i = 0;
		foreach($db_records as $key => $record){//$transaction->associate->name//customers
            $records[$i] = ['Client Code', 'Client Name', 'Balance', 'Interest Percent', 'Interest','No Of Introducer', 'Associate Code', 'Associate Name', 'Commission Percent', 'Brokerage'];
            
            $i = $i+1;
            // dump($i);
			$records[$i]['Client Code'] = $record->code;
			$records[$i]['Client Name'] = $record->name;
            $records[$i]['Balance']     = $record->customer_current_balance();
            $records[$i]['Interest Percent'] = $record->customeractiveinterestpercent?$record->customeractiveinterestpercent->interest_percent:'N/A';
            
            $records[$i]['Interest']     = (($record->customer_current_balance()*$record->customeractiveinterestpercent->interest_percent)/100)/12;
            // $records[] = [''];
            // $records[] = [''];
            // $records[] = [''];
            // $records[] = [''];
            // $records[] = [''];
            // $i = $i+1;
            // $records[$i] = ['No Of Introducer', 'Associate Code', 'Associate Name', 'Commission Percent', 'Brokerage'];
            
            foreach($record->associatecommissions as $key_a => $commission){
                $i = $i+$key_a;
                // dd($i);
                if($key_a>0){
                    $records[$i]['Client Code'] = '';
                     $records[$i]['Client Name'] = '';
                     $records[$i]['Balance'] = '';
                     $records[$i]['Interest Percent'] = '';
                     $records[$i]['Interest'] = '';
                }
                $asso_monthly_balance = (($record->customer_current_balance()*$commission->commission_percent)/100)/12;
                     

                     $records[$i]['No Of Introducer'] = $commission->no_of_introducer;
                     $records[$i]['Associate Code'] = $commission->associate->code;
                     $records[$i]['Associate Name'] = $commission->associate->name;
                     $records[$i]['Commission Percent'] = $commission->commission_percent;
                     $records[$i]['Brokerage'] = $asso_monthly_balance;
                  
            }
            // dd($records);
            $records[] = ['']; 
            $i++;
      	}
        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;  
        // $activity_log->user_id = $user->id;    
        $activity_log->statement = 'Associate Percent Wise Balance Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');  
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}

               