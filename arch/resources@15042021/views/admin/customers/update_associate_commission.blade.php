
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">
<input type="hidden" name="associate_id" id="" value="{{$associate->id}}">
<input type="hidden" name="table_id" id="" value="{{$associate_comm_per->id}}">

<div class="col-md-12">
  <div class="row">
    <div class="form-group col-md-12">
      <label for="update_associate_commission">Update Associate Commission % <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="update_associate_commission" id="customer_interest" placeholder="Enter update_associate_commission" value="{{$associate_comm_per->commission_percent}}">
      @if($errors->has('update_associate_commission'))
        <p>{{ $errors->first('update_associate_commission') }}</p>
      @endif
    </div>
  </div>
</div>