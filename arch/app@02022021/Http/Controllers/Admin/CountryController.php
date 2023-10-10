<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Country;
class CountryController extends Controller

{
    /**
     * Create a new controller instance.
    
     */
    public function index()
    {
        $countries = Country::paginate(10);
        return view('Admin.country.countries',compact('countries'));
    }

     public function countrystore(Request $request)
    {
        $this->validate($request,[
             'name'=>'required',
        ]);
        $country = new Country;
        $country->name = $request->name;
        $country->created_by = 1;
        $country->updated_at = $request->null;
        $country->save();
        return redirect('Admin/countries')->with('success','Country Added Successfully!');
    }
      public function destroy($id)
    {
        Country::where('id',$id)->delete();

        return redirect('admin/countries')->with('success','Record Delete Successfully! ');
    }
     public function edit(Request $request)
    {
        $country = Country::where('id',$request->id)->firstOrFail();
        return view('Admin.country.edit',compact('country'));
        
    }
     public function update(Request $request)
     {
        $country = Country::where('id',$request->id)->firstOrFail();
        $this->validate($request,[
        
             'name'=>'required',
           
        ]);
        // dd(\Auth::user());
        $country->name = $request->name;
        $country->updated_by = \Auth::user()->id;
        $country->updated_at = $request->null;
        $country->save();
        return redirect('Admin/countries')->with('success','Country Name Updated Successfully!');
     }


    public function status($id)
    {
        $country = Country::where('id',$id)->firstOrFail();
        $country->status = !$country->status;
        $country->save();
        return redirect('Admin/countries')->with('success','Status Updated Successfully!');
    }
}
?>