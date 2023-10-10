<?php

namespace App\Exports;

use App\User;
use App\Model\AssociateTransactions;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AllAssociatePaymentReportExport implements FromCollection
{

	public $associate = '';
	public $month = '';
    public $year = '';

	public function __construct($request){
		$this->associate = $request->associate;
		$this->month = $request->month;
        $this->year = $request->year;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Code','Name','Credit','Debit','Month','Year'];

        $month = str_pad($this->month,2,0,STR_PAD_LEFT);
        $associate_txn = AssociateTransactions::groupBy('associate_id');
        $year = $this->year?$this->year:'';
        $monthh = $month?$month:'';

        if($this->year != '' && $this->month != '' || $this->associate != ''){
            // dd('fdf');
            if($this->year != '' && $this->month != ''){
                $associate_txn->whereBetween('deposit_date',[date($this->year.'-'.$month.'-'.'01'),date($this->year.'-'.$month.'-'.'31')]);
            }
            if($this->associate != ''){
                $associate_txn->whereIn('associate_id', function($q){
                    return $q->select('id')->where('code','like','%'.$this->associate.'%')->orWhere('name','like','%'.$this->associate.'%')->from('users');
                });
            }
        }
        
		$db_records = $associate_txn->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Code']   = $record->associate->code;
	        $records[$key]['Name']   = $record->associate->name;
            if($monthh != '' && $year != ''){
                $records[$key]['Credit'] = $record->monthly_total_cr($monthh,$year,$record->associate->id);
            }else{
                $records[$key]['Credit'] = $record->total_cr?$record->total_cr:'0.00';
            }
            if($monthh != '' && $year != ''){
                $records[$key]['Debit'] = $record->monthly_total_dr($monthh,$year,$record->associate->id)?$record->monthly_total_dr($monthh,$year,$record->associate->id):'0.00';
                $records[$key]['Month'] = $monthh;
                $records[$key]['Year'] = $year;
            }else{
                $records[$key]['Debit']  = $record->total_dr?$record->total_dr:'0.00';
                $records[$key]['Month'] = 'N/A';
                $records[$key]['Year'] = 'N/A';
            }


	        
	
      	}
        return collect($records);
    }
}
