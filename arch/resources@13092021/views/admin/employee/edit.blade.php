

@extends('layouts.admin.default')
@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit # {{ $employee->name }} </li>
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
   <form action="{{route('admin.employeeUpdate')}}" method="post">
      {{ csrf_field()}}
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="employee_id">
               Employee Code <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="employee_id" id="employee_id" placeholder="Enter employee Id" value="{{$employee->code}}">
               <input type="hidden" class="form-control" name="id" id="id" placeholder="Enter employee Id" value="{{$employee->id}}">
               @if($errors->has('employee_id'))
               <p class="text-danger">{{ $errors->first('employee_id') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12" style="padding-top:20px">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="name">Name <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" value="{{$employee->name}}">
               @if($errors->has('name'))
               <p>{{ $errors->first('name') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="mobile">Mobile <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Enter mobile" value="{{$employee->mobile}}">
               @if($errors->has('mobile'))
               <p>{{ $errors->first('mobile') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="email">Email <span class="text-danger">*</span></label>
               <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" value="{{ $employee->email }}">
               @if($errors->has('email'))
               <p>{{ $errors->first('email') }}</p>
               @endif
            </div>
         </div>
      </div>
      <div class="col-md-12">
         <div class="row">
            <div class="form-group col-md-4">
               <label for="password">Password <span class="text-danger">*</span></label>
               <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" value="" required="">
               @if($errors->has('password'))
               <p class="text-danger">{{ $errors->first('password') }}</p>
               @endif
            </div>
            <div class="form-group col-md-4">
               <label for="confirm_password"> Confirm  Password <span class="text-danger">*</span></label>
               <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm password" value="" required="">
               @if($errors->has('confirm_password'))
               <p class="text-danger">{{ $errors->first('confirm_password') }}</p>
               @endif
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

