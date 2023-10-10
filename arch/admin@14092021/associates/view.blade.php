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
                <label for="email">Email </label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="{{$associate->email}}" disabled="disabled">
                @if($errors->has('email'))
                  <p>{{ $errors->first('email') }}</p>
                @endif
              </div> 

               

              <div class="form-group col-md-4">
                <label for="dob">Date Of Birth</label>
                <input type="dob" class="form-control" name="dob" id="dob" placeholder="Enter DOB" value="<?php echo (isset($associate->associatedetail->dob) && $associate->associatedetail->dob != NUll)?date('d-m-Y',strtotime($associate->associatedetail->dob)):'' ?>" disabled="disabled">
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
                <input type="text" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father/Husband" value="{{$associate->associatedetail?$associate->associatedetail->father_husband_wife:''}}" disabled="disabled">
                @if($errors->has('father_husband_wife'))
                  <p>{{ $errors->first('father_husband_wife') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="mother_name">Mother's Name</label>
                <input type="text" class="form-control" name="mother_name" id="mother_name" placeholder="Enter Mother Name" value="{{$associate->associatedetail?$associate->associatedetail->mother_name:''}}" disabled="disabled">
                @if($errors->has('mother_name'))
                  <p>{{ $errors->first('mother_name') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="image">Your Photo</label>
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
                <label for="address one">Address One </label>
                <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter Address One" value="{{$associate->associatedetail?$associate->associatedetail->address_one:''}}" disabled="disabled">
                @if($errors->has('address_one'))
                  <p>{{ $errors->first('address_one') }}</p>
                @endif
              </div> 
              <div class="form-group col-md-4">
                <label for="address_two">Address Two </label>
                <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter Address Two " value="{{$associate->associatedetail?$associate->associatedetail->address_two:''}}" disabled="disabled">
                @if($errors->has('address_two'))
                  <p>{{ $errors->first('address_two') }}</p>
                @endif
              </div>
              <div class="form-group col-md-4">
                <label for="zipcode">Zipcode </label>
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
                <label for="country">Country </label>
                <select class="form-control country_id" name="country_id" id="country_id" disabled="">
                  <option value="">Select Country</option>
                  @foreach($countries as $id=>$country)
                  <option value="{{$id}}"@if($id) selected="selected"@endif>{{$country}}</option>
                  @endforeach
               </select>
              </div>  
               <div class="form-group col-md-4">
                <label for="state">State </label>
                <select class="form-control state_id" name="state_id" id="state_id" disabled="">
                  <option value="">Select State</option>
                  @foreach($states as $id => $state)
                    <option value="{{$state->id}}" @if($associate->associatedetail->state_id == $state->id) {{'selected'}} @endif >{{$state->name}}</option>
                  @endforeach
               </select>
              </div>
              <div class="form-group col-md-4">
                <label for="city">City </label>
                <select class="form-control city_id" name="city_id" id="city_id" disabled="">
                  <option value="">Select City</option>
                   @foreach($cities as $id => $city)
                      <option value="{{$city->id}}" @if($associate->associatedetail->city_id == $city->id) {{'selected'}} @endif >{{$city->name}}</option>
                    @endforeach
               </select>
              </div>
            </div>
          </div>
        </div><br>
        

  <div class="card card-style">
    <div style="background-color:#167bea;color:white;">&nbsp;Bank Details</div><hr><br>
      <div class="col-md-12" style="padding-top:20px">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="account_holder_name">Account Holder Name </label>
            <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" disabled="disabled" value="{{$associate->associatedetail?$associate->associatedetail->account_holder_name:''}}">
            @if($errors->has('account_holder_name'))
              <p>{{ $errors->first('account_holder_name') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="account_number">Account Number </label>
            <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number " disabled="disabled" value="{{$associate->associatedetail?$associate->associatedetail->account_number:''}}">
            @if($errors->has('account_number'))
              <p>{{ $errors->first('account_number') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="bank_name">Bank Name </label>
            <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name" value="{{$associate->associatedetail?$associate->associatedetail->bank_name:''}}" disabled="disabled">
            @if($errors->has('bank_name'))
              <p>{{ $errors->first('bank_name') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="Branch">Branch </label>
            <input type="text" class="form-control" name="branch" id="branch" placeholder="Enter Branch" value="{{$associate->associatedetail?$associate->associatedetail->branch:''}}" disabled="disabled">
            @if($errors->has('branch'))
              <p>{{ $errors->first('branch') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="ifsc_code">IFSC Code </label>
            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{$associate->associatedetail?$associate->associatedetail->ifsc_code:''}}" disabled="disabled">
            @if($errors->has('ifsc_code'))
              <p>{{ $errors->first('ifsc_code') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="pan_no">Pan Number</label>
            <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter Pan Number" value="{{$associate->associatedetail->pan_no}}" disabled="">
            @if($errors->has('pan_no'))
              <p>{{ $errors->first('pan_no') }}</p>
            @endif
          </div>
          <div class="form-group col-md-4">
            <label for="payment_type">Payment Type <sup class="text-danger">*</sup></label>
            <select name="payment_type" class="form-control" disabled="">
              <option value="">Select...</option>
              <option value="cash" @if($associate->associatedetail->payment_type == 'cash') selected="selected" @endif>Cash</option>
              <option value="accumulate" @if($associate->associatedetail->payment_type == 'accumulate') selected="selected" @endif>Accumulate</option>
              <option value="bank" @if($associate->associatedetail->payment_type== 'bank') selected="selected" @endif>Bank</option>
              <option value="hold" @if($associate->associatedetail->payment_type== 'hold') selected="selected" @endif>Hold</option>
              <option value="no_interest" @if($associate->associatedetail->payment_type== 'no_interest') selected="selected" @endif>No Interest</option>
            </select>
            @if($errors->has('payment_type'))
            <p>{{ $errors->first('payment_type') }}</p>
            @endif
          </div>
        </div>
      </div>
  </div>
  
<div class="card card-style">
  <div style="background-color:#167bea;color:white;">&nbsp;NOMINEE</div><hr><br>
  <div class="col-md-12">
    <div class="row">  
      <div class="form-group col-md-4">
        <label for="nominee_name">Nominee Name </label>
        <input type="nominee_name" class="form-control" name="nominee_name" id="nominee_name" placeholder="Enter Nominee Name"  value="{{$associate->associatedetail->nominee_name}}" disabled="disabled">
        @if($errors->has('nominee_name'))
        <p>{{ $errors->first('nominee_name') }}</p>
        @endif
      </div>   
      <div class="col-sm-4">
        <label for="nominee_dob">Date Of Birth </label>
        <div class="input-group date" data-provide="datepicker">
          <input type="text" class="form-control"  id="nominee_dob" placeholder="Enter Date Of Birth" name="nominee_dob" value="<?php echo ($associate->associatedetail->nominee_dob) && $associate->associatedetail->nominee_dob != NUll ?date('d-m-Y',strtotime($associate->associatedetail->nominee_dob)):'' ?>" disabled="disabled">
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
        <input type="radio" name="nominee_sex" value="male"@if($associate->associatedetail->nominee_sex == 'male') checked="checked" @endif disabled="disabled"> Male
        <input type="radio" name="nominee_sex" value="female"@if($associate->associatedetail->nominee_sex == 'female') checked="checked" @endif disabled="disabled"> Female
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
        <input type="text" class="form-control" name="nominee_relation_with_applicable" id="nominee_relation_with_applicable" placeholder="Enter Relationship With Applicant" value="{{$associate->associatedetail->nominee_relation_with_applicable}}" disabled="disabled">
        @if($errors->has('nominee_relation_with_applicable'))
        <p>{{ $errors->first('nominee_relation_with_applicable') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="address one">Address One </label>
        <input type="text" class="form-control" name="nominee_address_one" id="nominee_address one" placeholder="Enter address one" value="{{$associate->associatedetail->nominee_address_one}}" disabled="disabled">
        @if($errors->has('nominee_address_one'))
        <p>{{ $errors->first('nominee_address_one') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="nominee_address_two">Address Two </label>
        <input type="text" class="form-control" name="nominee_address_two" id="nominee_address_two" placeholder="Enter address two" value="{{$associate->associatedetail->nominee_address_two}}" disabled="disabled">
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
        <input type="text" class="form-control n-get-csc" data-target-country="nominee_country" data-target-state="nominee_state" data-target-city="nominee_city" name="nominee_zipcode" id="zipcode" placeholder="Enter zipcode" value="{{$associate->associatedetail->nominee_zipcode}}" disabled="disabled">
        @if($errors->has('nominee_zipcode'))
        <p>{{ $errors->first('nominee_zipcode') }}</p>
        @endif
      </div>
      <div class="form-group col-md-4">
        <label for="country">Country </label>
        <select class="form-control country_id" name="nominee_country_id" id="nominee_country_id" disabled="disabled">
          <option value="">Select Country</option>
          @foreach($countries as $id=>$country)
            <option value="{{$id}}"@if($id) selected="selected"@endif>{{$country}}</option>
          @endforeach
        </select>
        @if($errors->has('nominee_country_id'))
        <p>{{ $errors->first('nominee_country_id') }}</p>
        @endif
      </div> 
      <div class="form-group col-md-4">
        <label for="state">State </label>
          <select class="form-control state_id" name="nominee_state_id" id="nominee_state_id" disabled="disabled">
            <option value="">Select State</option>
            @foreach($states as $id => $state)
              <option value="{{$state->id}}" @if($associate->associatedetail->nominee_state_id == $state->id) {{'selected'}} @endif >{{$state->name}}</option>
            @endforeach
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
          <select class="form-control city_id" name="nominee_city_id" id="nominee_city_id" disabled="disabled">
          <option value="">Select City</option>
          @foreach($cities as $id => $city)
            <option value="{{$city->id}}" @if($associate->associatedetail->nominee_city_id == $city->id) {{'selected'}} @endif >{{$city->name}}</option>
          @endforeach
          </select>
          @if($errors->has('nominee_city_id'))
          <p>{{ $errors->first('nominee_city_id') }}</p>
          @endif
        </div>
         <div class="form-group col-md-4">
           <label for="nominee_age">Age </label>
           <input type="text" class="form-control" name="nominee_age" id="nominee_age" placeholder="Enter age" value="{{$associate->associatedetail->nominee_age}}" disabled="disabled">
           @if($errors->has('nominee_age'))
           <p>{{ $errors->first('nominee_age') }}</p>
           @endif
         </div> 
          <div class="form-group col-md-4">
                 <label for="nominee_image">Your Photo</label>
                <!--  <input type="file" name="nominee_image" id="nominee_image" placeholder="Enter Image">
                 @if($errors->has('nominee_image'))
                 <p>{{ $errors->first('nominee_image') }}</p>
                 @endif -->
                 <br>           
                <img src="{{asset('images/associate/'.$associate->associatedetail->nominee_image)}}" width="50px" height="50px"></div>
    </div>
  </div>
</div>
</form><br>
<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_associate" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.associateDelete')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Delete Associate</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="delete_associate-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-xs">Delete</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!------------------------------------------------------------------->

<div class="col-md-8">
   <div class="row">
    @if(empCan('edit_associate') || Auth::user()->login_type == 'superadmin') 
      <div class="col-md-1">
         <a href="{{url('admin/associate_edit/'.$associate->id)}}" class="btn btn-primary btn-md">Edit</a>
      </div>
    @endif
    @if(empCan('delete_associate') || Auth::user()->login_type == 'superadmin') 
      <div class="col-md-1">
         <a class="btn btn-danger delete_confirm text-light" data-id="{{$associate->id}}">Delete</a>
      </div>
    @endif
   </div>       
</div><br><br>
@endsection
@section('page_js')
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script>       
$('.delete_confirm').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.associate_delete_confirm")}}',
    type:'post',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_associate-container').html(data);
        $('#delete_associate').modal('show');
      
    }
  });
});
</script>
@endsection