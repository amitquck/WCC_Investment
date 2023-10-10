<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use App\Model\AssociateCommissionPercentage;
use App\Model\City;
use App\Model\ActivityLog;

class CityBusinessReportExport implements FromCollection
{
	public $city_id = '';
  	public $from_date = '';
  	public $to_date = '';

	public function __construct($request){
    $this->city_id = $request->city_id;
    $this->from_date = $request->from_date;
    $this->to_date = $request->to_date;
	}

    public function collection()
    {

        $city = City::where('id', $this->city_id)->first();

        $records[0] = [$city->name];
        $records[1] = ['Customer Code','Customer Name','Mobile','Email','Balance'];
        $users = User::whereLoginType('customer');

        if($this->city_id != null){
            $users->whereHas('customerdetails',function($q){
                return $q->where('city_id',$this->city_id);
            });
        }
        $users = $users->orderByDesc('created_at')->paginate(50);
        $i = 3;
        foreach($users as $key => $user){
          $i = $i+1;
            if($this->from_date == null){
                if($user->customerMainBalance() != 0){
                    $records[$i]['Customer Code'] = $user->code;
                    $records[$i]['Customer Name'] = $user->name;
                    $records[$i]['Mobile'] = $user->mobile?$user->mobile:'N/A';
                    $records[$i]['Email'] = $user->email?$user->email:'N/A';
                    $records[$i]['Balance'] = $user->customerMainBalance();
                }
            }elseif(date('Y-m',strtotime($this->from_date)) == date('Y-m',strtotime('-1 month'))){
                if($user->customerLastMonthMainBalance() != 0){
                    $records[$i]['Customer Code'] = $user->code;
                    $records[$i]['Customer Name'] = $user->name;
                    $records[$i]['Mobile'] = $user->mobile?$user->mobile:'N/A';
                    $records[$i]['Email'] = $user->email?$user->email:'N/A';
                    $records[$i]['Balance'] = $user->customerLastMonthMainBalance();
                }
            }elseif(date('Y-m',strtotime($this->from_date)) == date('Y-m')){
                if($user->customerThisMonthMainBalance() != 0){
                    $records[$i]['Customer Code'] = $user->code;
                    $records[$i]['Customer Name'] = $user->name;
                    $records[$i]['Mobile'] = $user->mobile?$user->mobile:'N/A';
                    $records[$i]['Email'] = $user->email?$user->email:'N/A';
                    $records[$i]['Balance'] = $user->customerThisMonthMainBalance();
                }
            }
          $i++;
        }

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id;
        // $activity_log->user_id = $user->id;
        $activity_log->statement = 'City Business Report Excel Export By '.auth()->user()->name.' Since At '.date('d-m-Y');
        $activity_log->action_type = 'Excel Export';
        $activity_log->save();
        return collect($records);
    }
}
