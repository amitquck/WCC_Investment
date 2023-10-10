@extends('layouts.admin.default')

@section('content')

<!-- <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<style type="text/css">
  .dataTables_filter{
    display: none;
  }
</style> -->
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">{{ucwords($q)}} Wise Total Business List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @php
    $params = [];
    if(Request::has('q'))
       $params = array_merge(['q' => Request::input('q')],$params);
  @endphp
  <div class="col-md-3" style="margin-left:85%">
    <a href="{{route('admin.excel_business_category',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
  </div>

  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0" id="myTable">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            @if($q == 'customer')
              <th>Customer Code</th>
            @elseif($q == 'associate')
              <th>Associate Code</th>
            @endif
           
            <th scope="col">{{ucwords($q)}} Name</th>
            <th scope="col">Business </th>
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
                    @if($txn->cust_balance > 0)
                      <a href="{{route('admin.transactions',['customer'=>$txn->customers->code])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-success">₹ {{$txn->cust_balance}}</a>
                    @else
                      <a href="{{route('admin.transactions',['customer'=>$txn->customers->code])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-danger">₹ {{$txn->cust_balance}}</a>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'associate')
            @if($associates)
              @foreach($associates as $key => $associate)
                
                  <tr>
                    <td>{{($associates->currentpage()-1)*$associates->perpage()+$key+1}}.</td>
                    <td>
                      <a href="{{route('admin.associate_view',$associate->associate_id)}}" class="text-info" data-toggle="tooltip" title="View Detail">{{ucwords(getUser($associate->associate_id)->code)}}</a>
                    </td>
                    <td>{{ucwords(getUser($associate->associate_id)->name)}}</td>
                    <td>
                    @if($associate->cust_balance>0)
                      <a href="{{route('admin.associate_business_report',['id'=>$associate->associate_id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-success"> ₹ {{$associate->cust_balance}}</a>
                    @else
                      <a href="{{route('admin.associate_business_report',['id'=>$associate->associate_id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-danger"> ₹ {{$associate->cust_balance}}</a>
                    @endif      
                    </td>               
                  </tr>
                
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'state')
            @if($cust_txn->count()>0)
              @foreach($cust_txn as $key => $txn)
                @if($txn->getState != '')
                  <tr>
                    <td>{{($cust_txn->currentpage()-1)*$cust_txn->perpage()+$key+1}}.</td>
                    <td>{{ucwords($txn->getState->name)}}</td>
                    <td>
                      @if($txn->cust_balance > 0)
                        <a href="{{route('admin.state_business',['state_id'=>$txn->getState->id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-success"> ₹ {{$txn->cust_balance}}</a>
                      @else
                        <a href="{{route('admin.state_business',['state_id'=>$txn->getState->id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-danger"> ₹ {{$txn->cust_balance}}</a>
                      @endif
                    </td>
                  </tr>
                @endif
              @endforeach
            @else
              <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif

          @elseif($q == 'city')
            @if($cities->count()>0)
              @foreach($cities as $key => $city)
                @if($city->getCity != '')
                  <tr>
                    <td>{{($cities->currentpage()-1)*$cities->perpage()+$key+1}}.</td>
                    <td>{{ucwords($city->getCity->name)}}</td>
                    <td>
                      @if($city->cust_balance > 0)
                        <a href="{{route('admin.city_business',['city_id'=>$city->getCity->id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-success"> ₹ {{$city->cust_balance}}</a>
                      @else
                        <a href="{{route('admin.city_business',['city_id'=>$city->getCity->id])}}" data-toggle="tooltip" title="View Ladger Balance" class="text-danger"> ₹ {{$city->cust_balance}}</a>
                      @endif
                    </td>
                  </tr>
                @endif
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
@elseif($q == 'associate')
  {{$associates->appends($params)->links()}}
@elseif($q == 'city')
  {{$cities->appends($params)->links()}}
@elseif($q == 'state')
  {{$cust_txn->appends($params)->links()}}
@endif
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
//  $(document).ready( function () {
//     $('#myTable').DataTable({
        
//         "pageLength": 20,
//         "pagingType": "simple_numbers",
//         "searching": false,
//         "ordering": false,
//     });
// } );
</script>
@endsection




         
