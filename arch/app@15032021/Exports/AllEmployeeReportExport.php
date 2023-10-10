<?php

namespace App\Exports;

use App\User;
use App\Model\CustomerDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB; 

class AllEmployeeReportExport implements FromCollection
{

	// public $state_id = '';
	// public $city_id = '';
	// public $id = '';

	public function __construct($request){
		// $this->state_id = $request->state_id;
		// $this->city_id = $request->city_id;
		// $this->id = $request->id;
	}

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$records = [];
    	$records[] = ['Code','Name','Mobile','Email','Member Since'];
    	$users = User::whereLoginType('employee');
    	// if($this->state_id != null){
	    //     $users->whereHas('customerdetails',function($q){
     //            return $q->where('state_id',$this->state_id);
     //        });
     //    }else if($this->city_id != null){
     //        $users->whereHas('customerdetails',function($q){
     //            return $q->where('city_id',$this->city_id);

     //        });
     //    }
        
		$db_records = $users->orderByDesc('created_at')->get();
		foreach($db_records as $key => $record){
			$key++;
	        $records[$key]['Code'] = $record->code;
	        $records[$key]['Name'] = $record->name;
	        $records[$key]['Mobile'] = $record->mobile;
	        $records[$key]['Email'] = $record->email?$record->email:'N/A';
	        $records[$key]['Member Since'] = $record->created_at;
      	}
        return collect($records);
    }
}
