@extends('layouts.admin.default')
@section('content')
<div class="main-body">
      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
          <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{Auth::user()->name}} Profile</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card card-style-1">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
             
                <div class="mt-3">
                  <h6 class="text-center">{{Auth::user()->code}}</h6>
                  <h4>{{Auth::user()->name}}</h4>
                  <p class="text-secondary mb-1"></p>
                  
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
                  <h6 class="mb-0">Employee Code</h6>
                </div>
                <div class="col-sm-9 text-secondary">
                  {{Auth::user()->code}}
                </div>
              </div>
              <hr>
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
              

            </div>
    </div>
  </div>

  
</div>
          </div>
        </div>
      </div>

    </div>
@endsection