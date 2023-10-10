@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Employee List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="d-flex">
    <div class="list-with-gap">
     <a href="{{route('admin.addEmployeepage')}}" class="btn btn-primary has-icon"><i class="material-icons mr-2">add_circle_outline</i>Add Employee</a>
     
    </div>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Details</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
            @if($employees->count()>0)
              @foreach($employees as $key => $employee)
              <tr>
                <td>{{($employees->currentpage()-1)*$employees->perpage()+$key+1}}.</td>
                <td>{{$employee->code}}</td>
                <td>{{$employee->name}}</td>
                <td><i class="material-icons text-primary">phone</i> {{$employee->mobile}}<br>
                  <i class="material-icons text-danger">mail</i> {{$employee->email}}</td>
                <td class="text-primary">
                  {{Carbon\Carbon::parse($employee->created_at)->format('j-m-Y')}}
                </td>
                <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{route('admin.privilege',encrypt($employee->id))}}" data-id="{{$employee->id}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary" data-toggle="tooltip" data-placement="top" title="Add Privileges"  data-id="{{$employee->id}}"><i class="material-icons" style="color:blue">security</i></a>
                  <a href="{{route('admin.editemployee',encrypt($employee->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-info" data-toggle="tooltip" title="Edit Commission"  data-id="{{$employee->id}}"><i class="material-icons">edit</i></a>
                  <a href="{{url('admin/employee/employeestatus/'.encrypt($employee->id))}}"  class="btn btn-link btn-icon bigger-130 text-success @if($employee->status) @else text-danger @endif" data-toggle="tooltip" title="@if($employee->status) Make Inactive @else Make Active @endif">
                    <i class="material-icons">@if($employee->status)lock @else lock_open @endif</i>
                    </a>
                    <form action="{{route('admin.employeeDelete',$employee->id)}}" id="delete-form-{{$employee->id}}" method="post" >
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                    <a href="{{route('admin.employeeDelete',encrypt($employee->id))}}"
                        class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" 
                        title="Delete"><i class="material-icons">delete_outline</i></a>
                </div>
                </td>
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
         
{{$employees->links()}}

@endsection




         
