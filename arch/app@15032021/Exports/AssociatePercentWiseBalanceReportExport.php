<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerTransactions;
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
            $records[$i] = ['Client Code', 'Client Name', 'Interest Percent', 'Balance', 'Month', 'Year'];
            $i = $i+1;
            // dump($records);
			$records[$i]['Client Code'] = $record->code;
			$records[$i]['Client Name'] = $record->name;
            $records[$i]['Interest Percent'] = $record->customeractiveinterestpercent?$record->customeractiveinterestpercent->interest_percent:'N/A';
            if($this->month != '' && $this->year != ''){
                $records[$i]['Balance']     = $record->customer_monthly_balance($this->month,$this->year);
            }else{
                $records[$i]['Balance']     = $record->customer_current_balance();
            }
            // $records[] = [''];
            // $records[] = [''];
            // $records[] = [''];
            $i = $i+1;
            $records[$i] = ['Associate Code','Associate Name','Commission %','Balance'];
            
            foreach($record->associatecommissions as $key => $commission){
                $i = $i+1;
                if($this->month != '' && $this->year != ''){
                    $asso_monthly_balance = (($record->customer_monthly_balance($this->month,$this->year)*$commission->commission_percent)/100);
                     $records[$i] = [$commission->associate->code,$commission->associate->name,$commission->commission_percent,$asso_monthly_balance];
                }else{
                    $asso_monthly_balance = (($record->customer_current_balance()*$commission->commission_percent)/100);
                     $records[$i] = [$commission->associate->code,$commission->associate->name,$commission->commission_percent,$asso_monthly_balance];
                }
              
            }
            if($this->month != '' && $this->year != ''){
                $records[$i]['Month']  =  $this->month;
                $records[$i]['Year']  =  $this->year;
            }else{
                $records[$i]['Month']  = 'N/A';
                $records[$i]['Year']  =  'N/A';
            }
            $records[] = ['']; 
            // $key++;
            $i++;
      	}
        return collect($records);
    }
}

               