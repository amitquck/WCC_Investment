<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\City;
use App\Model\Country;
use App\Model\State;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::paginate(100);
        $countries = Country::get()->pluck('name','id');
        $states = State::get()->pluck('name','id');
        return view('admin.city.index',compact('cities','countries','states'));
    }
    public function store(Request $request)
    {
        $this->validate($request,[
            'country'=>'required',
             'state'=>'required',
             'name'=>'required',
        ]);
        $cities  = new City;
        $cities->name = $request->name;
        $cities->country = $request->country;
        $cities->state = $request->state;
        $cities->created_by = 1;
        $cities->save();
        // dd($request);
        return redirect('admin/city/cities')->with('success','City  Added Successfully!');
    }

    public function edit(Request $request){
        $city = City::where('id',$request->id)->firstOrFail();
        $countries = Country::get()->pluck('name','id');
        $states = State::get()->pluck('name','id');
        return view('admin.city.edit',compact('city','countries','states'));
    }

  
    public function update(Request $request)
    {
        $cities = city::where('id',$request->id)->firstOrFail();
        $this->validate($request,[
            'country'=>'required',
             'state'=>'required',
             'name'=>'required',
        ]);
        $cities->name = $request->name;
        $cities->country = $request->country;
        $cities->state = $request->state;
        $cities->updated_by = \Auth::user()->id;
        $cities->updated_at->getTimestamp();
        $cities->save();
        return redirect('admin/city/cities')->with('success','City Name Updated Successfully!');
    }

    public function getcountry(Request $request)
    {
        $state = State::where('country',$request->id)->pluck('name','id');
        return json_encode($state);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $cities = City::where('id',$id)->firstOrFail();
        $cities->status = !$cities->status;
        $cities->save();
        return redirect('admin/city/cities')->with('success','Status Updated Successfully!');
    }
    public function destroy($id)
    {
        City::where('id',$id)->delete();
        return redirect('admin/city/cities')->with('success','Record Deleted Successfully!');
    }

}
