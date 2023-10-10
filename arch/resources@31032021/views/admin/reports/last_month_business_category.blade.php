@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ucwords($q)}} Wise Last Month Business List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @php
    $params = [];
    if(Request::has('q'))
       $params = array_merge(['q' => Request::input('q')],$params);
    if(Request::has('from_date'))
       $params = array_merge(['from_date' => Request::input('from_date')],$params);
    if(Request::has('to_date'))
       $params = array_merge(['to_date' => Request::input('to_date')],$params);
  @endphp
  <div class="col-md-3" style="margin-left:85%">
    <a href="{{route('admin.excel_last_month_business_category',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
  </div>

  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            @if($q == 'customer')
              <th>Customer Code</th>
            @elseif($q == 'associate')
              <th>Associate Code</th>
            @endif
            <th scope="col">{{ucwords($q)}} Name</th>
            <th scope="col">Business</th>
          </tr>
        </thead>
        <tbody>
          @if($q == 'customer')
            @if($cust_txn->count()>0)
              @foreach($cust_txn as $key => $txn)
                <tr>
                  <td>{{($cust_txn->currentpage()-1)*$cust_txn->perpage()+$key+1}}.</td>
                  <td><a href="{{route('admin.customer_detail',encrypt($txn->customers->id))}}" class="text-primary" data-toggle="tooltip" title="View Detail">{{$txn->customers->code}}</a></td>
                  <td>{{ucwords($txn->customers->name)}}</td>
                  <td>
                    @if($txn->customer_wise_last_month_business > 0)
                      <p class="text-success"> ₹ {{$txn->customer_wise_last_month_business}}</p>
                    @else
                      <p class="text-danger"> ₹ {{$txn->customer_wise_last_month_business}}</p>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'associate')
            @if($associates->count()>0)
              @foreach($associates as $key => $associate)
                  <tr>
                    <td>{{($associates->currentpage()-1)*$associates->perpage()+$key+1}}.</td>
                    <td><a href="{{route('admin.associate_view',$associate->associate->id)}}" class="text-info" data-toggle="tooltip" title="View Detail">{{ucwords($associate->associate->code)}}</a></td>
                    <td>{{ucwords($associate->associate->name)}}</td>
                    <td>
                      @if($associate->associate_wise_last_month_business > 0)
                        <p class="text-success"> ₹ {{$associate->associate_wise_last_month_business}}</p>
                      @else
                        <p class="text-danger"> ₹ {{$associate->associate_wise_last_month_business}}</p>
                      @endif
                    </td>
                  </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'state')
            @if($states->count()>0)
              @foreach($states as $key => $state)
                  <tr>
                    <td>{{($states->currentpage()-1)*$states->perpage()+$key+1}}.</td>
                    <td>{{ucwords($state->name)}}</td>
                    <td>
                      @if($state->state_wise_last_month_business > 0)
                        <p class="text-success"> ₹ {{$state->state_wise_last_month_business}}</p>
                      @else
                        <p class="text-danger"> ₹ {{$state->state_wise_last_month_business}}</p>
                      @endif
                    </td>
                  </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'city')
            @if($cities->count()>0)
              @foreach($cities as $key => $city)
                  <tr>
                    <td>{{($cities->currentpage()-1)*$cities->perpage()+$key+1}}.</td>
                    <td>{{ucwords($city->name)}}</td>
                    <td>
                      @if($city->city_wise_last_month_business > 0)
                        <p class="text-success"> ₹ {{$city->city_wise_last_month_business}}</p>
                      @else
                        <p class="text-danger"> ₹ {{$city->city_wise_last_month_business}}</p>
                      @endif
                    </td>
                  </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif
          @endif
        </tbody>
      </table>
    </div>
  </div>
@php
$params = [];
if(Request::has('q'))
   $params = array_merge(['q' => Request::input('q')],$params);
if(Request::has('from_date'))
   $params = array_merge(['from_date' => Request::input('from_date')],$params);
if(Request::has('to_date'))
   $params = array_merge(['to_date' => Request::input('to_date')],$params);
@endphp
@if($q == 'customer')
  {{$cust_txn->appends($params)->links()}}
@elseif($q == 'associate')
  {{$associates->appends($params)->links()}}
@elseif($q == 'city')
  {{$cities->appends($params)->links()}}
@elseif($q == 'state')
  {{$states->appends($params)->links()}}
@endif
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection




         
