@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Wise Customer Report</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  @if($associate_txn)
    @php
        $params = [];
        if(Request::has('id'))
           $params = array_merge(['id' => Request::input('id')],$params);
    @endphp
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_associatewise_customer',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
 <div class="col-md-12">
    <form action="{{route('admin.associatecus')}}" method="get">
      <div class="row">
        <div class="col-md-3">
          <tr class="column-filter" id="filter">
            <th>
              <!-- <a href="#" data-toggle="popover" title="Associate Search Hint" data-content="Name Search"><i class="material-icons text-danger icon-xs">info_outline</i></a> -->
              <input type="text" class="form-control" placeholder="Search By Associate Name" name="" id="cust_invest" autocomplete="off"  data-toggle="tooltip" title="Search by Associate Name"><input type="hidden" name="id" id="hidden_cust_invest">
            </th>
          </tr>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-primary btn-sm"><i class="material-icons">search</i>
            
          </button>
        </div>
      </div>
    </form>
  </div>
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
        @if($associate_txn)
          @foreach($associate_txn->take(1) as $us)
            <h6 class="text-center">{{($us->associate->name) .' ('.$us->associate->code.')' }}</h6>
          @endforeach
        @endif
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Name (Code)</th>
            <th scope="col">Details</th>
            <th scope="col">Action</th>
            <!-- <th scope="col">Associate Commission %</th> -->
            <!-- <th scope="col">Associate Total Commission</th> -->
          </tr>
        </thead>
        <tbody>
          @if($associate_txn)
            @foreach($associate_txn as $key => $investment)
              <tr>
                <td>{{ ($associate_txn->currentpage()-1) * $associate_txn->perpage() + $key + 1 }}. </td>
                <td>{{$investment->customer->name.' ('.$investment->customer->code.')'}}</td>
                <td>
                  <i class="material-icons text-success">phone</i>{{$investment->customer->mobile}}<br>
                  <i class="material-icons text-warning">mail</i> {{$investment->customer->email?$investment->customer->email:'N/A'}}<br>
                </td>
                <!-- <td>{{$investment->commission_percent}} %</td>
                <td>₹ {{$investment->associate_per_customer_total_amount}}</td> -->
               
                <td><a href="{{route('admin.customermonthlyreport',['associate_id' => Request::input('id'), 'customer_id'=>$investment->customer->id,'month'=>date('m'),'year'=>date('Y')])}}"><i class="material-icons" data-toggle="tooltip" title="View Monthly Report">visibility</i></a></td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
@php
  $params = [];
    if(Request::has('id'))
      $params = array_merge(['id' => Request::input('id')],$params);
@endphp
{{$associate_txn->appends($params)->links()}}
@endsection

@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $('#cust_invest').autocomplete({
    source:"{{route('admin.associatecustomer')}}",
    minLength: 2,
    select:function(event,ui){
      $('#cust_invest').val(ui.item.name);
      $('#hidden_cust_invest').val(ui.item.id);

    }
  });
  </script>
  <script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();   
});
</script>
  @endsection

