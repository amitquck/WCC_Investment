
<input type="hidden" name="customer_id" id="" value="{{$customer_withdraw->customer_id}}">
<input type="hidden" name="table_id" id="" value="{{$customer_withdraw->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="customer_withdraw">Amount <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="customer_withdraw" id="customer_interest" placeholder="Enter customer_withdraw" value="{{$customer_withdraw->amount}}">
      @if($errors->has('customer_withdraw'))
        <p>{{ $errors->first('customer_withdraw') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
       <label for="deposit_date">Deposit Date </label>
       <input type="date" class="form-control" name="deposit_date" id="deposit_date" placeholder="dd/mm/yyyy" value="{{$customer_withdraw->deposit_date}}">
       @if($errors->has('deposit_date'))
          <p>{{ $errors->first('deposit_date') }}</p>
       @endif
    </div>
  </div>
</div>