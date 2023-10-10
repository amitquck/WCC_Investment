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

// use App\Model\CustomerService;
class ResetPasswordController extends Controller
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

    public function updatePassword(Request $request){
        $this->validate($request,[
            'password' => 'required|confirmed' 
        ]);
        $user = User::whereId(\Auth::user()->id)->first();
            // dd($user);
        $user->password = Hash::make($request->password);
        $user->updated_by = $user->id;
        $user->save();
        return redirect('admin/dashboard')->with('success','Password Change Successfully!');

    }

    

}
