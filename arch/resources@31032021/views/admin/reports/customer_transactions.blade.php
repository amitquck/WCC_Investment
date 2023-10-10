@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Transactions List</li>
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
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_customer_transactions',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="from_date">From Date </label>
        <input type="text" name="from_date" class="form-control datepicker" placeholder="From Date">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date </label>
        <input type="text" name="to_date" class="form-control datepicker" placeholder="To Date">
      </div>
      <p>OR</p>
      <div class="col-md-3">
        <label for="customer">Customer <sup class="text-danger">*</sup><a href="javascript:void(0);" class="text-danger" data-toggle="tooltip" data-placement="top" title="Only Full Code Accepted"><i class="material-icons">info</i></a></label>
        <input type="text" name="customer" class="form-control" placeholder="Code">
      </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary">
            search
        </button>
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
            <th>Code</th>
            <th>Name</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Running Balnace</th>
            <th scope="col">Credit/Debit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($transactions)
            @php $total_credit = $total_debit = $running_balance = 0; @endphp
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
                <tr>
                  <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>

                  <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($transaction->customers->id))}}" data-toggle="tooltip" title="View Details">{{($transaction->customers->code)}}</td>
                  <td>{{ucwords($transaction->customers->name)}}</td>
                  
                  
                  @if($transaction->cr_dr == 'cr')
                    <td class="text-success">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                  @else
                    <td class="text-success">₹ 0.00</td>
                  @endif
                  
                  @if($transaction->cr_dr == 'dr')
                    <td class="text-danger">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                  @else
                    <td class="text-danger">₹ 0.00</td>
                  @endif
                  
                   @if($transaction->cr_dr == 'cr')
                    @php
                        $running_balance += $transaction->amount;
                    @endphp
                      @else
                    @php
                        $running_balance -= $transaction->amount;
                    @endphp
                  @endif

                  <td class="text-primary">₹ {{number_format($running_balance,2)}}</td>
                  
                  
                  <td>{{$transaction->deposit_date?Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y'):''}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
                 
                </tr>
              @endforeach
              <tr><td></td>
                <td colspan="3"><span class="text-primary">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Total Debit :  ₹ {{$total_debit}}</span>   </td>
              </tr>
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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

<script type="text/javascript">
$('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat : "dd-mm-yy"
});
// $.fn.datepicker.defaults.format = "dd/mm/yyyy";
</script>
@endsection




         
