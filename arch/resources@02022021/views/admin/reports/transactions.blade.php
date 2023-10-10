@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Transactions List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($transactions->count()>0)
    @php
        $params = [];
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
        if(Request::has('payment_type'))
           $params = array_merge(['payment_type' => Request::input('payment_type')],$params);
    @endphp
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_all_transactions',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="from_date">From Date</label>
        <input type="date" name="from_date" class="form-control" placeholder="From Date">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date</label>
        <input type="date" name="to_date" class="form-control" placeholder="To Date">
      </div>
      <div class="col-md-3">
       <label for="name">Search Payment Mode</label>
       <select name="payment_type" class="form-control" onChange="payment(this)">
         <option value="">Select</option>
         <option value="cash">Cash</option>
         <option value="cheque">Cheque</option>
         <option value="dd">DD</option>
         <option value="NEFT">NEFT</option>
         <option value="RTGS">RTGS</option>
       </select>
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
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Transaction</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
            @php $total_credit = $total_debit = 0; @endphp
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

                  @if($transaction->associate_id)
                    <td>Associate Name : {{ucwords($transaction->associate->name)}}<br>
                      Code : <strong class="text-primary">{{$transaction->associate->code}}</strong></td>
                  @else
                    <td>Customer Name : {{ucwords($transaction->customers->name)}}<br>
                      Code : <strong class="text-info">{{$transaction->customers->code}}</strong>
                    </td>
                  @endif
                  
                  @if($transaction->transaction_type == 'deposit')
                    <td class="text-success">₹ {{($transaction->transaction_type == 'interest')?$transaction->sum_monthly_interest:$transaction->amount}}</td>
                  @else
                    <td class="text-danger">₹ {{($transaction->transaction_type == 'interest')?$transaction->sum_monthly_interest:$transaction->amount}}</td>
                  @endif
                  
                  @if($transaction->transaction_type == 'deposit')
                    <td class="text-success">{{ucwords($transaction->transaction_type)}}</td>
                  @else
                    <td class="text-danger">{{ucwords($transaction->transaction_type)}}</td>
                  @endif
                  
                  <td>
                    @if($transaction->payment_type == 'null')
                      {{'N/A'}}
                    @else
                    {{$transaction->payment_type}}
                    @endif
                  </td>
                  <td>
                    @if($transaction->payment_type == 'cash' || $transaction->payment_type == 'null')
                      {{'Cash Payment'}}
                    @else
                      <strong>Bank Name : </strong>{{$transaction->bankname?$transaction->bankname->bank_name:'N/A'}}
                    <br>
                      <strong>Cheque/DD Number : </strong>{{$transaction->cheque_dd_numbe?$transaction->cheque_dd_number:'N/A'}}
                    @endif
                  </td>
                  <td>
                    @php $deposit_date = $transaction->deposit_date?Carbon\Carbon::parse($transaction->deposit_date)->format('j-M-Y'):''; @endphp
                    {{$deposit_date}}
                  </td>
                  <td>
                    @php $created_at = Carbon\Carbon::parse($transaction->created_at)->format('j-M-Y'); @endphp
                  {{$created_at}}</td>
                 
                </tr>
              @endforeach
              <tr><td></td>
                <td colspan="3"><span class="text-primary">Total Deposit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Total Withdrawl :  ₹ {{$total_debit}}</span>   </td>
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
  if(Request::has('payment_type'))
     $params = array_merge(['payment_type' => Request::input('payment_type')],$params);
@endphp
{{$transactions->appends($params)->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!-- <script>
$('.AddTransationForm').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.associateAddTransationForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#add-transaction-container').html(data);
        $('#addTransationModal').modal('show');
      
    }
  });
});
function payment(elem)
    {
      // alert('sgd');      
      if($(elem).val()=='cash')
      {
        $(".payment_mode").hide();
      }
      else if($(elem).val()== '')
      {
        $(".payment_mode").hide();
      }
      else
      {
      $(".payment_mode").show();
      }
    }
</script> -->
@endsection




         
