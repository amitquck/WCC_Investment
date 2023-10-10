
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">
<input type="hidden" name="table_id" id="" value="{{$cust_int_per->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-12">
      <label for="update_customer_interest">Update Customer Interest % <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="update_customer_interest" id="customer_interest" placeholder="Enter update_customer_interest" value="{{$customer->customeractiveinterestpercent->interest_percent}}">
      @if($errors->has('update_customer_interest'))
        <p>{{ $errors->first('update_customer_interest') }}</p>
      @endif
    </div>
  </div>
</div>