@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Detail</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
<div class="card card-style">
<div class="heading" style="width:100%;background-color:#2b579a;color:white">Personal Details </div>  
  <div class="col-md-12" style="padding-top:20px">
      <div class="row">
         <div class="form-group col-md-4" >
            <label for="customer_id">
               Customer Id <span class="text-danger">*</span></label>
               <input disabled disabled type="text" class="form-control" name="customer_id" id="customer_id" placeholder="Enter Customer Id" value="{{$customer->code}}">
               
               @if($errors->has('customer_id'))
               <p>{{ $errors->first('customer_id') }}</p>
               @endif
         </div>
        <div class="form-group col-md-4">
          <label for="payment_type">Payment Type <sup class="text-danger">*</sup></label>
          <select name="payment_type" class="form-control" disabled="disabled">
            <option value="">Select...</option>
            <option value="cash" @if($customer->customerdetails->payment_type == 'cash') selected="selected" @endif>Cash</option>
            <option value="accumulate" @if($customer->customerdetails->payment_type == 'accumulate') selected="selected" @endif>Accumulate</option>
            <option value="bank" @if($customer->customerdetails->payment_type== 'bank') selected="selected" @endif>Bank</option>
            <option value="hold" @if($customer->customerdetails->payment_type== 'hold') selected="selected" @endif>Hold</option>
          </select>
          @if($errors->has('payment_type'))
            <p>{{ $errors->first('payment_type') }}</p>
          @endif
        </div>
      </div>
   </div>
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="name">Name <span class="text-danger">*</span></label>
               <input disabled type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="{{$customer->name}}">
               @if($errors->has('name'))
               <p>{{ $errors->first('name') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="mobile">Mobile <span class="text-danger">*</span></label>
               <input disabled type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" value="{{$customer->mobile}}">
               @if($errors->has('mobile'))
               <p>{{ $errors->first('mobile') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="email">Email <span class="text-danger">*</span></label>
               <input disabled type="text" class="form-control" name="email" id="email" placeholder="Enter email" value="{{ $customer->email }}">
               @if($errors->has('email'))
               <p>{{ $errors->first('email') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
                <label for="image">Your Photo</label>
                  <img src="{{asset('images/customer/'.$customer->customerdetails->image)}}" width="50px" height="50px">
            </div> 
            <div class="col-sm-4">
               <label for="dob">
               Date Of Birth </label>
               <div class="input-group date" data-provide="datepicker">
                  <input disabled type="text" class="form-control" placeholder="Enter Date Of Birth" name="dob" value="<?php echo isset($customer->customerdetails->dob)?date('d-m-Y',strtotime($customer->customerdetails->dob)):'' ?>">
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
               <input disabled type="radio" name="sex" value="male"  @if($customer->customerdetails->sex == 'male') {{'checked'}} @endif > Male
               <input disabled type="radio" name="sex" value="female"  @if($customer->customerdetails->sex == 'female') {{'checked'}} @endif > Female
               @if($errors->has('sex'))
               <p>{{ $errors->first('sex') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="father_husband_wife">Father's Name </label>
               <input disabled type="father_husband_wife" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father Name" value=" {{ $customer->customerdetails->father_husband_wife }} ">
               @if($errors->has('father_husband_wife'))
               <p>{{ $errors->first('father_husband_wife') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="age">Age </label>
               <input disabled type="text" class="form-control" name="age" id="age" placeholder="Enter age" value="{{ $customer->customerdetails->age }}" >
               @if($errors->has('age'))
               <p>{{ $errors->first('age') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="nationality">Nationality </label>
               <input disabled type="text" class="form-control" name="nationality" id="nationality" placeholder="Enter nationality one" value="{{ $customer->customerdetails->nationality }}">
               @if($errors->has('nationality'))
               <p>{{ $errors->first('nationality') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="address one">Address One </label>
               <input disabled type="text" class="form-control" name="address_one" id="address one" placeholder="Enter address one" value="{{ $customer->customerdetails->address_one }}">
               @if($errors->has('address_one'))
               <p>{{ $errors->first('address_one') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="address_two">Address Two </label>
               <input disabled type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter address two" value="{{ $customer->customerdetails->address_two }}">
               @if($errors->has('address_two'))
               <p>{{ $errors->first('address_two') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="zipcode">Zipcode </label>
               <input disabled type="text" class="form-control zipcode" name="zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ $customer->customerdetails->zipcode }} ">
               @if($errors->has('zipcode'))
               <p>{{ $errors->first('zipcode') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="country">Country </label>
               <input disabled type="text" class="form-control zipcode" name="country_id" id="zipcode" placeholder="Enter Country" value="{{ $customer->customerdetails->country_id }} ">
               @if($errors->has('country_id'))
               <p>{{ $errors->first('country_id') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="state">State </label>
               <select class="form-control state_id" name="state_id" id="state_id">
                  <option value="">Select State</option>
               </select>
               @if($errors->has('state_id'))
               <p>{{ $errors->first('state_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="city">City </label>
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
<div class="heading" style="width:100%;background-color:#2b579a;color:white">Bank Details </div>
<div class="col-md-12">
	<div class="row">
		<div class="form-group col-md-6">
			<label>Account Holder Name</label>
			<input disabled type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" value="{{ $customer->customerdetails->account_holder_name }} ">
			@if($errors->has('account_holder_name'))
			<p>{{ $errors->first('account_holder_name') }}</p>
			@endif
		</div>
		<div class="form-group col-md-6">
			<label>Bank Name</label>
			<input disabled type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name" value="{{ $customer->customerdetails->bank_name }} ">
			@if($errors->has('bank_name'))
			<p>{{ $errors->first('bank_name') }}</p>
			@endif
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="row">
		<div class="form-group col-md-6">
			<label>Account No</label>
			<input disabled type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number" value="{{ $customer->customerdetails->account_number }} ">
			@if($errors->has('account_number'))
			<p>{{ $errors->first('account_number') }}</p>
			@endif
		</div>
		<div class="form-group col-md-6">
			<label>IFSC Code</label>
			<input disabled type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{ $customer->customerdetails->ifsc_code }} ">
			@if($errors->has('ifsc_code'))
			<p>{{ $errors->first('ifsc_code') }}</p>
			@endif
		</div>
	</div>
</div>
</div><br>

<div class="card card-style">
<div class="heading" style="width:100%;background-color:#2b579a;color:white">NOMINEE</div>
<div class="col-md-12">
	<div class="row">  
		<div class="form-group col-md-4">
			<label for="nominee_name">Nominee Name </label>
			<input disabled type="nominee_name" class="form-control" name="nominee_name" id="nominee_name" placeholder="Enter Nominee Name" value="{{ $customer->customerdetails->nominee_name }} ">
			@if($errors->has('nominee_name'))
			<p>{{ $errors->first('nominee_name') }}</p>
			@endif
		</div>   
		<div class="col-sm-4">
			<label for="nominee_dob">
			Date Of Birth </label>
			<div class="input-group date" data-provide="datepicker">
			<input disabled type="text" class="form-control"   id="nominee_dob" placeholder="Enter Date Of Birth" name="nominee_dob"value="<?php echo isset($customer->customerdetails->nominee_dob)?date('d-m-Y',strtotime($customer->customerdetails->nominee_dob)):'' ?>">
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
<input disabled type="radio" name="nominee_sex" value="male"  @if($customer->customerdetails->nominee_sex == 'male') {{'checked'}} @endif> Male
<input disabled type="radio" name="nominee_sex" value="female"  @if($customer->customerdetails->nominee_sex == 'female') {{'checked'}} @endif> Female
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
<input disabled type="text" class="form-control" name="nominee_relation_with_applicable" id="nominee_relation_with_applicable" placeholder="Enter Relationship With Applicant" value="{{ $customer->customerdetails->nominee_relation_with_applicable }} ">
@if($errors->has('nominee_relation_with_applicable'))
<p>{{ $errors->first('nominee_relation_with_applicable') }}</p>
@endif
</div> 
<div class="form-group col-md-4">
<label for="address one">Address One </label>
<input disabled type="text" class="form-control" name="nominee_address_one" id="nominee_address one" placeholder="Enter address one" value="{{ $customer->customerdetails->nominee_address_one }}">
@if($errors->has('nominee_address_one'))
<p>{{ $errors->first('nominee_address_one') }}</p>
@endif
</div> 
<div class="form-group col-md-4">
<label for="nominee_address_two">Address Two </label>
<input disabled type="text" class="form-control" name="nominee_address_two" id="nominee_address_two" placeholder="Enter address two" value="{{ $customer->customerdetails->nominee_address_two }}">
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
<input disabled type="text" class="form-control zipcode" name="nominee_zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ $customer->customerdetails->nominee_zipcode }}">
@if($errors->has('nominee_zipcode'))
<p>{{ $errors->first('nominee_zipcode') }}</p>
@endif
</div>
<div class="form-group col-md-4">
<label for="country">Country </label>
<input disabled type="text" class="form-control zipcode" name="nominee_country_id" id="nominee_country_id" placeholder="Enter Country" value="{{ $customer->customerdetails->nominee_country_id }} ">
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
<input disabled type="text" class="form-control" name="nominee_age" id="nominee_age" placeholder="Enter age" value="{{ $customer->customerdetails->nominee_age }}" >
@if($errors->has('nominee_age'))
<p>{{ $errors->first('nominee_age') }}</p>
@endif
</div> 
</div>
</div>
</div><br>	

<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerDelete')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Delete Customer</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="delete_customer-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-xs">Delete</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!------------------------------------------------------------------->


@if(Auth::user()->login_type == 'superadmin') 
<div class="col-md-8">
   <div class="row">
      <div class="col-md-1">
         <a href="{{ url('admin/customer-edit/'.encrypt($customer->id)) }}" class="btn btn-primary btn-md">Edit</a>
      </div>
      <div class="col-md-1">
         <a class="btn btn-danger delete_confirm text-light" data-id="{{$customer->id}}">Delete</a>
      </div>
   </div>       
</div><br><br>
@endif
@endsection

@section('page_js')
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script>       
$('.delete_confirm').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.delete_confirm")}}',
    type:'post',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_customer-container').html(data);
        $('#delete_customer').modal('show');
      
    }
  });
});
</script>
@endsection