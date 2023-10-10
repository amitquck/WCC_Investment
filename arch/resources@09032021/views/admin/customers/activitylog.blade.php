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
            <th scope="col">Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Transaction Type</th>
            <th scope="col">Payment Type</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
              @foreach($transactions as $key => $transaction)
               
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
                  @elseif($transaction->transaction_type == 'interest')
                    <td class="text-primary">₹ {{($transaction->transaction_type == 'interest')?$transaction->sum_monthly_interest:$transaction->amount}}</td>
                  @else
                    <td class="text-danger">₹ {{($transaction->transaction_type == 'interest')?$transaction->sum_monthly_interest:$transaction->amount}}</td>
                  @endif
                  
                  @if($transaction->transaction_type == 'deposit')
                    <td class="text-success">{{ucwords($transaction->transaction_type)}}</td>
                  @elseif($transaction->transaction_type == 'interest')
                    <td class="text-primary">{{ucwords($transaction->transaction_type)}}</td>
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
                    @php $deposit_date = $transaction->deposit_date?Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y'):''; @endphp
                    {{$deposit_date}}
                  </td>
                  <td>
                    @php $created_at = Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y'); @endphp
                  {{$created_at}}</td>
                 
                </tr>
              @endforeach
              
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
  {{$transactions->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


@endsection




         
