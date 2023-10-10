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
                  <p class="text-secondary mb-1">Date of Birth  @if(Auth::user()->associatedetail->dob) {{date('d-m-Y',strtotime(Auth::user()->associatedetail->dob))  }} @else N/A @endif </p>
                  <p class="text-muted font-size-sm">{{ Auth::user()->associatedetail->address_one }}</p>
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
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Full Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  {{Auth::user()->name}}
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
                {{Auth::user()->associatedetail->father_husband_wife}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Mother's Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                {{Auth::user()->associatedetail->mother_name}}
                </div>
              </div>
              <hr>
              
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Address</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                   {{Auth::user()->associatedetail->address_one}}@isset(Auth::user()->associatedetail->address_two), @endisset {{Auth::user()->associatedetail->address_two}}@isset(Auth::user()->associatedetail->city_id), @endisset {{Auth::user()->associatedetail->city_id}}@isset(Auth::user()->associatedetail->state_id), @endisset {{Auth::user()->associatedetail->state_id}}@isset(Auth::user()->associatedetail->country_id), @endisset {{Auth::user()->associatedetail->country_id}}@isset(Auth::user()->associatedetail->zipcode), @endisset {{Auth::user()->associatedetail->zipcode}}
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
                {{Auth::user()->associatedetail->account_holder_name}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Bank Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                {{Auth::user()->associatedetail->bank_name}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Branch Name</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                {{Auth::user()->associatedetail->branch}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">Account Number</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                {{Auth::user()->associatedetail->account_number}}
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <h6 class="mb-0">IFSC Code</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                {{Auth::user()->associatedetail->ifsc_code}}
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

    </div>
@endsection