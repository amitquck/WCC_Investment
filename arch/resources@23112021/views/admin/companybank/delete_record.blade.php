<input type="hidden" name="table_id" id="" value="{{$data->id}}">
<input type="hidden" name="second_table_id" id="" value="{{$data->bank_transfer_id}}">
<input type="password" name="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="off">
@if($errors->has('password'))
  <p style="color:red;">{{ $errors->first('password') }}</p>
@endif
