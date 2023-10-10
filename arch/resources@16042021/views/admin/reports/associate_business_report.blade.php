@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Business Report</li>
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
    @if(empcan('export_associate_wise_customer') || Auth::user()->login_type == 'superadmin')
      <?php /*<div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_associatewise_customer',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
      </div>*/ ?>
    @endif
  @endif
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
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Contact Details</th>
            <th scope="col">Balance</th>
            <!-- <th scope="col">Associate Commission %</th> -->
            <!-- <th scope="col">Associate Total Commission</th> -->
          </tr>
        </thead>
        <tbody>
          @if(Request::input('from_date') == null)
            @if($associate_txn)
              @foreach($associate_txn as $key => $investment)
                <tr>
                  <td>{{ ($associate_txn->currentpage()-1) * $associate_txn->perpage() + $key + 1 }}. </td>
                  <td class="text-info">
                    <a href="{{route('admin.customer_detail',encrypt($investment->customer->id))}}" data-toggle="tooltip" title="View Details">{{$investment->customer->code}}</a>
                  </td>
                  <td>{{$investment->customer->name}}</td>
                  <td>
                    <i class="material-icons text-success">phone</i>{{$investment->customer->mobile}}<br>
                    <i class="material-icons text-warning">mail</i> {{$investment->customer->email?$investment->customer->email:'N/A'}}<br>
                  </td>
                  <!-- <td>{{$investment->commission_percent}} %</td>
                  <td>₹ {{$investment->associate_per_customer_total_amount}}</td> -->
                 
                  <!-- <td><a href="{{route('admin.customermonthlyreport',['associate_id' => Request::input('id'), 'customer_id'=>$investment->customer->id,'month'=>date('m'),'year'=>date('Y')])}}"><i class="material-icons" data-toggle="tooltip" title="View Monthly Report">visibility</i></a></td> -->
                  <td>
                    @if($investment->customer->customerMainBalance() > 0)
                      <p class="text-success">₹ {{$investment->customer->customerMainBalance()}}</p>
                    @else
                      <p class="text-danger">₹ {{$investment->customer->customerMainBalance()}}</p>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
          @elseif(date('Y-m',strtotime(Request::input('from_date'))) == date('Y-m',strtotime('-1 month')))
            @if($associate_txn)
              @foreach($associate_txn as $key => $investment)
                <tr>
                  <td>{{ ($associate_txn->currentpage()-1) * $associate_txn->perpage() + $key + 1 }}. </td>
                  <td class="text-info">
                    <a href="{{route('admin.customer_detail',encrypt($investment->customer->id))}}" data-toggle="tooltip" title="View Details">{{$investment->customer->code}}</a>
                  </td>
                  <td>{{$investment->customer->name}}</td>
                  <td>
                    <i class="material-icons text-success">phone</i>{{$investment->customer->mobile}}<br>
                    <i class="material-icons text-warning">mail</i> {{$investment->customer->email?$investment->customer->email:'N/A'}}<br>
                  </td>
                  <!-- <td>{{$investment->commission_percent}} %</td>
                  <td>₹ {{$investment->associate_per_customer_total_amount}}</td> -->
                 
                  <!-- <td><a href="{{route('admin.customermonthlyreport',['associate_id' => Request::input('id'), 'customer_id'=>$investment->customer->id,'month'=>date('m'),'year'=>date('Y')])}}"><i class="material-icons" data-toggle="tooltip" title="View Monthly Report">visibility</i></a></td> -->
                  <td>
                    @if($investment->customer->customerLastMonthMainBalance() > 0)
                      <p class="text-success">₹ {{$investment->customer->customerLastMonthMainBalance()}}</p>
                    @else
                      <p class="text-danger">₹ {{$investment->customer->customerLastMonthMainBalance()}}</p>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
          @elseif(date('Y-m',strtotime(Request::input('from_date'))) == date('Y-m'))
            @if($associate_txn)
              @foreach($associate_txn as $key => $investment)
                <tr>
                  <td>{{ ($associate_txn->currentpage()-1) * $associate_txn->perpage() + $key + 1 }}. </td>
                  <td class="text-info">
                    <a href="{{route('admin.customer_detail',encrypt($investment->customer->id))}}" data-toggle="tooltip" title="View Details">{{$investment->customer->code}}</a>
                  </td>
                  <td>{{$investment->customer->name}}</td>
                  <td>
                    <i class="material-icons text-success">phone</i>{{$investment->customer->mobile}}<br>
                    <i class="material-icons text-warning">mail</i> {{$investment->customer->email?$investment->customer->email:'N/A'}}<br>
                  </td>
                  <!-- <td>{{$investment->commission_percent}} %</td>
                  <td>₹ {{$investment->associate_per_customer_total_amount}}</td> -->
                 
                  <!-- <td><a href="{{route('admin.customermonthlyreport',['associate_id' => Request::input('id'), 'customer_id'=>$investment->customer->id,'month'=>date('m'),'year'=>date('Y')])}}"><i class="material-icons" data-toggle="tooltip" title="View Monthly Report">visibility</i></a></td> -->
                  <td>
                    @if($investment->customer->customerThisMonthMainBalance() > 0)
                      <p class="text-success">₹ {{$investment->customer->customerThisMonthMainBalance()}}</p>
                    @else
                      <p class="text-danger">₹ {{$investment->customer->customerThisMonthMainBalance()}}</p>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
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

