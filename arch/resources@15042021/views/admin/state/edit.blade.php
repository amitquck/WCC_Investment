 <input type="hidden" name="id" id="" value="{{$state->id}}">
 <div class="form-group">
                  <label for="country">Country<span class="text-danger">*</span></label>
                  <select class="form-control" name="country" required="">
                    <option value="">Select Country</option>

                     @foreach($countries as $id=>$country)
                      <option value="{{$id}}" @if($state->country == $id) selected @endif>{{$country}}</option>
                     @endforeach
                  </select>
                  @if($errors->has('country'))
                    <p>{{ $errors->first() }}</p>
                  @endif
                </div>
<div class="form-group">
  <label for="name">Name<span class="text-danger">*</span></label>
  <input type="text" class="form-control" name="name" id="Name" placeholder="Enter State" value="{{$state->name}}">
</div>

