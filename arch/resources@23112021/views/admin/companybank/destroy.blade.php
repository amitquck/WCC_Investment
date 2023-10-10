<input type="hidden" name="bank_id" id="" value="{{$bank->id}}">
<input type="password" name="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="off">
@if($errors->has('password'))
  <p style="color:red;">{{ $errors->first('password') }}</p>
@endif
