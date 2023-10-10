
<input type="hidden" name="associate_id" id="" value="{{$associate->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="amount">Amount <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter amount">
      @if($errors->has('amount'))
        <p>{{ $errors->first('amount') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
       <label for="deposit_date">Withdraw Date <span class="text-danger">*</span></label>
       <input type="text" class="form-control datepicker" name="deposit_date" id="deposit_date" placeholder="dd-mm-yyyy" autocomplete="off">
       @if($errors->has('deposit_date'))
          <p>{{ $errors->first('deposit_date') }}</p>
       @endif
    </div>
  </div>
</div>



<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
       <label for="name">Payment Type<span class="text-danger">*</span></label>
       <select name="payment_type" class="form-control" onChange="payment(this)">
         <option value="">Select</option>
         <option value="cash">Cash</option>
         <option value="cheque">Cheque</option>
         <option value="dd">DD</option>
         <option value="NEFT">NEFT</option>
         <option value="RTGS">RTGS</option>
       </select>
       @if($errors->has('name'))
          <p>{{ $errors->first('name') }}</p>
       @endif
    </div>
    <div class="form-group col-md-6">
      <label for="remarks">Remarks<span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter remarks">
      @if($errors->has('remarks'))
        <p>{{ $errors->first('remarks') }}</p>
      @endif
    </div>
  </div>
</div>
<div class="col-md-12 payment_mode" style="display:none;">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="bank_id">Bank Name</label>
      <select name="bank_id" class="form-control" id="bank_name">
        <option value="">Select Bank</option>
        @foreach($companyBanks as $bank)
          <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
        @endforeach
      </select>

    </div>
    <div class="form-group col-md-6">
       <label for="cheque_dd_number">Cheque/DD/NEFT/RTGS Number</label>
       <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
    </div>
    <div class="form-group col-md-6">
       <label for="date">Cheque/DD Date</label>
       <input type="text" class="form-control datepicker" name="date" id="date" placeholder="Enter date" autocomplete="off">

    </div>
  </div>
</div>

<script>
$('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat: 'dd-mm-yy'
});
</script>
