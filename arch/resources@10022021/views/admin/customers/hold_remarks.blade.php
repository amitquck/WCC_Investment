<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">
<label for="hold_remarks">Hold Remarks <sup class="text-danger">*</sup></label>
<textarea type="text" name="hold_remarks" class="form-control" placeholder="Enter Remarks..." required="required" rows="3" autocomplete="off"></textarea>
@if($errors->has('hold_remarks'))
  <p style="color:red;">{{ $errors->first('hold_remarks') }}</p>
@endif