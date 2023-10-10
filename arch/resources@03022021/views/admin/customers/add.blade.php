

@extends('layouts.admin.default')
@section('content')
<!-- Breadcrumb -->
<script>
var _checkout_config = {
          "get_csc_url": "{{ url('/getcsc') }}",
      };
</script>
<script src="{{ asset('js/getzip.js') }}" crossorigin="anonymous"></script>
<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add Customer</li>
   </ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')
<!-----------------------------add customer---------------------------------------------->
<div class="card card-style">
   <div class="heading" style="width:100%;background-color:#2b579a;color:white">Personal Detail</div>
   <!-- <div class="modal-header d-flex align-items-center bg-primary text-white">
      <h5 class="text-center">Add</h5>
      </div> -->
   <form action="{{route('admin.cstore')}}" method="post" enctype="multipart/form-data">
      {{ csrf_field()}}
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="customer_id">
               Customer Code <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="customer_id" id="customer_id" placeholder="Enter Customer Id" value="{{old('customer_id')}}" required="required">
               @if($errors->has('customer_id'))
               <p style="color:red;">{{ $errors->first('customer_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
                <label for="password">Password <span class="text-danger">*</span></label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                @if($errors->has('password'))
                  <p style="color:red;">{{ $errors->first('password') }}</p>
                @endif
            </div>

             <div class="form-group col-md-4">
                <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                @if($errors->has('password_confirmation'))
                  <p style="color:red;">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="name">Name <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="{{old('name')}}">
               @if($errors->has('name'))
               <p style="color:red;">{{ $errors->first('name') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="mobile">Mobile <span class="text-danger">*</span></label>
               <input type="text" data-validation="required numeric" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" value="{{ old('mobile') }}">
               @if($errors->has('mobile'))
               <p style="color:red;">{{ $errors->first('mobile') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="email">Email </label>
               <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" value="{{ old('email') }}">
               @if($errors->has('email'))
               <p style="color:red;">{{ $errors->first('email') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
           <div class="form-group col-md-4">
             <label for="image">Your Photo</label>
             <input type="file" name="image" id="image" placeholder="Enter Image" >
             @if($errors->has('image'))
               <p>{{ $errors->first('image') }}</p>
             @endif
           </div> 
            <div class="col-sm-4">
               <label for="dob">
               Date Of Birth </label>
               <div class="input-group date" data-provide="datepicker">
                  <input type="text" class="form-control" placeholder="Enter Date Of Birth" name="dob" value="">
                  <div class="input-group-addon">
                     <span class="glyphicon glyphicon-th"></span>
                  </div>
                  @if($errors->has('dob'))
                  <p>{{ $errors->first('dob') }}</p>
                  @endif
               </div>
            </div>
            <div class="form-group col-md-4">
               <label for="sex">Sex</label><br>
               <input type="radio" name="sex" value="male"> Male
               <input type="radio" name="sex" value="female"> Female
               @if($errors->has('sex'))
               <p>{{ $errors->first('sex') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="father_husband_wife">Father's Name </label>
               <input type="father_husband_wife" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father Name">
               @if($errors->has('father_husband_wife'))
               <p>{{ $errors->first('father_husband_wife') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="age">Age </label>
               <input type="text" class="form-control" name="age" id="age" placeholder="Enter age" value="{{ old('age') }}">
               @if($errors->has('age'))
               <p>{{ $errors->first('age') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="nationality">Nationality </label>
               <input type="text" class="form-control" name="nationality" id="nationality" placeholder="Enter nationality one" value="{{ old('nationality') }}">
               @if($errors->has('nationality'))
               <p>{{ $errors->first('nationality') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="address one">Address One </label>
               <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter address one" value="{{ old('address_one') }}">
               @if($errors->has('address_one'))
               <p>{{ $errors->first('address_one') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="address_two">Address Two </label>
               <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter address two" value="{{ old('address_two') }}">
               @if($errors->has('address_two'))
               <p>{{ $errors->first('address_two') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="zipcode">Zipcode </label>
               <input type="text" class="form-control zipcode zipcode-get-csc" name="zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ old('zipcode') }}">
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
               <select class="form-control ccountry_id" name="country_id" id="country_id">
                  <option value="">Select Country</option>
                  @foreach($countries as $id=>$country)
                  <option value="{{$id}}">{{$country}}</option>
                  @endforeach
               </select>
               @if($errors->has('country_id'))
               <p>{{ $errors->first('country_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="state">State </label>
               <select class="form-control sstate_id" name="state_id" id="state_id">
                  <option value="">Select State</option>
               </select>
               @if($errors->has('state_id'))
               <p>{{ $errors->first('state_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="city">City </label>
               <select class="form-control ccity_id" name="city_id" id="city_id">
                  <option value="">Select City</option>
               </select>
               @if($errors->has('city_id'))
               <p>{{ $errors->first('city_id') }}</p>
               @endif
            </div>
         </div>
      </div>

<div class="heading" style="width:100%;background-color:#2b579a;color:white">Bank Details </div><br>
<div class="col-md-12">
	<div class="row">
		<div class="form-group col-md-6">
			<label>Account Holder Name</label>
			<input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" value="{{ old('account_holder_name') }}">
			@if($errors->has('account_holder_name'))
			<p>{{ $errors->first('account_holder_name') }}</p>
			@endif
		</div>
		<div class="form-group col-md-6">
			<label>Bank Name</label>
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
	</div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="form-group col-md-6">
			<label>Account No</label>
			<input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number" value="{{ old('account_number') }}">
			@if($errors->has('account_number'))
			<p>{{ $errors->first('account_number') }}</p>
			@endif
		</div>
		<div class="form-group col-md-6">
			<label>IFSC Code</label>
			<input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{ old('ifsc_code') }}">
			@if($errors->has('ifsc_code'))
			<p>{{ $errors->first('ifsc_code') }}</p>
			@endif
		</div>
    <div class="form-group col-md-6">
      <label for="pan_no">Pan Number</label>
      <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter Pan Number" value="{{old('pan_no')}}" >
      @if($errors->has('pan_no'))
        <p>{{ $errors->first('pan_no') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
      <label for="payment_type">Payment Type <sup class="text-danger">*</sup></label>
      <select name="payment_type" class="form-control" required="required">
        <option value="">Select...</option>
        <option value="cash">Cash</option>
        <option value="accumulate">Accumulate</option>
        <option value="bank">Bank</option>
      </select>
      @if($errors->has('payment_type'))
        <p>{{ $errors->first('payment_type') }}</p>
      @endif
    </div>
	</div>
</div>

<div class="heading" style="width:100%;background-color:#2b579a;color:white">Cheque For Security </div><br>
<div class="data">
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-6">
        <label>Cheque Issue Date</label>
        <input type="date" class="form-control" name="cheque_issue_date[]" id="cheque_issue_date" placeholder="Enter Account Holder Name" value="{{ old('cheque_issue_date') }}">
        @if($errors->has('cheque_issue_date'))
        <p>{{ $errors->first('cheque_issue_date') }}</p>
        @endif
      </div>
      <div class="form-group col-md-6">
        <label>Cheque Maturity Date</label>
        
         <input type="date" name="cheque_maturity_date[]" class="form-control" placeholder="Enter Bank Name" value="{{old('cheque_maturity_date')}}">
        @if($errors->has('cheque_maturity_date'))
        <p>{{ $errors->first('cheque_maturity_date') }}</p>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-6">
        <label>Cheque Bank Name</label>
        <input type="text" class="form-control" name="cheque_bank_name[]" id="cheque_bank_name" placeholder="Enter Bank Name" value="{{ old('cheque_bank_name') }}">
        @if($errors->has('cheque_bank_name'))
        <p>{{ $errors->first('cheque_bank_name') }}</p>
        @endif
      </div>
      <div class="form-group col-md-6">
        <label>Cheque Date</label>
        <input type="date" class="form-control" name="cheque_date[]" id="cheque_date" placeholder="Enter date" value="{{ old('cheque_date') }}">
        @if($errors->has('cheque_date'))
        <p>{{ $errors->first('cheque_date') }}</p>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-6">
        <label>Cheque Amount</label>
        <input type="text" class="form-control" name="cheque_amount[]" id="cheque_amount" placeholder="Enter Amount" value="{{ old('cheque_amount') }}">
        @if($errors->has('cheque_amount'))
        <p>{{ $errors->first('cheque_amount') }}</p>
        @endif
      </div>
      <div class="form-group col-md-6">
        <label for="pan_no">Scan Copy</label>
        <input type="file" class="form-control" name="scan_copy[]" id="" placeholder="Enter">
      </div>
    </div>
  </div>
</div><button class="btn text-primary  add_more" id="add-more" data-toggle="tooltip" title="Create More"><i class="material-icons">add</i></button><br><br><br>
<div class="heading" style="width:100%;background-color:#2b579a;color:white">NOMINEE</div><br>
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
			<label for="nominee_dob">
			Date Of Birth </label>
			<div class="input-group date" data-provide="datepicker">
			<input type="text" class="form-control"  id="nominee_dob" placeholder="Enter Date Of Birth" name="nominee_dob" value="">
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
<input type="text" class="form-control n-get-csc" data-target-country="nominee_country" data-target-state="nominee_state" data-target-city="nominee_city"" name="nominee_zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ old('nominee_zipcode') }}">
@if($errors->has('nominee_zipcode'))
<p>{{ $errors->first('nominee_zipcode') }}</p>
@endif
</div>
  <div class="form-group col-md-4">
    <label for="country">Country </label>
    <select class="form-control country_id" name="nominee_country_id" id="country_id">
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
      <select class="form-control state_id" name="nominee_state_id" id="state_id">
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
<select class="form-control city_id" name="nominee_city_id" id="city_id">
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


<div class="card card-style">
  <div style="background-color:#2b579a; color: white;">&nbsp;Add Customer Commission</div>
   <div class="">
      {{ csrf_field() }}
         <div class="col-md-12 maini">
            <div class="mania">&nbsp;
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label for="customer">Customer <span class="text-danger">*</span></label>
                     <input type="text" onChange="calcomm(this.value)" id="customer" class="form-control customer" name="customer_invest" placeholder="Enter Customer Invest Percentage"   value="{{ old('customer_invest') }}">
                     @if ($errors->has('customer_invest'))
                        <span class="help-block text-danger d-block">
                          <strong>{{ $errors->first('customer_invest') }}</strong>
                        </span>
                      @endif
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="customer">Sum Of Commission</label>
                     <input type="text" value="" id="sum_of_commission" name="sum_of_commission" class="form-control sum_of_commission" placeholder="Enter Customer Commission Percentage" readonly="">
                  </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 1</label>
                     <?php /*<input type="text" value="" name="associate_name[]"  id="associate" class="form-control associate"   placeholder="Associate One" >*/?> 
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>             
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"   placeholder="Associate One">
                     
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                   </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 2</label>
                     <!-- <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Two"> -->
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Two">
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                   </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div ">
                     <label for="associate">Associate 3</label>
                     <!-- <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Three"> -->
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Three">
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""  id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                   </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 4</label>
                     <!-- <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Four"> -->
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Four">
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                    </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 5</label>
                     <!-- <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Five"> -->
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Five">
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                  </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 6</label>
                     <!-- <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Six">  -->
                     <select class="form-control" name="associate_name[]">
                        <option value="">Select Associate</option>
                        @foreach($associates as $associate)
                           <option value="{{$associate->id}}">{{$associate->name.'-'.$associate->code}}</option>
                        @endforeach
                     </select>                 
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission</label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                                  
                  </div>
               </div>
            </div>
         </div>
      
   </div>
  </div>
</div>
</div>	
<div class="form-group col-md-6" style="margin-top:10px;">
<button type="submit" class="btn btn-primary btn-lg">Save</button>
<?php /*<i class="btn btn-primary col-md-1 float-right add-more" id="add-more" ><i class="material-icons">add</i></i>*/ ?>
<br>  
</div>	
</form>
</div>
</div>
<!---------------------------------------------------------------------------->
@endsection
@section('page_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('css/jquery-ui.css')}}"></script>
<!-- Plugins -->
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('plugins/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>  
   flatpickr('.datepicker-wrap', {
       allowInput: true,
       clickOpens: false,
       wrap: true,
     })


	$(document).ready(function(){
		// alert('ashish');
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
	})
 $(document).ready(function() {
    associateauto();
   var max_fields      = 15; //maximum input boxes allowed
   var wrapper       = $(".mania"); //Fields wrapper
   var add_button      = $(".add-more"); //Add button ID
   
   var numberWords = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','twelve','Thirteen','Fourteen','Fifteeen'];

   var x = 6; //initlal text box count
   $(add_button).click(function(e){
      // alert(x);
       //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
         x++; //text box increment
         $(wrapper).append('<div class="row associate-row"><div class="col-md-6 form-group associate-div"><label for="associate">Associate '+x+' <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value="" id="associate" class="form-control associate ui-autocomplete-input"  placeholder="Associate '+numberWords[x-1]+'" name="associate_name[]"><input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">@error("associate.*") <span class="help-block text-danger d-block"><strong>{{$message}}</strong></span>@enderror</div> <div class="col-md-6 form-group"><label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">  <a class="remove_field btn btn-danger pt-2 mt-1">--</a> @error("commission.*") <span class="help-block text-danger d-block"> <strong>{{$message}}</strong></span>@enderror</div></div>');
         associateauto(); 
      }
      if(max_fields == x){
         $(".add-more").css('display','none');
      }
   });
   
   $(wrapper).on("click",".remove_field", function(e){ 
      if(x <= max_fields){
         $(".add-more").css('display','block');
      }
      //user click on remove text
      e.preventDefault(); $(this).parents('.associate-row').remove(); x--;
      
   })
  
});
function calcomm(elem){
   return $('#sum_of_commission').val(elem);
}
$(document).on("keyup", ".commission", function() {
    var sum = total = 0;
    $(".commission").each(function(){
        sum += +$(this).val();
    });
   // var s = calcomm();
   var s =   $("#customer").val();
// alert(s);var 
   total = (parseFloat(s) + parseFloat(sum));   
    $("#sum_of_commission").val(total);
    if(total > 36){
       alert('Total Sum of Commission Not Greater Than 36');
       $('#customer').val(0);
       $('#sum_of_commission').val(0);

       $('.commission').each(function(){
          $(this).val(0);
       });
       return false;
    }
    return true;
});
   



 function associateauto() {
    
   //  var data = $('.main').parents('.associate-div').find('.associate').val();
   //  alert(data);
     $('.associate').autocomplete({
        source:"{{url('admin/customer/commission/associate/autocomplete')}}",
              select: function( event, ui ) {
               //   console.log(ui.item);
      //   ( "Selected: " + ui.item.value );
            $(this).next().val(ui.item.id);
      //   $('.associate-row').parents('.maini').find('.associate_id').attr("value", ui.item.id);

      }

    });
    return false;
            
}

$(document).ready(function() {
   var max_fields  = 15; //maximum input boxes allowed
   var wrapper     = $(".data"); //Fields wrapper
   var add_button  = $(".add_more"); //Add button ID
   
   var x = 6; //initlal text box count
   $(add_button).click(function(e){
      // alert(x);
       //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
         x++; //text box increment
         $(wrapper).append('<div class="associate-row"><div class="col-md-12">    <div class="row">      <div class="form-group col-md-6">        <label>Cheque Issue Date</label>        <input type="date" class="form-control" name="cheque_issue_date[]" id="cheque_issue_date" placeholder="Enter Account Holder Name" value="{{ old('cheque_issue_date') }}">        @if($errors->has('cheque_issue_date'))        <p>{{ $errors->first('cheque_issue_date') }}</p>        @endif      </div>      <div class="form-group col-md-6">        <label>Cheque Maturity Date</label>                 <input type="date" name="cheque_maturity_date[]" class="form-control" placeholder="Enter Bank Name" value="{{old('cheque_maturity_date')}}">        @if($errors->has('cheque_maturity_date'))        <p>{{ $errors->first('cheque_maturity_date') }}</p>        @endif      </div>    </div>  </div>  <div class="col-md-12">    <div class="row">      <div class="form-group col-md-6">        <label>Cheque Bank Name</label>        <input type="text" class="form-control" name="cheque_bank_name[]" id="cheque_bank_name" placeholder="Enter Bank Name" value="{{ old('cheque_bank_name') }}">        @if($errors->has('cheque_bank_name'))        <p>{{ $errors->first('cheque_bank_name') }}</p>        @endif      </div>      <div class="form-group col-md-6">        <label>Cheque Date</label>        <input type="date" class="form-control" name="cheque_date[]" id="cheque_date" placeholder="Enter date" value="{{ old('cheque_date') }}">        @if($errors->has('cheque_date'))        <p>{{ $errors->first('cheque_date') }}</p>        @endif      </div>    </div>  </div>  <div class="col-md-12">    <div class="row">      <div class="form-group col-md-6">        <label>Cheque Amount</label>        <input type="text" class="form-control" name="cheque_amount[]" id="cheque_amount" placeholder="Enter Amount" value="{{ old('cheque_amount') }}">        @if($errors->has('cheque_amount'))        <p>{{ $errors->first('cheque_amount') }}</p>        @endif      </div>      <div class="form-group col-md-6">        <label for="pan_no">Scan Copy</label>        <input type="file" class="form-control" name="scan_copy[]" id="" placeholder="Enter">      </div>    </div><a class="remove_field btn text-danger pt-2 mt-1"><i class="material-icons">delete</i></a>  </div></div>');
      }
      if(max_fields == x){
         $(".add_more").css('display','none');
      }
   });
   
   $(wrapper).on("click",".remove_field", function(e){ 
      if(x <= max_fields){
         $(".add_more").css('display','block');
      }
      //user click on remove text
      e.preventDefault(); $(this).parents('.associate-row').remove(); x--;
      
   })
  
});
</script>
@endsection

