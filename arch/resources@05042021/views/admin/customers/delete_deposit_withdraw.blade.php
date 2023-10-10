<input type="hidden" name="customer_id" id="" value="{{$cust_txn->customer_id}}">
<input type="hidden" name="table_id" id="" value="{{$cust_txn->id}}">
<input type="hidden" name="respective_table_id" id="" value="{{$cust_txn->respective_table_id}}">
<input type="password" name="password" class="form-control" placeholder="Enter Password" required="required" autocomplete="off">
@if($errors->has('password'))
  <p style="color:red;">{{ $errors->first('password') }}</p>
@endif