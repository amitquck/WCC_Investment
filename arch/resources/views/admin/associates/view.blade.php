@extends('layouts.admin.default')
@section('content')



  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associates List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
@include('flash')
      <!-- <div class="modal-header d-flex align-items-center bg-primary text-white">
      <h5 class="text-center">Add</h5>
    </div> -->
      <form action="{{url('admin/associate_update/'.$associate->id)}}" method="post" enctype="multipart/form-data">
  <div class="card card-style">
      {{ csrf_field()}}
  <div style="background-color:#167bea; color: white;">&nbsp; Personal Details</div><hr><br>
          <div class="col-md-12" style="padding-top:20px">
             
            <div class="row">
              <div class="form-group col-md-4">
                <label for="code">Associate Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" disabled="disabled" value="{{$associate->code}}">
                @if($errors->has('code'))
                  <p>{{ $errors->first('code') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="name">Associate Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{$associate->name}}" disabled="disabled">
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="mobile">Mobile <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile" value="{{$associate->mobile}}" disabled="disabled">
                @if($errors->has('mobile'))
                  <p>{{ $errors->first('mobile') }}</p>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{$associate->email}}" disabled="disabled">
                @if($errors->has('email'))
                  <p>{{ $errors->first('email') }}</p>
                @endif
              </div> 

               

              <div class="form-group col-md-4">
                <label for="dob">Date Of Birth<span class="text-danger">*</span></label>
                <input type="dob" class="form-control" name="dob" id="dob" placeholder="Enter DOB" value="{{$associate->associatedetail?$associate->associatedetail->dob:''}}" disabled="disabled">
                @if($errors->has('dob'))
                  <p>{{ $errors->first('dob') }}</p>
                @endif
              </div> 
          </div>
        </div>

   
        <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="father_husband_wife">Father/Husband<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father/Husband" value="{{$associate->associatedetail?$associate->associatedetail->father_husband_wife:''}}" disabled="disabled">
                @if($errors->has('father_husband_wife'))
                  <p>{{ $errors->first('father_husband_wife') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="mother_name">Mother's Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="mother_name" id="mother_name" placeholder="Enter Mother Name" value="{{$associate->associatedetail?$associate->associatedetail->mother_name:''}}" disabled="disabled">
                @if($errors->has('mother_name'))
                  <p>{{ $errors->first('mother_name') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="image">Your Photo<span class="text-danger">*</span></label>
                <br>
                  <img src="{{asset('images/associate/'.$associate->associatedetail->image)}}" width="50px" height="50px">
                @if($errors->has('image'))
                  <p>{{ $errors->first('image') }}</p>
                @endif
              </div> 
            </div>
        </div>
              
          
        <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="address one">Address One <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter Address One" value="{{$associate->associatedetail?$associate->associatedetail->address_one:''}}" disabled="disabled">
                @if($errors->has('address_one'))
                  <p>{{ $errors->first('address_one') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="address_two">Address Two <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter Address Two " value="{{$associate->associatedetail?$associate->associatedetail->address_two:''}}" disabled="disabled">
                @if($errors->has('address_two'))
                  <p>{{ $errors->first('address_two') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="zipcode">Zipcode <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter Zipcode" value="{{$associate->associatedetail?$associate->associatedetail->zipcode:''}}" disabled="disabled">
                @if($errors->has('zipcode'))
                  <p>{{ $errors->first('zipcode') }}</p>
                @endif
              </div>
             
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="country">Country <span class="text-danger">*</span></label>
                <input type="text" name="country_id" class="form-control" value="{{$associate->associatedetail?$associate->associatedetail->country_id:''}}" disabled="disabled">
                @if($errors->has('country_id'))
                  <p>{{ $errors->first('country_id') }}</p>
                @endif
              </div>  
               <div class="form-group col-md-4">
                <label for="state">State <span class="text-danger">*</span></label>
                <input type="text" name="state_id" class="form-control" value="{{$associate->associatedetail?$associate->associatedetail->state_id:''}}" disabled="disabled">
                @if($errors->has('state_id'))
                  <p>{{ $errors->first('state_id') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="city">City <span class="text-danger">*</span></label>
                <input type="text" name="city_id" class="form-control" value="{{$associate->associatedetail?$associate->associatedetail->city_id:''}}" disabled="disabled">
                @if($errors->has('city_id'))
                  <p>{{ $errors->first('city_id') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div><br>
        

  <div class="card card-style">
            <div style="background-color:#167bea;color:white;">&nbsp;Bank Details</div><hr><br>
          <div class="col-md-12" style="padding-top:20px">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="account_holder_name">Account Holder Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" disabled="disabled" value="{{$associate->associatedetail?$associate->associatedetail->account_holder_name:''}}">
                @if($errors->has('account_holder_name'))
                  <p>{{ $errors->first('account_holder_name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="account_number">Account Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number " disabled="disabled" value="{{$associate->associatedetail?$associate->associatedetail->account_number:''}}">
                @if($errors->has('account_number'))
                  <p>{{ $errors->first('account_number') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name" value="{{$associate->associatedetail?$associate->associatedetail->bank_name:''}}" disabled="disabled">
                @if($errors->has('bank_name'))
                  <p>{{ $errors->first('bank_name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="Branch">Branch <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="branch" id="branch" placeholder="Enter Branch" value="{{$associate->associatedetail?$associate->associatedetail->branch:''}}" disabled="disabled">
                @if($errors->has('branch'))
                  <p>{{ $errors->first('branch') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{$associate->associatedetail?$associate->associatedetail->ifsc_code:''}}" disabled="disabled">
                @if($errors->has('ifsc_code'))
                  <p>{{ $errors->first('ifsc_code') }}</p>
                @endif
              </div>
            </div>
          </div>
  </div>
      </form>

@endsection