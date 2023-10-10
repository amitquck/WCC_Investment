
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="customer_interest">Customer Interest % <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="customer_interest" id="customer_interest" placeholder="Enter customer_interest">
      @if($errors->has('customer_interest'))
        <p>{{ $errors->first('customer_interest') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
       <label for="applicable_date">Applicable Date <span class="text-danger">*</span></label>
       <input type="text" class="form-control datepicker" name="applicable_date" id="applicable_date" placeholder="dd-mm-yyyy">
       @if($errors->has('applicable_date'))
          <p>{{ $errors->first('applicable_date') }}</p>
       @endif
    </div>
  </div>
</div>

<script type="text/javascript">
  $('.datepicker').datepicker({
  startDate: '-3d',
  dateFormat: 'dd-mm-yy'
});
</script>