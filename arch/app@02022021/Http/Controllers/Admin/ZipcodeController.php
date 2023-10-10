<?php 
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\City;
use App\Model\Country;
use App\Model\State;
use App\Model\Zipcode;

class ZipcodeController extends Controller
{
	public function index()
	{
		$zipcodes = Zipcode::paginate(50);
		$countries = Country::get()->pluck('name','id');
        $states = State::get()->pluck('name','id');
        $cities = City::get()->pluck('name','id');
		return  view('admin.zipcode.index',compact('zipcodes','cities','countries','states'));
	}
	public function store(Request $request)
	{
		// dd($request->all());
		$this->validate($request,[
			'zipcode' => 'required',
			'city'	  => 'required',
			'state'   => 'required',
			'country' => 'required',
		]);
		$zipcode = new Zipcode;
		$zipcode->zipcode = $request->zipcode;
		$zipcode->state = $request->state;
		$zipcode->city = $request->city;
		$zipcode->country = $request->country;
		$zipcode->created_at = date('Y-m-d');
		$zipcode->save();
		return redirect('admin/zipcode/zipcodes')->with('success','Zipcode Added Successfully!');
	}
	public function edit(Request $request)
	{
        $zipcode = Zipcode::where('id',$request->id)->firstOrFail();
        $countries = Country::get()->pluck('name','id');
        $states = State::get()->pluck('name','id');
        $cities = City::get()->pluck('name','id');
        return view('admin.zipcode.edit',compact('zipcode','cities','countries','states'));
    }
    public function update(Request $request)
    {
    	$zipcode = Zipcode::where('id',$request->id)->firstOrFail();
    	$this->validate($request,[
    		'zipcode' => 'required',
    		'country' => 'required',
    		'state' => 'required',
    		'city' => 'required',
    	]);
    	$zipcode->zipcode = $request->zipcode;
    	$zipcode->country = $request->country;
    	$zipcode->city = $request->city;
    	$zipcode->state = $request->state;
    	$zipcode->updated_by = \Auth::user()->id;
    	$zipcode->updated_at->getTimeStamp();
    	$zipcode->save();
    	return redirect('admin/zipcode/zipcodes')->with('success','Zipcode Updated Successfully!');
    }
	public function getCountryState(Request $request)
	{
        $state = State::where('country',$request->id)->pluck('name','id');
        return json_encode($state);
    }
    public function getStateCity(Request $request)
	{
        $city = City::where('state',$request->state_id)->pluck('name','id');
        return json_encode($city);
    }
	public function status($id)
	{
		$zipcodes = Zipcode::where('id',$id)->firstOrFail();
		$zipcodes->status = !$zipcodes->status;
		$zipcodes->save();
		return redirect('admin/zipcode/zipcodes')->with('success','Zipcode Updated Successfully!');
	}
	public function destroy($id)
	{
		Zipcode::where('id',$id)->delete();
		return redirect('admin/zipcode/zipcodes')->with('success','Zipcode Deleted Successfully!');
	}
}
?>