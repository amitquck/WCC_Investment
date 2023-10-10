<?php 
	namespace App\Http\Controllers\Admin;

	use App\Model\Country;
	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use App\Model\State;
	class StateController extends Controller
	{
		public function store(Request $request)
		{
			$this->validate($request,[
				'name' => 'required',
				'country' => 'required',
			]);
			$state = new State;
			$state->name = $request->name;
			$state->country = $request->country;
	        $state->created_by = 1;
	        // $state->updated_at = $request->null;
	        $state->save();
	        return redirect('admin/states')->with('success','State Added Successfully! ');
		}
		public function index()
		{
			 $states = State::paginate(10);
			//  dd($states->first()->countrydetail);
			 $countries = Country::get()->pluck('name','id');
			 return view('admin.state.index',compact('states','countries'));
		}
		public function edit(Request $request){
			$state = State::where('id',$request->id)->firstOrFail();
			$countries = Country::get()->pluck('name','id');
			return view('admin.state.edit',compact('state','countries'));
		}
		public function update(Request $request){
			
			$state = State::where('id',$request->id)->firstOrFail();
			$this->validate($request,[
				'country'=>'required',
				 'name'=>'required'
				 
			]);
			$state->name = $request->name;
			$state->country = $request->country;
			$state->updated_by = \Auth::user()->id;
			$state->updated_at->getTimestamp();
			$state->save();
			return redirect('admin/states')->with('success','State Updated Successfully!');
		}
		public function status($id)
		{
			$states = State::where('id',$id)->firstOrFail();
			$states->status = !$states->status;
			$states->save();
			return redirect('admin/states')->with('success','Status Updated Successfully!');
		}
		public function destroy($id)
		{	
			State::where('id',$id)->delete();
			return redirect('admin/states')->with('success','Record Deleted Successfully!');
		}
		
	}

 ?>