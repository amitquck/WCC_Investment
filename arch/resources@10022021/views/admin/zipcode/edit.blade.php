<input type="hidden" name="id" id="" value="{{$zipcode->id}}">
  <div class="form-group">
    <label for="country">Country<span class="text-danger">*</span></label>
      <select class="form-control" name="country" required="">
        <option value="">Select Country</option>

        @foreach($countries as $id=>$country)
        <option value="{{$id}}" @if($zipcode->country == $id) selected @endif>{{$country}}</option>
        @endforeach
      </select>
  </div> 
  <div class="form-group">
    <label for="state">State<span class="text-danger">*</span></label>
      <select class="form-control" name="state" required="">
        <option value="">Select State</option>

        @foreach($states as $id=>$state)
        <option value="{{$id}}" @if($zipcode->state == $id) selected @endif>{{$state}}</option>
        @endforeach
      </select>
  </div> 
  <div class="form-group">
    <label for="city">City<span class="text-danger">*</span></label>
      <select class="form-control" name="city" required="">
        <option value="">Select City </option>

        @foreach($cities as $id=>$city)
        <option value="{{$id}}" @if($zipcode->city == $id) selected @endif>{{$city}}</option>
        @endforeach
      </select>
  </div>
  <div class="form-group">
    <label for="name">Zipcode<span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter zipcode" value="{{$zipcode->zipcode}}">
  </div>

