@extends('layouts.admin.default')
@section('content')
 <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Employee</a></li>
      <li class="breadcrumb-item active" aria-current="page">Employee Permission List</li>
    </ol>
  </nav>

@include('flash')	
<div>
	<h3><i class="material-icons" style="color:blue">security</i> Privileges</h3>
</div>
<div class="card card-style-1 mt-3">
    <div class="table-responsive" style="padding-top:10px;padding-left:10px;">
	<form action="{{route('admin.apply_privilege',encrypt($employee->id))}}" method="post">
  {{csrf_field()}}
    @foreach($permissions as $key => $permission)
    <input type="hidden" name="permission_id" value="{{$permission->id}}">
  	<div class="row">
		  <div class="col-md-3">{{$permission->name}}</div>
        <div class="col-md-9">
          <div class="row">
  				  @foreach(explode(',',$permission['actions']) as $privilege)
  					<div class="col-md-3">
  						<input type="checkbox" name="{{$privilege}}_{{$permission->slug}}" id="{{$privilege}}_{{$permission->slug}}" value="1" @if($employeePermissions->has($privilege.'_'.$permission->slug) && $employeePermissions->get($privilege.'_'.$permission->slug) == 1) checked="checked" @endif> 
  						<label for="{{$privilege}}_{{$permission->slug}}"> {{ucfirst(str_replace('_',' ',$privilege))}}</label>
  					</div>
      			@endforeach
          </div>
        </div>
		</div>
    <hr>
    @endforeach
	  	<div>
	      	<button type="submit" class="btn btn-md btn-success">Apply</button>
	    </div><br>
    </form>
    </div>
  </div>




@endsection