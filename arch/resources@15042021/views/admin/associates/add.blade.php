@extends('layouts.admin.default')
@section('content')



  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add Associates</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
@include('flash')
      <!-- <div class="modal-header d-flex align-items-center bg-primary text-white">
      <h5 class="text-center">Add</h5>
    </div> -->
      <form action="{{route('admin.associate_store')}}" method="post" enctype="multipart/form-data">
  <div class="card card-style">
      {{ csrf_field()}}
  <div style="background-color:#167bea; color: white;">&nbsp; Personal Details</div><hr><br>
          <div class="col-md-12" style="padding-top:20px">
             
            <div class="row">
              <div class="form-group col-md-4">
                <label for="code">Associate Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" value="{{old('code')}}">
                @if($errors->has('code'))
                  <p style="color:red;">{{ $errors->first('code') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="name">Associate Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{old('name')}}">
                @if($errors->has('name'))
                  <p style="color:red;">{{ $errors->first('name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                 <label for="mobile">Mobile <span class="text-danger">*</span></label>
                 <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" value="{{ old('mobile') }}">
                 @if($errors->has('mobile'))
                 <p style="color:red;">{{ $errors->first('mobile') }}</p>
                 @endif
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="email">Email </label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{old('email')}}">
                @if($errors->has('email'))
                  <p style="color:red;">{{ $errors->first('email') }}</p>
                @endif
              </div> 
              
              <div class="form-group col-md-4">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" value="{{old('password')}}">
                @if($errors->has('password'))
                  <p style="color:red;">{{ $errors->first('password') }}</p>
                @endif
            </div>

             <div class="form-group col-md-4">
                <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password" value="{{old('password_confirmation')}}">
                @if($errors->has('password_confirmation'))
                  <p style="color:red;">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>
              
          </div>
        </div>

   
        <div class="col-md-12">
            <div class="row">
            <div class="form-group col-md-4">
                <label for="sex">Sex</label><br>
                <input type="radio" name="sex" value="male"> Male
                <input type="radio" name="sex" value="female"> Female
                @if($errors->has('nominee_name'))
                  <p>{{ $errors->first('nominee_name') }}</p>
                @endif
              </div>  

              <div class="form-group col-md-4">
                <label for="dob">DOB</label>
                <input type="text" class="form-control datepicker" name="dob" id="dob" placeholder="Enter DOB" value="{{old('dob')}}" >
                @if($errors->has('dob'))
                  <p>{{ $errors->first('dob') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="father_husband_wife">Father/Husband</label>
                <input type="text" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father/Husband" value="{{old('father_husband_wife')}}" >
                @if($errors->has('father_husband_wife'))
                  <p>{{ $errors->first('father_husband_wife') }}</p>
                @endif
              </div> 
              
            </div>
        </div>
              
          
        <div class="col-md-12">
            <div class="row">
            <div class="form-group col-md-4">
                <label for="mother_name">Mother's Name</label>
                <input type="text" class="form-control" name="mother_name" id="mother_name" placeholder="Enter Mother Name" value="{{old('mother_name')}}" >
                @if($errors->has('mother_name'))
                  <p>{{ $errors->first('mother_name') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="image">Your Photo</label>
                <input type="file" name="image" id="image" placeholder="Enter Image" >
                @if($errors->has('image'))
                  <p>{{ $errors->first('image') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="address one">Address One</label>
                <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter Address One" value="{{old('address_one')}}" >
                @if($errors->has('address_one'))
                  <p>{{ $errors->first('address_one') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="address_two">Address Two</label>
                <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter Address Two " value="{{old('address_two')}}" >
                @if($errors->has('address_two'))
                  <p>{{ $errors->first('address_two') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="zipcode">Zipcode</label>
                <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter Zipcode" value="{{old('zipcode')}}" >
                @if($errors->has('zipcode'))
                  <p>{{ $errors->first('zipcode') }}</p>
                @endif
              </div>
             <div class="form-group col-md-4">
                <label for="country">Country</label>
               <select class="form-control country_id" name="country_id" id="country_id" >
                  <option value="">Select Country</option>
                  @foreach($countries as $id=>$country)
                  <option value="{{$id}}">{{$country}}</option>
                  @endforeach
               </select>
                @if($errors->has('country_id'))
                  <p>{{ $errors->first('country_id') }}</p>
                @endif
              </div> 
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
               
               <div class="form-group col-md-4">
                <label for="state">State</label>
               <select class="form-control state_id" name="state_id" id="state_id" >
                  <option value="">Select State</option>
                  
               </select>
                @if($errors->has('state_id'))
                  <p>{{ $errors->first('state_id') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="city">City</label>
               <select class="form-control city_id" name="city_id" id="city_id" >
                  <option value="">Select City</option>
                  
               </select>
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
            <label for="account_holder_name">Account Holder Name</label>
            <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name"  value="{{old('account_holder_name')}}">
            @if($errors->has('account_holder_name'))
              <p>{{ $errors->first('account_holder_name') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="account_number">Account Number</label>
            <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number "  value="{{old('account_number')}}">
            @if($errors->has('account_number'))
              <p>{{ $errors->first('account_number') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="bank_name">Bank Name</label>
            <!-- <select name="bank_id" class="form-control">
              <option value="">Select</option>
              @foreach($banks as $bank)
                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
              @endforeach
            </select> -->
            <input type="text" name="bank_name" class="form-control" placeholder="Enter Bank Name" value="{{old('bank_name')}}">
            @if($errors->has('bank_id'))
              <p>{{ $errors->first('bank_id') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="Branch">Branch</label>
            <input type="text" class="form-control" name="branch" id="branch" placeholder="Enter Branch" value="{{old('branch')}}" >
            @if($errors->has('branch'))
              <p>{{ $errors->first('branch') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="ifsc_code">IFSC Code</label>
            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{old('ifsc_code')}}" >
            @if($errors->has('ifsc_code'))
              <p>{{ $errors->first('ifsc_code') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="pan_no">Pan Number</label>
            <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter Pan Number" value="{{old('pan_no')}}" >
            @if($errors->has('pan_no'))
              <p>{{ $errors->first('pan_no') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="payment_type">Payment Type <sup class="text-danger">*</sup></label>
            <select name="payment_type" class="form-control" required="required">
              <option value="">Select...</option>
              <option value="cash">Cash</option>
              <option value="accumulate">Cumilate</option>
              <option value="bank">Bank</option>
              <option value="hold">Hold</option>
              <option value="no_interest">No Interest</option>
            </select>
            @if($errors->has('payment_type'))
              <p>{{ $errors->first('payment_type') }}</p>
            @endif
          </div>  
        </div>
      </div>
  </div>
  <br>
<div class="card card-style">
  <div style="background-color:#167bea;color:white;">&nbsp;NOMINEE</div><hr><br>
  <div class="col-md-12">
    <div class="row">  
      <div class="form-group col-md-4">
        <label for="nominee_name">Nominee Name </label>
        <input type="nominee_name" class="form-control" name="nominee_name" id="nominee_name" placeholder="Enter Nominee Name">
        @if($errors->has('nominee_name'))
        <p>{{ $errors->first('nominee_name') }}</p>
        @endif
      </div>   
      <div class="col-sm-4">
        <label for="nominee_dob">Date Of Birth </label>
        <div class="input-group date">
          <input type="text" class="form-control datepicker"  id="nominee_dob" placeholder="Enter Date Of Birth" name="nominee_dob" value="">
          <div class="input-group-addon">
            <span class="glyphicon glyphicon-th"></span>
          </div>
          @if($errors->has('nominee_dob'))
          <p>{{ $errors->first('nominee_dob') }}</p>
          @endif
        </div>
      </div>                              
      <div class="form-group col-md-4">
        <label for="nominee_sex">Sex</label><br>
        <input type="radio" name="nominee_sex" value="male"> Male
        <input type="radio" name="nominee_sex" value="female"> Female
        @if($errors->has('nominee_sex'))
        <p>{{ $errors->first('nominee_sex') }}</p>
        @endif
      </div>  
    </div>
  </div>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="nominee_relation_with_applicable">Relationship With Applicant </label>
        <input type="text" class="form-control" name="nominee_relation_with_applicable" id="nominee_relation_with_applicable" placeholder="Enter Relationship With Applicant" value="{{ old('nominee_relation_with_applicable') }}">
        @if($errors->has('nominee_relation_with_applicable'))
        <p>{{ $errors->first('nominee_relation_with_applicable') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="address one">Address One </label>
        <input type="text" class="form-control" name="nominee_address_one" id="nominee_address one" placeholder="Enter address one" value="{{ old('nominee_address_one') }}">
        @if($errors->has('nominee_address_one'))
        <p>{{ $errors->first('nominee_address_one') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="nominee_address_two">Address Two </label>
        <input type="text" class="form-control" name="nominee_address_two" id="nominee_address_two" placeholder="Enter address two" value="{{ old('nominee_address_two') }}">
        @if($errors->has('nominee_address_two'))
        <p>{{ $errors->first('nominee_address_two') }}</p>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="nominee_zipcode">Zipcode </label>
        <input type="text" class="form-control n-get-csc" data-target-country="nominee_country" data-target-state="nominee_state" data-target-city="nominee_city" name="nominee_zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ old('nominee_zipcode') }}">
        @if($errors->has('nominee_zipcode'))
        <p>{{ $errors->first('nominee_zipcode') }}</p>
        @endif
      </div>
      <div class="form-group col-md-4">
        <label for="country">Country </label>
        <select class="form-control country_id" name="nominee_country_id" id="nominee_country_id">
          <option value="">Select Country</option>
          @foreach($countries as $id=>$country)
            <option value="{{$id}}">{{$country}}</option>
          @endforeach
        </select>
        @if($errors->has('nominee_country_id'))
        <p>{{ $errors->first('nominee_country_id') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="state">State </label>
          <select class="form-control state_id" name="nominee_state_id" id="nominee_state_id">
            <option value="">Select State</option>
          </select>
          @if($errors->has('nominee_state_id'))
          <p>{{ $errors->first('nominee_state_id') }}</p>
          @endif
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="row">
        <div class="form-group col-md-4">
          <label for="nominee_city">City </label>
          <select class="form-control city_id" name="nominee_city_id" id="nominee_city_id">
          <option value="">Select City</option>
          </select>
          @if($errors->has('nominee_city_id'))
          <p>{{ $errors->first('nominee_city_id') }}</p>
          @endif
        </div>
         <div class="form-group col-md-4">
           <label for="nominee_age">Age </label>
           <input type="text" class="form-control" name="nominee_age" id="nominee_age" placeholder="Enter age" value="{{ old('nominee_age') }}">
           @if($errors->has('nominee_age'))
           <p>{{ $errors->first('nominee_age') }}</p>
           @endif
         </div> 
    </div>
  </div>
</div>
  <div class="form-group col-md-4" style="margin-top:30px;">
     <button type="submit" class="btn btn-primary">Save</button>
  </div>

</form>

@endsection
@section('page_js')
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $('.datepicker').datepicker({
      startDate: '-3d',
      dateFormat: 'dd-mm-yy'
  });
   $('#country_id').change(function(){
    // alert('segfs');
     $('#state_id').html('<option>Select State </option>');
     var id = $('#country_id').val();
     $.ajax({
       url: "{{ route('admin.getCountryState') }}",
       type: 'POST',
       dataType:'json',
       data:{id:id,_token:'{!! csrf_token() !!}'},
       success: function(state)
       {
         // alert(state);
         $.each(state,function(key,value){  
           $('#state_id').append('<option value="'+key+'">'+value+'</option>');
         })
       },
       error: function(state)
       {
         alert('faild');
       }
   
   });
   }); 
   
   $('#state_id').change(function(){
     $('#city_id').html('<option>Select City </option>');
     var state_id = $('#state_id').val();
     $.ajax({
       url: "{{ route('admin.getStateCity') }}",
       type: 'POST',
       dataType:'json',
       data:{state_id:state_id,_token:'{!! csrf_token() !!}'},
       success: function(city)
       {
         // alert(state);
         $.each(city,function(key,value){  
           $('#city_id').append('<option value="'+key+'">'+value+'</option>');
         })
       },
       error: function(state)
       {
         alert('faild');
       }
   
   });
   });


    $('#nominee_country_id').change(function(){
      // alert('sg');
     $('#nominee_state_id').html('<option>Select State </option>');
     var id = $('#nominee_country_id').val();
     $.ajax({
       url: "{{ route('admin.getCountryState') }}",
       type: 'POST',
       dataType:'json',
       data:{id:id,_token:'{!! csrf_token() !!}'},
       success: function(state)
       {
         // alert(state);
         $.each(state,function(key,value){  
           $('#nominee_state_id').append('<option value="'+key+'">'+value+'</option>');
         })
       },
       error: function(state)
       {
         alert('faild');
       }
   
   });
   }); 
   
   $('#nominee_state_id').change(function(){
     $('#nominee_city_id').html('<option>Select City </option>');
     var state_id = $('#nominee_state_id').val();
     $.ajax({
       url: "{{ route('admin.getStateCity') }}",
       type: 'POST',
       dataType:'json',
       data:{state_id:state_id,_token:'{!! csrf_token() !!}'},
       success: function(city)
       {
         // alert(state);
         $.each(city,function(key,value){  
           $('#nominee_city_id').append('<option value="'+key+'">'+value+'</option>');
         })
       },
       error: function(state)
       {
         alert('faild');
       }
   
   });
   });
    // $.fn.datepicker.defaults.format = "dd/mm/yyyy";
</script>
@endsection
<?php /*
<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<!-- Plugins -->
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('plugins/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
*/ ?>