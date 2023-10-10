

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
                 <label for="code">
                 Customer Id <span class="text-danger">*</span></label>
                 <input type="text" class="form-control" name="code" id="code" placeholder="Enter Customer Id" value="{{$customer->code}}">
                 <input type="hidden" class="form-control" name="id" id="id" placeholder="Enter Customer Id" value="{{$customer->id}}">
                 @if($errors->has('code'))
                 <p>{{ $errors->first('code') }}</p>
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
                 <label for="email">Email </label>
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
                 <label for="country">Country</label>
                 <select class="form-control country_id" name="country_id" id="country_id" >
                    <option value="">Select Country</option>
                    @foreach($countries as $id=>$country)
                    <option value="{{$id}}"@if($id) selected="selected"@endif>{{$country}}</option>
                    @endforeach
                 </select>
                 @if($errors->has('country_id'))
                 <p>{{ $errors->first('country_id') }}</p>
                 @endif
              </div>
              <div class="form-group col-md-4">
                 <label for="state">State</label>
                 <select class="form-control state_id" name="state_id" id="state_id" >
                    <option value="">Select State</option>
                    @foreach($states as $id => $state)
                    <option value="{{$state->id}}" @if($customer->customerdetails->state_id == $state->id) {{'selected'}} @endif >{{$state->name}}</option>
                  @endforeach
                 </select>
                 @if($errors->has('state_id'))
                 <p>{{ $errors->first('state_id') }}</p>
                 @endif
              </div>
              <div class="form-group col-md-4">
                 <label for="city">City</label>
                 <select class="form-control city_id" name="city_id" id="city_id" >
                    <option value="">Select City</option>
                     @foreach($cities as $id => $city)
                      <option value="{{$city->id}}" @if($customer->customerdetails->city_id == $city->id) {{'selected'}} @endif >{{$city->name}}</option>
                    @endforeach
                 </select>
                 @if($errors->has('city_id'))
                 <p>{{ $errors->first('city_id') }}</p>
                 @endif
              </div>
           </div>
        </div>

        <div class="card card-style">
          <div class="heading" style="width:100%;background-color:#2b579a;color:white">Bank Details </div>
          <div class="col-md-12"><br>
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
              <div class="form-group col-md-6">
                <label for="payment_type">Payment Type <sup class="text-danger">*</sup></label>
                <select name="payment_type" class="form-control">
                <option value="">Select...</option>
                <option value="cash" @if($customer->customerdetails->payment_type == 'cash') selected="selected" @endif>Cash</option>
                <option value="accumulate" @if($customer->customerdetails->payment_type == 'accumulate') selected="selected" @endif>Accumulate</option>
                <option value="bank" @if($customer->customerdetails->payment_type== 'bank') selected="selected" @endif>Bank</option>
                <option value="hold" @if($customer->customerdetails->payment_type== 'hold') selected="selected" @endif>Hold</option>
                <option value="no_interest" @if($customer->customerdetails->payment_type== 'no_interest') selected="selected" @endif>No Interest</option>
                </select>
                @if($errors->has('payment_type'))
                <p>{{ $errors->first('payment_type') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>
    
      <div class="card card-style">
        <div class="heading" style="width:100%;background-color:#2b579a;color:white">Cheque For Security </div><br>
        <div class="editdata">
          @foreach($customer->customersecuritycheque as $key => $s_cheque)
          
            <div class="col-md-12"><br>
              <div class="row">
                <div class="form-group col-md-6">
                  <input type="hidden" name="customer_id[]" value="{{$customer->id}}" class="form-control">
                  <input type="hidden" name="ids[]" value="{{$s_cheque->id}}" class="form-control">
                  <label>Cheque Issue Date</label>
                  <input type="text" class="form-control datepicker" name="cheque_issue_date[{{$key}}]" id="cheque_issue_date" placeholder="Enter Account Holder Name" value="@if($s_cheque) <?php echo  date('j-m-Y',strtotime($s_cheque->cheque_issue_date)) ?> @endif">
                </div>
                <div class="form-group col-md-6">
                  <label>Cheque Maturity Date</label>
                  <input type="text" name="cheque_maturity_date[{{$key}}]" class="form-control datepicker" placeholder="Enter Bank Name" value="@if($s_cheque) <?php echo  date('j-m-Y',strtotime($s_cheque->cheque_maturity_date)) ?> @endif">
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
                  <input type="text" class="form-control" name="cheque_bank_name[{{$key}}]" id="cheque_bank_name" placeholder="Enter Bank Name" value="@if($s_cheque){{ $s_cheque->cheque_bank_name }}@endif">
                  @if($errors->has('cheque_bank_name'))
                  <p>{{ $errors->first('cheque_bank_name') }}</p>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Cheque Number</label>
                  <input type="text" class="form-control" name="cheque_number[{{$key}}]" id="cheque_number" placeholder="Enter Cheque Number" value="@if($s_cheque){{ $s_cheque->cheque_number }}@endif">
                  @if($errors->has('cheque_number'))
                  <p>{{ $errors->first('cheque_number') }}</p>
                  @endif
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-6">
                  <label>Cheque Amount</label>
                  <input type="text" class="form-control" name="cheque_amount[{{$key}}]" id="cheque_amount" placeholder="Enter Amount" value="@if($s_cheque){{ $s_cheque->cheque_amount }}@endif">
                  @if($errors->has('cheque_amount'))
                  <p>{{ $errors->first('cheque_amount') }}</p>
                  @endif
                </div>
                <div class="form-group col-md-6">
                  <label>Cheque Date</label>
                  <input type="text" class="form-control datepicker" name="cheque_date[{{$key}}]" id="cheque_date" placeholder="Enter date" value="@if($s_cheque) <?php echo  date('j-m-Y',strtotime($s_cheque->cheque_date)) ?> @endif">
                  @if($errors->has('cheque_date'))
                  <p>{{ $errors->first('cheque_date') }}</p>
                  @endif
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="pan_no">Scan Copy</label>
                  <input type="file" class="form-control" name="scan_copy[{{$key}}]" id="" placeholder="Enter"><br>
                  <img src="@if($s_cheque){{asset('images/chequeScanCopy/'.$s_cheque->scan_copy)}}@endif" width="50px;" height="50px;">
                </div>
              </div>
            </div>
          @endforeach
        </div>
          <button class="btn text-primary  add_more" id="add-more" data-toggle="tooltip" title="Create More"><i class="material-icons">add</i></button><br><br><br>
      </div>
      <div class="card card-style">
        <div class="heading" style="width:100%;background-color:#2b579a;color:white">NOMINEE</div>
        <div class="col-md-12"><br>
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
              <label for="country">Country</label>
              <select class="form-control country_id" name="nominee_country_id" id="nominee_country_id">
                <option value="">Select Country</option>
                @foreach($countries as $id=>$country)
                  <option value="{{$id}}"@if($id) selected="selected"@endif>{{$country}}</option>
                @endforeach
              </select>
              @if($errors->has('country_id'))
              <p>{{ $errors->first('country_id') }}</p>
              @endif
            </div>
            <div class="form-group col-md-4">
              <label for="state">State</label>
              <select class="form-control state_id" name="nominee_state_id" id="nominee_state_id">
                <option value="">Select State</option>
                @foreach($states as $id => $state)
                  <option value="{{$state->id}}" @if($customer->customerdetails->nominee_state_id == $state->id) {{'selected'}} @endif >{{$state->name}}</option>
                @endforeach
              </select>
              @if($errors->has('state_id'))
              <p>{{ $errors->first('state_id') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="form-group col-md-4">
              <label for="city">City</label>
             <select class="form-control city_id" name="nominee_city_id" id="nominee_city_id">
              <option value="">Select City</option>
              @foreach($cities as $id => $city)
                <option value="{{$city->id}}" @if($customer->customerdetails->nominee_city_id == $city->id) {{'selected'}} @endif >{{$city->name}}</option>
              @endforeach
            </select>
              @if($errors->has('city_id'))
              <p>{{ $errors->first('city_id') }}</p>
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
<!-- <div class="card card-style">
  <div style="background-color:#2b579a; color: white;">&nbsp;Add Customer Commission</div>
    {{ csrf_field() }}
    <div class="col-md-12 maini">
        <div class="mania">&nbsp;
           <div class="row">
              <div class="col-md-6 form-group">
                 <label for="customer">Customer <span class="text-danger">*</span></label>
                 <input type="text" onChange="calcomm(this.value)" id="customer" class="form-control customer" name="customer_invest" placeholder="Enter Customer Invest Percentage"   value="{{ $customer->customeractiveinterestpercent->interest_percent }}" readonly="readonly">
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

            @foreach($customer->associatecommissions as $commission)
              <div class="row associate-row">
                <div class="col-md-6 form-group associate-div">
                  <label for="associate">Associate 1</label>
                   <?php /*<input type="text" value="" name="associate_name[]"  id="associate" class="form-control associate"   placeholder="Associate One" >*/?> 
                  <select class="form-control" name="associate_name[]">
                      <option value="">Select Associate</option>
                      @foreach($associates as $associate)
                         <option value="{{$associate->id}}" @if($commission->associate_id == $associate->id) {{'selected'}} @endif>{{$associate->name}} ({{$associate->code}})</option>
                      @endforeach
                  </select>             
                  <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"   placeholder="Associate One">
                </div>
                <div class="col-md-6 form-group">
                   <label for="commission">Commission</label>
                   <input type="text" value="{{$commission->commission_percent}}"   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission " readonly="readonly">
                </div>
              </div>
            @endforeach
           
        </div>
    </div>
</div> -->
    <div class="form-group col-md-4" style="margin-top:30px;">
      <button type="submit" class="btn btn-primary">Save</button><br>  
    </div>  
  </form>
</div>
<!---------------------------------------------------------------------------->
@endsection
@section('page_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<!-- Plugins -->
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('plugins/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
  $('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat: 'dd-mm-yy'
  });

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

</script>

<script>
   $(document).ready(function() {
      var max_fields  = 15; //maximum input boxes allowed
      var wrapper     = $(".editdata"); //Fields wrapper
      var add_button  = $(".add_more"); //Add button ID
      
      var x = 6; //initlal text box count
      $(add_button).click(function(e){
         // alert(x);
          //on add input button click
         e.preventDefault();
         if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="associate-row">   <div class="col-md-12">      <div class="row">         <div class="form-group col-md-6">            <label>Cheque Issue Date</label>        <input type="text" class="form-control datepicker" name="cheque_issue_dates[]" id="cheque_issue_date" placeholder="Enter Account Holder Name">                      </div>         <div class="form-group col-md-6">            <label>Cheque Maturity Date</label>                 <input type="text" name="cheque_maturity_dates[]" class="form-control datepicker" placeholder="Enter Bank Name" >                        </div>      </div>   </div>   <div class="col-md-12">      <div class="row">         <div class="form-group col-md-6">            <label>Cheque Bank Name</label>        <input type="text" class="form-control" name="cheque_bank_names[]" id="cheque_bank_name" placeholder="Enter Bank Name" >              </div>         <div class="form-group col-md-6">            <label>Cheque Number</label>        <input type="text" class="form-control" name="cheque_numbers[]" id="cheque_number" placeholder="Enter date">                      </div>      </div>   </div>   <div class="col-md-12">      <div class="row">         <div class="form-group col-md-6">            <label>Cheque Amount</label>        <input type="text" class="form-control" name="cheque_amounts[]" id="cheque_amount" placeholder="Enter Amount">          </div>         <div class="form-group col-md-6">            <label>Cheque Date</label>        <input type="text" class="form-control datepicker" name="cheque_dates[]" id="cheque_date" placeholder="Enter date">                      </div>      </div>   </div>   <div class="col-md-12">      <div class="row">         <div class="form-group col-md-6">        <label for="pan_no">Scan Copy</label>        <input type="file" class="form-control" name="scan_copys[]" id="" placeholder="Enter">      </div>      </div>   </div></div>');
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
    if(total > 36 || total < 36){
       alert('Total Sum of Commission Not Less Or Greater Than 36');
       $('#customer').val(0);
       $('#sum_of_commission').val(0);

       $('.commission').each(function(){
          $(this).val(0);
       });
       return false;
    }
    return true;
});
   
   
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

