@extends('layouts.admin.default')
@section('content')
<div class="main-body">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
          <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Associate Profile</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

    <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card card-style-1">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">

                <img src="{{ asset('images/associate/'.Auth::user()->associatedetail->image) }}" alt="" class="rounded-circle" width="150">
                <div class="mt-3">
                  <h4>{{Auth::user()->name}}</h4>
                  <h6>({{ Auth::user()->code }})</h6>
                  <p class="text-secondary mb-1">Date of Birth  @if(Auth::user()->associatedetail->dob) {{date('d-m-Y',strtotime(Auth::user()->associatedetail->dob))  }} @else N/A @endif </p>
                  <p class="text-muted font-size-sm"><a href="{{ url('associate/associate-change-password') }}">Change Password</a></p>
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
                                    <tr><th>Associate Code</th><td>{{Auth::user()->code}}</td></tr>
                                    <tr><th>Full Name</th><td>{{Auth::user()->name}}</td></tr>
                                    <tr><th>Email</th><td>{{Auth::user()->email}}</td></tr>
                                    <tr><th>Mobile</th><td>{{Auth::user()->mobile}}</td></tr>
                                    <tr><th>Father/Husband/Wife</th><td>{{Auth::user()->associatedetail->father_husband_wife}}</td></tr>
                                    <tr><th>Mother's Name</th><td> {{Auth::user()->associatedetail->mother_name}}</td></tr>
                                    <tr><th>Address</th><td>{{Auth::user()->associatedetail->address_one}}@isset(Auth::user()->associatedetail->address_two), @endisset {{Auth::user()->associatedetail->address_two}}@isset(Auth::user()->associatedetail->city_id), @endisset {{Auth::user()->associatedetail->city_id}}@isset(Auth::user()->associatedetail->state_id), @endisset {{Auth::user()->associatedetail->state_id}}@isset(Auth::user()->associatedetail->country_id), @endisset {{Auth::user()->associatedetail->country_id}}@isset(Auth::user()->associatedetail->zipcode), @endisset {{Auth::user()->associatedetail->zipcode}}</td></tr>
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
                                    <tr><th>Account Holder Name</th><td>{{Auth::user()->associatedetail->account_holder_name}}</td></tr>
                                    <tr><th>Bank Name</th><td>{{Auth::user()->associatedetail->bank_name}}</td></tr>
                                    <tr><th>Branch Name</th><td>{{Auth::user()->associatedetail->branch}}</td></tr>
                                    <tr><th>Account Number</th><td>{{Auth::user()->associatedetail->account_number}}</td></tr>
                                    <tr><th>IFSC Code</th><td>{{Auth::user()->associatedetail->ifsc_code}}</td></tr>
                                    <tr><th>Pan Number</th><td>{{Auth::user()->associatedetail->pan_no}}</td></tr>
                                </table>
                            </div>
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
                                <tr><th>Nominee Name</th><td>{{ ucwords(Auth::user()->associatedetail->nominee_name)}}</td><td><img src="{{ asset('images/associate/'.Auth::user()->associatedetail->image) }}" alt="" class="rounded-circle" width="100"></td></tr>
                                <tr><th>Date Of Birth</th><td>{{Auth::user()->associatedetail->nominee_dob}}</td></tr>
                                <tr><th>Age</th><td>{{Auth::user()->associatedetail->nominee_age}}</td></tr>
                                <tr><th>Gender</th><td>{{Auth::user()->associatedetail->nominee_sex}}</td></tr>
                                <tr><th>Relationship With Applicant</th><td>{{Auth::user()->associatedetail->nominee_relation_with_applicable}}</td></tr>
                                <tr><th>Address One</th><td>{{Auth::user()->associatedetail->nominee_address_one}}</td></tr>
                                <tr><th>Address Two</th><td>{{Auth::user()->associatedetail->nominee_address_two}},
                                    @isset(Auth::user()->associatedetail->nominee_city_id)
                                        {{Auth::user()->associatedetail->city->name}},
                                    @endisset
                                    @isset(Auth::user()->associatedetail->nominee_state_id)
                                        {{Auth::user()->associatedetail->state->name}},
                                    @endisset
                                    @isset(Auth::user()->associatedetail->nominee_country_id)
                                        {{Auth::user()->associatedetail->country->name}},
                                    @endisset
                                    @isset(Auth::user()->associatedetail->nominee_zipcode)
                                        {{Auth::user()->associatedetail->nominee_zipcode}}
                                    @endisset</td></tr>
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
