
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">

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
       <label for="deposit_date">Deposit Date <span class="text-danger">*</span></label>
       <input type="date" class="form-control" name="deposit_date" id="deposit_date" placeholder="dd/mm/yyyy">
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
      <label for="bank_name">Bank Name<span class="text-danger">*</span></label>
      <select name="bank_id" class="form-control">
         <option value="">Select</option>
         @foreach($banks as $bank)
           <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
         @endforeach
       </select>
      @if($errors->has('bank_id'))
      <p>{{ $errors->first('bank_id') }}</p>
      @endif
      
    </div>
    <div class="form-group col-md-6">
       <label for="cheque_dd_number">Cheque/DD/NEFT/RTGS Number<span class="text-danger">*</span></label>
       <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
    </div>
    <div class="form-group col-md-6">
       <label for="date">Cheque/DD Date<span class="text-danger">*</span></label>
       <input type="date" class="form-control" name="date" id="date" placeholder="Enter date">
      
    </div>
  </div>
</div>
