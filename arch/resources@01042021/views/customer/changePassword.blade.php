@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Change Password</li>
    </ol>
  </nav>
   @include('flash')
<div class="card card-style-1 mt-3">  
  <div class="modal-header d-flex align-items-center bg-primary text-white">
    <h6 class="modal-title mb-0" id="addUserLabel">Change Password</h6>
  </div>
  <div style="margin-left:10px;margin-top:20px;">
    <form action="{{route('admin.changePassword')}}" method="post">
    {{ csrf_field()}}
        <div class="col-md-12">
          <div class="form-group">
            <label for="old_password">Old Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Enter old_password">
            @if($errors->has('old_password'))
              <p>{{ $errors->first('old_password') }}</p>
            @endif
          </div>

          <div class="form-group">
            <label for="new_password">New Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new_password">
            @if($errors->has('new_password'))
              <p>{{ $errors->first('new_password') }}</p>
            @endif
          </div>

          <div class="form-group">
            <label for="password">Confirm Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            @if($errors->has('password'))
              <p>{{ $errors->first('password') }}</p>
            @endif
          </div>

          <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form><br>
  </div>
</div>
@endsection