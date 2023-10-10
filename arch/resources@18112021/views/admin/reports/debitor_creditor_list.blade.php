@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Debitor/Creditor List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  @if($transactions)
    @php
        $params = [];
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
        if(Request::has('customer'))
           $params = array_merge(['customer' => Request::input('customer')],$params);
    @endphp
    @if(empcan('export_debitor_creditor') || Auth::user()->login_type == 'superadmin')
      <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_debitor_creditor_list',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
      </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="from_date">From Date </label>
        <input type="text" name="from_date" class="form-control datepicker" placeholder="From Date" autocomplete="off">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date </label>
        <input type="text" name="to_date" class="form-control datepicker" placeholder="To Date" autocomplete="off">
      </div>
      <p>OR</p>
      <div class="col-md-3">
        <label for="customer">Customer </label>
        <input type="text" name="name" class="form-control" placeholder="Customer Search" id="cust_search">
        <input type="hidden" id="hidden_cust_search" name="customer">
      </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
        </button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">

    <div class="table-responsive">
      @if(Request::input('to_date'))
        <div style="background-color: blue;">
          <h6 class="text-center text-light">Debitor/Creditor List As on Date {{Carbon\Carbon::parse(Request::input('to_date'))->format('j-M-Y')}}</h6>
        </div>
      @endif
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Customer Code</th>
            <th>Customer Name</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <!-- <th scope="col">Credit/Debit Date</th> -->
          </tr>
        </thead>
        <tbody>
            @if($transactions)
              @php
                $total_credit = $total_debit = $running_balance = 0;
              @endphp
              @if(Request::input('from_date') != null && Request::input('to_date') != null)
              @php
                $from_date = date('Y-m-d',strtotime(Request::input('from_date')));
                $to_date = date('Y-m-d',strtotime(Request::input('to_date')));
              @endphp
              @else
              @php $from_date = $to_date = null; @endphp
              @endif
              @foreach($transactions as $key => $transaction)
                @if($transaction->cr_dr == 'cr')
                  @php
                    $total_credit += $transaction->amount;
                  @endphp
                @else
                  @php
                    $total_debit += $transaction->amount;
                  @endphp
                @endif
                @if($transaction->customers != NULL)
                    <tr>
                        <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>
                        <td class="text-info"><a @if(empcan('view_detail_debitor_creditor') || Auth::user()->login_type == 'superadmin') href="{{route('admin.customer_detail',encrypt($transaction->customers->id))}}" @endif data-toggle="tooltip" title="View Details">{{($transaction->customers->code)}}</td>
                        <td>{{ucwords($transaction->customers->name)}}</td>

                        @if($from_date != null && $to_date != null)
                            @if($transaction->customer_current_balance($transaction->customer_id,$from_date,$to_date) < 0)
                                <td class="text-danger">₹ {{$transaction->customer_current_balance($transaction->customer_id,$from_date,$to_date)}}</td>
                                <td class="text-success">₹ 0.00</td>
                            @else
                                <td class="text-danger">₹ 0.00</td>
                                <td class="text-success">₹ {{$transaction->customer_current_balance($transaction->customer_id)}}</td>
                            @endif
                        @else
                            @if($transaction->customer_current_balance($transaction->customer_id) < 0)
                                <td class="text-danger">₹ {{$transaction->customer_current_balance($transaction->customer_id)}}</td>
                                <td class="text-success">₹ 0.00</td>
                            @else
                                <td class="text-danger">₹ 0.00</td>
                                <td class="text-success">₹ {{$transaction->customer_current_balance($transaction->customer_id)}}</td>
                            @endif
                        @endif

                        @php /*<td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-M-Y')}}</td>*/ @endphp
                    </tr>
                @endif
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
  if(Request::has('from_date'))
     $params = array_merge(['from_date' => Request::input('from_date')],$params);
  if(Request::has('to_date'))
     $params = array_merge(['to_date' => Request::input('to_date')],$params);
  if(Request::has('customer'))
     $params = array_merge(['customer' => Request::input('customer')],$params);
@endphp
  @if($transactions)
    {{$transactions->appends($params)->links()}}
  @endif
@endsection


@section('page_js')
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">

$('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat : "dd-mm-yy"
});

$('#cust_search').autocomplete({
    source:"{{route('admin.customertransaction')}}",
    minLength: 2,
    select:function(event,ui){
        $('#cust_search').val(ui.item.name);
        $('#hidden_cust_search').val(ui.item.code);
    }
});
// $.fn.datepicker.defaults.format = "dd-mm-yyyy";
</script>
@endsection





