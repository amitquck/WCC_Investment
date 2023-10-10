
<input type="hidden" name="associate_id" id="" value="{{$associate->id}}">
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-6">
      <label for="associate_withdrawl">Associate Commission % <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="associate_withdrawl" id="associate_withdrawl" placeholder="Enter associate_withdrawl">
      @if($errors->has('associate_withdrawl'))
        <p>{{ $errors->first('associate_withdrawl') }}</p>
      @endif
    </div>
    <div class="form-group col-md-6">
       <label for="start_date">Applicable Date <span class="text-danger">*</span></label>
       <input type="text" class="form-control datepicker" name="start_date" id="start_date" placeholder="dd-mm-yyyy">
       @if($errors->has('start_date'))
          <p>{{ $errors->first('start_date') }}</p>
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