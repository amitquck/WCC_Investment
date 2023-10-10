@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Entry Locks List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  
<form action="{{route('admin.store_entrylocks')}}" method="post">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <select class="form-control" name="year">
            <option value="">Select Year</option>
            @for($i=date('Y'); $i>=2019; $i--)
                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
            @endfor
        </select>
      </div>
      <div class="col-md-1 mt-2">
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Month</th>
            <th scope="col">Year</th>
            <th scope="col">Status</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
          @if($locks->count()>0)
            @foreach($locks as $key  => $lock)
              <tr>
                <td>{{($locks->currentpage()-1)*$locks->perpage()+$key+1}}.</td>
                <td>{{$lock->month}}</td>
                <td>{{$lock->year}}</td>
                <td>
                  <a href="{{url('admin/entrylockStatus/'.$lock->id)}}"  class="btn btn-link btn-icon bigger-130 text-success @if($lock->status) @else text-danger @endif" data-toggle="tooltip" title="@if($lock->status) Make Entry Lock @else Make Entry Unlock @endif"><i class="material-icons">@if($lock->status)lock_open @else lock @endif</i></a>
                </td>
                <td>{{Carbon\Carbon::parse($lock->created_at)->format('j-m-Y h:i A')}}</td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

{{$locks->links()}}
@endsection


@section('page_js')
@endsection




         
