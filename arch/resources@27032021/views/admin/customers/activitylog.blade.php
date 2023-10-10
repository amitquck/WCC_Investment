@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customer</a></li>
      <li class="breadcrumb-item active" aria-current="page">Activity Log List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Created By</th>
            <th scope="col">Statement</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($activitylogs->count()>0)
              @foreach($activitylogs as $key => $activitylog)
                <tr>
                  <td>{{($activitylogs->currentpage()-1)*$activitylogs->perpage()+$key+1}}.</td>
                  <td class="text-primary">{{ucwords($activitylog->user->name)}}</td>
                  <td>{{$activitylog->statement}}.</td>
                  <td>{{Carbon\Carbon::parse($activitylog->created_at)->format('j-m-Y h:i:s A')}}</td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
  {{$activitylogs->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


@endsection




         
