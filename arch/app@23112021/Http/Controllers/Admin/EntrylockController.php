<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;
use App\Model\EntryLock;
use App\Model\ActivityLog;

// use App\Model\CustomerService;
class EntrylockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $locks = EntryLock::paginate(12);
        return view('admin.entrylocks.index',compact('locks'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'year' => 'required',
        ]);

        for($i=01; $i<=12; $i++){
            $entrylock = new EntryLock;
            $entrylock->month = str_pad($i, 2, '0', STR_PAD_LEFT);
            // dd($entrylock->month);
            $entrylock->year = $request->year;
            $entrylock->created_at = date('Y-m-d H:i:s');
            $entrylock->created_by = \Auth()->user()->id;
            $entrylock->save();
        }
        return redirect()->back()->with('success',$request->year.' Year Created Successfully !');
    }

    public function entrylockStatus($id)
    {
        $entrylock = EntryLock::where('id',$id)->firstOrFail();
        $entrylock->status = !$entrylock->status;
        $entrylock->save();

        $activity_log = new ActivityLog;
        $activity_log->created_by = auth()->user()->id; 
        // $activity_log->user_id = $customer->id;   
        if($entrylock->status == 1){
            $activity_log->statement = $entrylock->month.'-'.$entrylock->year .' Unlock By '.auth()->user()->login_type .' Since '. date('d-m-Y');
        }else{
            $activity_log->statement = $entrylock->month.'-'.$entrylock->year .' Lock By '.auth()->user()->login_type .' Since '. date('d-m-Y');
        }
          
        $activity_log->action_type = 'Change Entry Lock Status';
        $activity_log->save();
        return redirect()->back()->with('success','Status Updated Successfully!');
    }

}
