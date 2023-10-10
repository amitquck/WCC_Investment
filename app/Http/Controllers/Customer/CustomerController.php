<?php

use App\Http\Controllers\Customer\CustomerController;

use Illuminate\Http\Request;
// use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use validator;
use Auth;
use App\Model\Faq;
use App\User;
// use App\CustomerServiceForm;
class CustomerController extends Controller
{
    public function __construct() {
        // $this->middleware('auth:customerlogin');
    }


    public function dashboard(){
        return view('dashboard');
    }





    public function forgotPass()
    {
        return view('resetPassword');
    }

    public function verifyEmail(Request $request)
    {
        $email = $request->email;
        return view('renewalPassword',compact('email'));
    }

    public function renewalPassword(Request $request)
    {
        $user = User::whereEmail($request->email)->whereStatus(1)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect('loginCustomer')->with('success','Password Changed Successfully!');
    }

    public function customerLogout(){
        // dd(Auth::user());
        if(\Auth::user()->login_type == 'superadmin'){
            Auth::logout();//guard('customerlogin')->
            return redirect(url("/"));
        }elseif(\Auth::user()->login_type == 'customer'){
            Auth::logout();//guard('customerlogin')->
            return redirect(url("/"));
        }elseif(\Auth::user()->login_type == 'employee'){
            Auth::logout();//guard('customerlogin')->
            return redirect(url("/"));
        }elseif(\Auth::user()->login_type == 'associate'){
            Auth::logout();//guard('customerlogin')->
            return redirect(url("/"));
        }
    }
}
