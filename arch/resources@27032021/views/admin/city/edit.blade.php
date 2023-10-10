 <input type="hidden" name="id" id="" value="{{$city->id}}">
<div class="form-group">
  <label for="country">Country</label>
  <select class="form-control" name="country" required="">
    <option value="">Select Country</option>
      @foreach($countries as $id=>$country)
      <option value="{{$id}}" @if($city->country == $id) selected @endif>{{$country}}</option>
      @endforeach
  </select>
  @if($errors->has('country'))
    <p>{{ $errors->first() }}</p>
  @endif
</div>
<div class="form-group">
  <label for="country">Country</label>
  <select class="form-control" name="state" required="">
    <option value="select">Select Country</option>
      @foreach($states as $id=>$state)
      <option value="{{$id}}" @if($city->state == $id) selected @endif>{{$state}}</option>
      @endforeach
  </select>
  @if($errors->has('state'))
    <p>{{ $errors->first() }}</p>
  @endif
</div>
<div class="form-group">
  <label for="name">Name</label>
  <input type="text" class="form-control" name="name" id="Name" placeholder="Enter City" value="{{$city->name}}">
</div>

