
<input type="hidden" name="customer_id" id="" value="{{$customer->id}}">
<label for="no_interest_remarks">No Interest Remarks <sup class="text-danger">*</sup></label>
<textarea type="text" name="no_interest_remarks" class="form-control" placeholder="Enter Remarks..." required="required" rows="3" autocomplete="off"></textarea>
@if($errors->has('no_interest_remarks'))
  <p style="color:red;">{{ $errors->first('no_interest_remarks') }}</p>
@endif