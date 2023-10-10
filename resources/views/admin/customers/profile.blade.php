@extends('layouts.admin.default')
@section('content')
<div class="main-body">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
          <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Customer Profile</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card card-style-1">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="{{ asset('images/customer/'.Auth::user()->customerdetails->image) }}" alt="" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4>{{ucwords(Auth::user()->name)}}</h4>
                  <h6>({{ Auth::user()->code }})</h6>
                  <p class="text-secondary mb-1">Date of Birth @if(Auth::user()->customerdetails->dob != NULL) {{date('d-m-Y',strtotime(Auth::user()->customerdetails->dob))  }} @else  @endif</p>
                  <p class="text-muted font-size-sm"><a href="{{ url('customer/Customer-change-password') }}">Change Password</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card card-style-1 mb-3">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr><th>Full Name</th><td>{{ ucwords(Auth::user()->name)}}</td></tr>
                                    <tr><th>Email</th><td>{{ ucwords(Auth::user()->email)}}</td></tr>
                                    <tr><th>Mobile</th><td>{{ ucwords(Auth::user()->mobile)}}</td></tr>
                                    <tr><th>Father/Husband/Wife</th><td>{{ ucwords(Auth::user()->customerdetails->father_husband_wife)}}</td></tr>
                                    <tr><th>Gender</th><td>{{ ucwords(Auth::user()->customerdetails->sex)}}</td></tr>
                                    <tr><th>Age</th><td>{{ ucwords(Auth::user()->customerdetails->age)}}</td></tr>
                                    <tr><th>Nationality</th><td>{{ ucwords(Auth::user()->customerdetails->nationality)}}</td></tr>
                                    <tr><th>Address</th>
                                        <td>{{Auth::user()->customerdetails->address_one}}
                                            @isset(Auth::user()->customerdetails->address_two), @endisset
                                            {{Auth::user()->customerdetails->address_two}}
                                            @if(Auth::user()->customerdetails->city_id != NULL)
                                                {{Auth::user()->customerdetails->citycity->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->state_id != NULL)
                                                {{Auth::user()->customerdetails->statestate->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->country_id != NULL)
                                                {{Auth::user()->customerdetails->countrycountry->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->zipcode != NULL)
                                                {{Auth::user()->customerdetails->zipcode}},
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Account Details
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    @foreach(auth()->user()->customerBankDetail as $key => $multibankdetail)
                                        <tr><th class="text-center">Bank Detail {{ $key+1 }}</th></tr>
                                        <tr><th>Account Holder Name</th><td>{{ ucwords($multibankdetail->account_holder_name)}}</td></tr>
                                        <tr><th>Bank Name</th><td>{{ ucwords($multibankdetail->bank_name)}}</td></tr>
                                        <tr><th>Account Number</th><td>{{ ucwords($multibankdetail->account_number)}}</td></tr>
                                        <tr><th>IFSC Code</th><td>{{ ucwords($multibankdetail->ifsc_code)}}</td></tr>
                                        <tr><th>Pan Number</th><td>{{$multibankdetail->pan_no}}</td></tr>
                                    @endforeach
                                </table>
                            </div>

                        </div>
                    </div>


                    <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Nominee Details
                            </button>
                        </h2>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr><th>Nominee Name</th><td>{{ ucwords(Auth::user()->customerdetails->nominee_name)}}</td><td><img src="{{ asset('images/customer/'.Auth::user()->customerdetails->image) }}" alt="" class="rounded-circle" width="100"></td></tr>
                                    <tr><th>Date Of Birth</th><td>{{Auth::user()->customerdetails->nominee_dob}}</td></tr>
                                    <tr><th>Age</th><td>{{Auth::user()->customerdetails->nominee_age}}</td></tr>
                                    <tr><th>Gender</th><td>{{Auth::user()->customerdetails->nominee_sex}}</td></tr>
                                    <tr><th>Relationship With Applicant</th><td>{{Auth::user()->customerdetails->nominee_relation_with_applicable}}</td></tr>
                                    <tr><th>Address One</th><td>{{Auth::user()->customerdetails->nominee_address_one}}</td></tr>
                                    <tr>
                                        <th>Address Two</th>
                                        <td>{{Auth::user()->customerdetails->nominee_address_two}},
                                            @if(Auth::user()->customerdetails->nominee_city_id != NULL)
                                                {{Auth::user()->customerdetails->city->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->nominee_state_id != NULL)
                                                {{Auth::user()->customerdetails->state->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->nominee_country_id != NULL)
                                                {{Auth::user()->customerdetails->country->name}},
                                            @endif
                                            @if(Auth::user()->customerdetails->nominee_zipcode != NULL)
                                                {{Auth::user()->customerdetails->nominee_zipcode}}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
