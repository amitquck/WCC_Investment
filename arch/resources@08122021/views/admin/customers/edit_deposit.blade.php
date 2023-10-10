
<input type="hidden" name="customer_id" id="" value="{{$customer->customer_id}}">
<input type="hidden" name="table_id" id="" value="{{$customer->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="customer_deposit">Amount <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="customer_deposit" id="customer_interest" placeholder="Enter customer_deposit" value="{{$customer->amount}}">
      @if($errors->has('customer_deposit'))
        <p>{{ $errors->first('customer_deposit') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
       <label for="deposit_date">Deposit Date </label>
       <input type="text" class="form-control datepickerss" name="deposit_date" id="deposit_date" placeholder="dd/mm/yyyy" value="<?php echo date('j-m-Y',strtotime($customer->deposit_date));?>">
       @if($errors->has('deposit_date'))
          <p>{{ $errors->first('deposit_date') }}</p>
       @endif
    </div>
  </div>
</div>

<script type="text/javascript">
    $('.datepickerss').datepicker({
        startDate: '-3d',
        dateFormat: 'dd-mm-yy'
    });
</script>
