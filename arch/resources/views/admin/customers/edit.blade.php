

@extends('layouts.admin.default')
@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit # {{ $customer->name }} </li>
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
   <form action="{{route('admin.customerUpdate')}}" method="post" enctype="multipart/form-data">
      {{ csrf_field()}}
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="customer_id">
               Customer Id <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="customer_id" id="customer_id" placeholder="Enter Customer Id" value="{{$customer->code}}">
               <input type="hidden" class="form-control" name="id" id="id" placeholder="Enter Customer Id" value="{{$customer->id}}">
               @if($errors->has('customer_id'))
               <p>{{ $errors->first('customer_id') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="name">Name <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="{{$customer->name}}">
               @if($errors->has('name'))
               <p>{{ $errors->first('name') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="mobile">Mobile <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" value="{{$customer->mobile}}">
               @if($errors->has('mobile'))
               <p>{{ $errors->first('mobile') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="email">Email <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" value="{{ $customer->email }}">
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
                <input type="file" name="image" id="image" placeholder="Enter Image" >
                @if($errors->has('image'))
                  <p>{{ $errors->first('image') }}</p>
                @endif
                <br>
                  <img src="{{asset('images/customer/'.$customer->customerdetails->image)}}" width="50px" height="50px">
            </div> 
            <div class="col-sm-4">
               <label for="dob">
               Date Of Birth </label>
               <div class="input-group date" data-provide="datepicker">
                  <input type="text" class="form-control" placeholder="Enter Date Of Birth" name="dob" value="<?php echo isset($customer->customerdetails->dob)?date('d-m-Y',strtotime($customer->customerdetails->dob)):'' ?>">
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
               <input type="radio" name="sex" value="male"  @if($customer->customerdetails->sex == 'male') {{'checked'}} @endif > Male
               <input type="radio" name="sex" value="female"  @if($customer->customerdetails->sex == 'female') {{'checked'}} @endif > Female
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
               <input type="father_husband_wife" class="form-control" name="father_husband_wife" id="father_husband_wife" placeholder="Enter Father Name" value=" {{ $customer->customerdetails->father_husband_wife }} ">
               @if($errors->has('father_husband_wife'))
               <p>{{ $errors->first('father_husband_wife') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="age">Age </label>
               <input type="text" class="form-control" name="age" id="age" placeholder="Enter age" value="{{ $customer->customerdetails->age }}" >
               @if($errors->has('age'))
               <p>{{ $errors->first('age') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="nationality">Nationality </label>
               <input type="text" class="form-control" name="nationality" id="nationality" placeholder="Enter nationality one" value="{{ $customer->customerdetails->nationality }}">
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
               <input type="text" class="form-control" name="address_one" id="address one" placeholder="Enter address one" value="{{ $customer->customerdetails->address_one }}">
               @if($errors->has('address_one'))
               <p>{{ $errors->first('address_one') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="address_two">Address Two </label>
               <input type="text" class="form-control" name="address_two" id="address_two" placeholder="Enter address two" value="{{ $customer->customerdetails->address_two }}">
               @if($errors->has('address_two'))
               <p>{{ $errors->first('address_two') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="zipcode">Zipcode </label>
               <input type="text" class="form-control zipcode-get-csc" name="zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ $customer->customerdetails->zipcode }} " max="6">
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
                  
                  <option value="@if($customer->customerdetails->country_id) {{ $customer->customerdetails->country->id }} @endif">@if($customer->customerdetails->country_id) {{ $customer->customerdetails->country->name }} @endif</option>
               </select>
               @if($errors->has('country_id'))
               <p>{{ $errors->first('country_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="state">State </label>
               <select class="form-control sstate_id" name="state_id" id="state_id">
                  <option value="@if($customer->customerdetails->state_id) {{ $customer->customerdetails->state->id }} @endif"> @if($customer->customerdetails->state_id) {{ $customer->customerdetails->state->name }} @endif</option>
               </select>
               @if($errors->has('state_id'))
               <p>{{ $errors->first('state_id') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="city">City </label>
               <select class="form-control ccity_id" name="city_id" id="city_id">
                  <option value="@if($customer->customerdetails->city_id) {{ $customer->customerdetails->city->id }} @endif">@if($customer->customerdetails->city_id) {{ $customer->customerdetails->city->name }} @endif</option>
               </select>
               @if($errors->has('city_id'))
               <p>{{ $errors->first('city_id') }}</p>
               @endif
            </div>
         </div>
      </div>
</div>
<div class="card card-style">
 <div class="heading" style="width:100%;background-color:#2b579a;color:white">Bank Details </div>
<div class="col-md-12">
   <div class="row">
      <div class="form-group col-md-6">
         <label>Account Holder Name</label>
         <input type="text" class="form-control" name="account_holder_name" id="account_holder_name" placeholder="Enter Account Holder Name" value="{{ $customer->customerdetails->account_holder_name }} ">
         @if($errors->has('account_holder_name'))
         <p>{{ $errors->first('account_holder_name') }}</p>
         @endif
      </div>
      <div class="form-group col-md-6">
         <label>Bank Name</label>
         <!-- <select name="bank_id" class="form-control" id="bank_name">
        <option value="">Select Bank</option>
        @foreach($banks as $bank)
          <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
        @endforeach
      </select> -->
      <input type="text" name="bank_name" class="form-control" value="{{$customer->customerdetails->bank_name}}">
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
         <input type="text" class="form-control" name="account_number" id="account_number" placeholder="Enter Account Number" value="{{ $customer->customerdetails->account_number }} ">
         @if($errors->has('account_number'))
         <p>{{ $errors->first('account_number') }}</p>
         @endif
      </div>
      <div class="form-group col-md-6">
         <label>IFSC Code</label>
         <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="Enter IFSC Code" value="{{ $customer->customerdetails->ifsc_code }} ">
         @if($errors->has('ifsc_code'))
         <p>{{ $errors->first('ifsc_code') }}</p>
         @endif
      </div>
     <div class="form-group col-md-6">
       <label for="pan_no">Pan Number</label>
       <input type="text" class="form-control" name="pan_no" id="pan_no" placeholder="Enter Pan Number" value="{{ $customer->customerdetails->pan_no }}" >
       @if($errors->has('pan_no'))
         <p>{{ $errors->first('pan_no') }}</p>
       @endif
     </div>
   </div>
</div>
</div>
<div class="card card-style">
   <div class="heading" style="width:100%;background-color:#2b579a;color:white">NOMINEE</div>
   <div class="col-md-12">
   	<div class="row">  
   		<div class="form-group col-md-4">
   			<label for="nominee_name">Nominee Name </label>
   			<input type="nominee_name" class="form-control" name="nominee_name" id="nominee_name" placeholder="Enter Nominee Name" value="{{ $customer->customerdetails->nominee_name }} ">
   			@if($errors->has('nominee_name'))
   			<p>{{ $errors->first('nominee_name') }}</p>
   			@endif
   		</div>   
   		<div class="col-sm-4">
   			<label for="nominee_dob">
   			Date Of Birth </label>
   			<div class="input-group date" data-provide="datepicker">
   			<input type="text" class="form-control"   id="nominee_dob" placeholder="Enter Date Of Birth" name="nominee_dob"value="<?php echo isset($customer->customerdetails->nominee_dob)?date('d-m-Y',strtotime($customer->customerdetails->nominee_dob)):'' ?>">
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
      <input type="radio" name="nominee_sex" value="male"  @if($customer->customerdetails->nominee_sex == 'male') {{'checked'}} @endif> Male
      <input type="radio" name="nominee_sex" value="female"  @if($customer->customerdetails->nominee_sex == 'female') {{'checked'}} @endif> Female
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
      <input type="text" class="form-control" name="nominee_relation_with_applicable" id="nominee_relation_with_applicable" placeholder="Enter Relationship With Applicant" value="{{ $customer->customerdetails->nominee_relation_with_applicable }} ">
      @if($errors->has('nominee_relation_with_applicable'))
      <p>{{ $errors->first('nominee_relation_with_applicable') }}</p>
      @endif
      </div> 
      <div class="form-group col-md-4">
      <label for="address one">Address One </label>
      <input type="text" class="form-control" name="nominee_address_one" id="nominee_address one" placeholder="Enter address one" value="{{ $customer->customerdetails->nominee_address_one }}">
      @if($errors->has('nominee_address_one'))
      <p>{{ $errors->first('nominee_address_one') }}</p>
      @endif
      </div> 
      <div class="form-group col-md-4">
      <label for="nominee_address_two">Address Two </label>
      <input type="text" class="form-control" name="nominee_address_two" id="nominee_address_two" placeholder="Enter address two" value="{{ $customer->customerdetails->nominee_address_two }}">
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
      <input type="text" class="form-control zipcode n-get-csc" name="nominee_zipcode" id="zipcode" placeholder="Enter zipcode" value="{{ $customer->customerdetails->nominee_zipcode }}">
      @if($errors->has('nominee_zipcode'))
      <p>{{ $errors->first('nominee_zipcode') }}</p>
      @endif
      </div>
      <div class="form-group col-md-4">
      <label for="country">Country </label>
      <select class="form-control country_id" name="nominee_country_id" id="country_id">
      <option value="@if($customer->customerdetails->nominee_country_id) {{ $customer->customerdetails->country->id }} @endif"> @if($customer->customerdetails->nominee_country_id) {{ $customer->customerdetails->country->name }} @endif</option>
      </select>
      @if($errors->has('nominee_country_id'))
      <p>{{ $errors->first('nominee_country_id') }}</p>
      @endif
      </div> 
      <div class="form-group col-md-4">
      <label for="state">State </label>
      <select class="form-control state_id" name="nominee_state_id" id="state_id">
      <option value="@if($customer->customerdetails->nominee_state_id) {{ $customer->customerdetails->state->id }} @endif "> @if($customer->customerdetails->nominee_state_id) {{ $customer->customerdetails->state->name }} @endif</option>
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
      <option value="@if($customer->customerdetails->nominee_city_id) {{ $customer->customerdetails->city->id }} @endif"> @if($customer->customerdetails->nominee_city_id) {{ $customer->customerdetails->city->name }} @endif</option>
      </select>
      @if($errors->has('nominee_city_id'))
      <p>{{ $errors->first('nominee_city_id') }}</p>
      @endif
      </div>
      <div class="form-group col-md-4">
      <label for="nominee_age">Age </label>
      <input type="text" class="form-control" name="nominee_age" id="nominee_age" placeholder="Enter age" value="{{ $customer->customerdetails->nominee_age }}" >
      @if($errors->has('nominee_age'))
      <p>{{ $errors->first('nominee_age') }}</p>
      @endif
      </div> 
      </div>
   </div>
</div>
</div>	
<div class="form-group col-md-4" style="margin-top:30px;">
<button type="submit" class="btn btn-primary">Save</button><br>  
</div>	
</form>
</div>
<!---------------------------------------------------------------------------->
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
<script>
$(document).ready(function(){
		// alert('ashish');
		$('.n-get-csc').on('keyup',function(){
			var zip = $('.n-get-csc').val();
			// alert(zip);
			$.ajax({
				url:'{{url("/admin/getzip")}}',
				data:{zip:zip,_token:'<?php echo csrf_token() ?>'},
				type:'post',
				dataType:'json',
				success:function(resp){
					// alert(resp);
					$('.country_id').html('<option value="'+resp.country+'">'+resp.country_name+'</option>')
					$('.state_id').html('<option value="'+resp.state+'">'+resp.state_name+'</option>')
					$('.city_id').html('<option value="'+resp.city+'">'+resp.city_name+'</option>')

				}
			})
		})
		$('.zipcode-get-csc').on('keyup',function(){
			// alert('asdf');
			var zip = $('.zipcode-get-csc').val();
			// alert(zip);
			$.ajax({
				url:'{{url("/admin/getzip")}}',
				data:{zip:zip,_token:'<?php echo csrf_token() ?>'},
				type:'post',
				dataType:'json',
				success:function(resp){
					// alert(resp);
					$('.ccountry_id').html('<option value="'+resp.country+'">'+resp.country_name+'</option>')
					$('.sstate_id').html('<option value="'+resp.state+'">'+resp.state_name+'</option>')
					$('.ccity_id').html('<option value="'+resp.city+'">'+resp.city_name+'</option>')

				}
			})
		})
	})
</script>
@endsection

