<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\AssociateDetail;
use App\Model\AssociateTransaction;
use App\Model\Permission;
use App\Model\UserPermission;
use Auth;
use Redirect;
use DB; 
use Carbon\Carbon;
use App\User;

// use App\Model\CustomerService;
class EmployeeController extends Controller
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
        $employees = User::where('login_type','employee')->paginate(10);
        return view('admin.employee.index',compact('employees'));
    }
    public function addEmployeePage(){
        return view('admin.employee.addemployee');
    }
    public function store(Request $request){
        $this->validate($request,[
            'employee_id' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
            'name' => 'required',
            'mobile' => 'required|numeric|min:10',
            'email' => 'required|email',
            'password' => 'required',

        ]);
        $employee  = new User;
        $employee->code = $request->employee_id; 
        $employee->name = $request->name; 
        $employee->mobile = $request->mobile; 
        $employee->email =$request->email; 
        $employee->login_type = 'employee'; 
        $employee->password = Hash::make($request->password);
        $employee->save();
        return redirect('admin/employee/employee')->with('success',$request->name.' As a Employee Added Succesfully');
        
    }
    public function edit(Request $request,$id){
        $employee = User::whereId(decrypt($id))->first();
        return view('admin.employee.edit',compact('employee'));
    }
    public function employeeUpdate(Request $request){
       $this->validate($request,[
            'employee_id' => 'required|max:20|unique:users,code,NULL,id,deleted_at,NULL',
            'name' => 'required',
            'mobile' => 'required|numeric|min:10',
            'email' => 'required|email',
            'password' => 'required_if:password,true|same:confirm_password',

        ]);
        $employee  =User::whereId($request->id)->first();
        $employee->code = $request->employee_id; 
        $employee->name = $request->name; 
        $employee->mobile = $request->mobile; 
        $employee->email =$request->email; 
        // $employee->login_type = 'employee'; 
        // $employee->password = Hash::make($request->password);
        $employee->save();
        return redirect('admin/employee/employee')->with('success',$request->name.' As a Employee Updated Succesfully');
        
    }
    public function status($id){
        // dd($id);
			$employee = User::where('id',decrypt($id))->firstOrFail();
			$employee->status = !$employee->status;
			$employee->save();
			return redirect('admin/employee/employee')->with('success','Status Updated Successfully!');
		}

		public function destroy($id){
			$employee = User::where('id',decrypt($id))->delete();
			// CustomerDetail::where('employee_id',decrypt($id))->delete();
        	return redirect('admin/employee/employee')->with('success','Employee Deleted Successfully!');
		}
    public function privilege(Request $request,$id)
    {
        $permissions = Permission::all();
        $employee = User::with(['permissions' => function($q){
            return $q->select('action_name', 'permission_id', 'user_id','status');
        }])->where('login_type','employee')->where('id',decrypt($id))->firstOrFail();
        $employeePermissions = $employee->permissions->pluck('status', 'action_name');
        return view('admin.employee.privilege',compact('permissions','employee','employeePermissions'));
    }


    public function apply_privilege(Request $request,$id)
    {


        if($request === null){
            return redirect('admin/employee/privilege')->with('error','Please! Select Any One');
        }

        $allpermission = Permission::all();
        foreach($allpermission as $key => $permission){
            foreach(explode(',',$permission['actions']) as $permission_action){
               $permission_slug_name = $permission_action.'_'.$permission->slug;
                $status = isset($request->$permission_slug_name)?1:0; 
                $alreadyExists = UserPermission::where('action_name',$permission_slug_name)->where('user_id',decrypt($id))->first();
                // dd($alreadyExists);
                if($alreadyExists){
                $alreadyExists->update([
                    'user_id' =>decrypt($id),
                    'action_name' =>$permission_slug_name,
                    'status' =>$status,
                ]);
                }else{
                     $permission->userpermission()->create([
                    'user_id' =>decrypt($id),
                    'action_name' =>$permission_slug_name,
                    'status' =>$status,
                ]);
                }
            }
        }
        //$userpermission = UserPermission::whereId(decrypt($id))->get();
        return redirect(route('admin.privilege',$id))->withSuccess('Privileges has been updated');

        // $this->validate($request,[
        //     'user_id'=>'required',
        //     'action_name'=>'required',
        // ]);
        // dd($request->all());
        // foreach ($request->all() as $action_name => $value) {
            
        // }
// dd();
    }
    public function profile(){
        return view('admin.employee.profile');
    }

}
