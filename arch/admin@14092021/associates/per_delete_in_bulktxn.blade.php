<input type="hidden" name="associate_id" id="" value="{{$asso_txn->associate_id}}">
<input type="hidden" name="table_id" id="" value="{{$asso_txn->id}}">
<input type="hidden" name="respective_table_id" id="" value="{{$asso_txn->respective_table_id}}">
<input type="password" name="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="off">
@if($errors->has('password'))
  <p style="color:red;">{{ $errors->first('password') }}</p>
@endif