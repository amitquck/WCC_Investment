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
                <input type="text" class="form-control" name="code" id="code" placeholder="Enter Code" required="required" value="{{$associate->code}}">
                @if($errors->has('code'))
                  <p>{{ $errors->first('code') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="name">Associate Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{{$associate->name}}" required="required">
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="mobile">Mobile <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter Mobile" value="{{$associate->mobile}}" required="required">
                @if($errors->has('mobile'))
                  <p>{{ $errors->first('mobile') }}</p>
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{$associate->email}}">
                @if($errors->has('email'))
                  <p>{{ $errors->first('email') }}</p>
                @endif
              </div> 

              <div class="form-group col-md-4">
                <label for="sex">Sex</label><br>
                <input type="radio" name="sex" value="male" @if($associate->associatedetail->sex == 'male') {{'checked'}} @endif> Male
                <input type="radio" name="sex" value="female" @if($associate->associatedetail->sex == 'female') {{'checked'}} @endif> Female
                @if($errors->has('nominee_name'))
                  <p>{{ $errors->first('nominee_name') }}</p>
                @endif
              </div>  

              <div class="form-group col-md-4">
                <label for="dob">Date Of Birth</label>
                <input type="dob" class="form-control" name="dob" id="dob" placeholder="Enter DOB" value="{{$associate->associatedetail->dob}}">
                @if($errors->has('dob'))
                  <p>{{ $errors->first('dob') }}</p>
                @endif
              </div> 
          </div>
        </div>

   
        <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="father_husband_wife">Father/Husband</label>
                <input type="text" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father/Husband" value="{{$associate->associatedetail->father_husband_wife}}">
                @if($errors->has('father_husband_wife'))
                  <p>{{ $errors->first('father_husband_wife') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="mother_name">Mother's Name</label>
                <input type="text" class="form-control" name="mother_name" id="mother_name" placeholder="Enter Mother Name" value="{{$associate->associatedetail->mother_name}}">
                @if($errors->has('mother_name'))
                  <p>{{ $errors->first('mother_name') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="image">Your Photo</label>
                <input type="file" name="image" id="image" placeholder="Enter Image">
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
                <label for="address one">Address One</label>
                <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter Address One" value="{{$associate->associatedetail->address_one}}">
                @if($errors->has('address_one'))
                  <p>{{ $errors->first('address_one') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="address_two">Address Two</label>
                <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter Address Two " value="{{$associate->associatedetail->address_two}}">
                @if($errors->has('address_two'))
                  <p>{{ $errors->first('address_two') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="zipcode">Zipcode</label>
                <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter Zipcode" value="{{$associate->associatedetail->zipcode}}">
                @if($errors->has('zipcode'))
                  <p>{{ $errors->first('zipcode') }}</p>
                @endif
              </div>
             
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="country">Country</label>
               <select class="form-control country_id" name="country_id" id="country_id">
                  <option value="">Select Country</option>
                  @foreach($countries as $id=>$country)
                  <option value="{{$id}}">{{$country->name}}</option>
                  @endforeach
               </select>
                @if($errors->has('country_id'))
                  <p>{{ $errors->first('country_id') }}</p>
                @endif
              </div>  
               <div class="form-group col-md-4">
                <label for="state">State</label>
               <select class="form-control state_id" name="state_id" id="state_id">
                  <option value="">Select State</option>
               </select>
                @if($errors->has('state_id'))
                  <p>{{ $errors->first('state_id') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="city">City</label>
               <select class="form-control city_id" name="city_id" id="city_id">
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
                <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" value="{{$associate->associatedetail->account_holder_name}}">
                @if($errors->has('account_holder_name'))
                  <p>{{ $errors->first('account_holder_name') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="account_number">Account Number</label>
                <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number " value="{{$associate->associatedetail->account_number}}">
                @if($errors->has('account_number'))
                  <p>{{ $errors->first('account_number') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="bank_id">Bank Name</label>
                <!-- <select name="bank_id" class="form-control">
                  <option value="">Select</option>
                  @foreach($banks as $bank)
                    <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                  @endforeach
                </select> -->
                <input type="text" name="bank_name" class="form-control" value="{{$associate->associatedetail->bank_name}}" placeholder="Enter Bank">
                @if($errors->has('bank_id'))
                  <p>{{ $errors->first('bank_id') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="Branch">Branch</label>
                <input type="text" class="form-control" name="branch" id="branch" placeholder="Enter Branch" value="{{$associate->associatedetail->branch}}">
                @if($errors->has('branch'))
                  <p>{{ $errors->first('branch') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="ifsc_code">IFSC Code</label>
                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{$associate->associatedetail->ifsc_code}}">
                @if($errors->has('ifsc_code'))
                  <p>{{ $errors->first('ifsc_code') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="pan_no">Pan Number</label>
                <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter Pan Number" value="{{$associate->associatedetail->pan_no}}" >
                @if($errors->has('pan_no'))
                  <p>{{ $errors->first('pan_no') }}</p>
                @endif
              </div>
            </div>
               <button type="submit" class="btn btn-primary" style="margin-top:30px;">Update</button><br><br>
          </div>
  </div>
      </form>

@endsection
@section('page_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script>
   $('#country_id').change(function(){
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

   flatpickr('.datepicker-wrap', {
       allowInput: true,
       clickOpens: false,
       wrap: true,
     })
</script>
<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<!-- Plugins -->
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('plugins/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
@endsection
