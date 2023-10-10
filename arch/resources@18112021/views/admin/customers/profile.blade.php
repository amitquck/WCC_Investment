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
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                {{ ucwords(Auth::user()->name)}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                {{Auth::user()->email}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Mobile</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                {{Auth::user()->mobile}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Father/Husband/Wife</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                {{Auth::user()->customerdetails->father_husband_wife}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                <h6 class="mb-0">Address</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                {{Auth::user()->customerdetails->address_one}}@isset(Auth::user()->customerdetails->address_two), @endisset {{Auth::user()->customerdetails->address_two}}@isset(Auth::user()->customerdetails->city_id), @endisset {{Auth::user()->customerdetails->citycity->name}}@isset(Auth::user()->customerdetails->state_id), @endisset {{Auth::user()->customerdetails->statestate->name}}@isset(Auth::user()->customerdetails->country_id), @endisset {{Auth::user()->customerdetails->countrycountry->name}}@isset(Auth::user()->customerdetails->zipcode), @endisset {{Auth::user()->customerdetails->zipcode}}
                                </div>
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
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Account Holder Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{Auth::user()->customerdetails->account_holder_name}}
                                </div>
                            </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Bank Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->bank_name}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Account Number</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->account_number}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">IFSC Code</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->ifsc_code}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Pan Number</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->pan_no}}
                                    </div>
                                </div>
                                <hr>
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
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nominee Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <div class="row">
                                        <div class="col-md-5">
                                            {{ ucwords(Auth::user()->customerdetails->nominee_name)}} &nbsp;&nbsp;&nbsp;
                                        </div>
                                        <div class="col-md-4">
                                            <span><img src="{{ asset('images/customer/'.Auth::user()->customerdetails->image) }}" alt="" class="rounded-circle" width="100"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Date Of Birth</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_dob}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Age</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_age}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Gender</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_sex}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Relationship With Applicant</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_relation_with_applicable}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address One</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_address_one}}
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Address Two</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        {{Auth::user()->customerdetails->nominee_address_two}},
                                        @isset(Auth::user()->customerdetails->nominee_city_id)
                                            {{Auth::user()->customerdetails->city->name}},
                                        @endisset
                                        @isset(Auth::user()->customerdetails->nominee_state_id)
                                            {{Auth::user()->customerdetails->state->name}},
                                        @endisset
                                        @isset(Auth::user()->customerdetails->nominee_country_id)
                                            {{Auth::user()->customerdetails->country->name}},
                                        @endisset
                                        @isset(Auth::user()->customerdetails->nominee_zipcode)
                                            {{Auth::user()->customerdetails->nominee_zipcode}}
                                        @endisset
                                    </div>
                                </div>
                                <hr>

                        </div>
                    </div>

                </div>







            </div>
        </div>
    </div>

</div>
@endsection
