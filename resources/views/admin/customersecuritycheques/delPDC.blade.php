
<input type="hidden" name="id" id="" value="{{$pdcCheque->id}}">
<input type="hidden" name="customer_id" id="" value="{{$pdcCheque->customer_id}}">
<input type="password" name="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="off">
@if($errors->has('password'))
  <p style="color:red;">{{ $errors->first('password') }}</p>
@endif