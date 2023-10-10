@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Ladger Customer Report</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  @if($customers)
    @php
        $params = [];
        if(Request::has('id'))
           $params = array_merge(['id' => Request::input('id')],$params);
    @endphp
  @endif
  @if($customers)
    @foreach($customers->take(1) as $us)
      <div class="col-md-3" style="margin-left:85%">
    <!-- <a href="" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a> -->
    <a href="{{route('admin.list_associate_ladger',['associate'=>$us->associate->code])}}" class="btn btn-success btn-sm">Associate Ladger</a>
      </div>
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
      <h6 class="text-center">{{($us->associate->name) .' ('.$us->associate->code.')' }}</h6>
    @endforeach
  @endif
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Contact Details</th>
            <th scope="col">Balance</th>
            <!-- <th scope="col">Associate Commission %</th> -->
            <!-- <th scope="col">Associate Total Commission</th> -->
          </tr>
        </thead>
        <tbody>
          @if($customers)
            @foreach($customers as $key => $customer)
              <tr>
                <td>{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>
                <td><a href="{{route('admin.customer_detail',encrypt($customer->customer->id))}}" data-toggle="tooltip" title="View Details">{{$customer->customer->code}}</a></td>
                <td>{{$customer->customer->name}}</td>
                <td>
                  <i class="material-icons text-primary">phone</i> {{$customer->customer->mobile?$customer->customer->mobile:'N/A'}}<br>
                  <i class="material-icons text-danger">mail</i> {{$customer->customer->email?$customer->customer->email:'N/A'}}
                </td>
                <td>
                  @if($customer->associate_customer_balance > 0)
                    <p class="text-success">₹ {{$customer->associate_customer_balance}}</p>
                  @else
                    <p class="text-danger">₹ {{$customer->associate_customer_balance}}</p>
                  @endif
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
@php
  $params = [];
    if(Request::has('associate_id'))
      $params = array_merge(['associate_id' => Request::input('associate_id')],$params);
@endphp
{{$customers->appends($params)->links()}}
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

