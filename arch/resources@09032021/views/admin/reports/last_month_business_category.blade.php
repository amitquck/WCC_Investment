@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ucwords($q)}} Wise This Month Business List</li>
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
                  <td>{{ucwords($txn->customers->name).' ('.$txn->customers->code.')'}}</td>
                  <td class="text-success">₹ {{$txn->customer_wise_last_month_business}}</td>
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
                    <td class="text-success">₹ {{$state->state_wise_last_month_business}}</td>
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
                    <td class="text-success">₹ {{$city->city_wise_last_month_business}}</td>
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
@endphp
@if($q == 'customer')
  {{$cust_txn->appends($params)->links()}}
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




         
