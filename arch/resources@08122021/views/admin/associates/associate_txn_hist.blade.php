@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Associate</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Transaction History</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  <div class="d-flex">
    <div class="list-with-gap">

    </div>
  </div>
  <div class="card card-style-1 mt-3">
      <span class="bg-primary text-center text-white">{{ucwords(Auth::user()->name)}} ( {{ Auth::user()->code }} )</span>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="">Sr No:</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Transaction</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            {{--  <th scope="col">Remarks</th>  --}}
            {{--  <th scope="col">Created At</th>  --}}

          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
            @php $total_credit = $total_debit = 0; @endphp
              @foreach($transactions as $key => $transaction)
                @if($transaction->cr_dr == 'cr')
                  @php $total_credit += $transaction->amount; @endphp
                @else
                  @php $total_debit += $transaction->amount;  @endphp
                @endif
                @if($transaction->associate != NULL || $transaction->customers != NULL)
                    <tr>
                    <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>


                    @if($transaction->transaction_type == 'deposit' || $transaction->transaction_type == 'commission')
                        <td class="text-success">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                    @else
                        <td class="text-success">₹ 0.00</td>
                    @endif

                    @if($transaction->transaction_type == 'withdraw')
                        <td class="text-danger">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                    @else
                        <td class="text-danger">₹ 0.00</td>
                    @endif

                    @if($transaction->transaction_type == 'deposit')
                        <td class="text-success">{{ucwords($transaction->transaction_type)}}</td>
                    @else
                        <td class="text-danger">{{ucwords($transaction->transaction_type)}}</td>
                    @endif

                    <td>
                        @if($transaction->payment_type == 'null')
                        Cash Payment
                        @else
                        {{$transaction->payment_type}}
                        @endif
                    </td>
                    <td>
                        @if($transaction->payment_type == 'cash' || $transaction->payment_type == 'null')
                        Cash Payment
                        @else
                        <strong>Bank Name : </strong>{{$transaction->bankname?$transaction->bankname->bank_name:'N/A'}}
                        <br>
                        <strong>Cheque/DD Number : </strong>{{$transaction->cheque_dd_number?$transaction->cheque_dd_number:'N/A'}}
                        @endif
                    </td>
                    <td>
                        @php $deposit_date = $transaction->deposit_date?Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y'):''; @endphp
                        {{$deposit_date}}
                    </td>
                    {{--  <td>{{$transaction->remarks?$transaction->remarks:'N/A'}}</td>
                    <td>
                        @php $created_at = Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y'); @endphp
                    {{$created_at}}</td>  --}}

                    </tr>
                @endif
              @endforeach
                <th colspan="3"><span class="text-success">Total :&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;  ₹ {{$total_credit}}</span> &nbsp; &nbsp;    &nbsp;  &nbsp;  <span class="text-danger">  ₹ {{$total_debit}}</span>   </th>
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
  {{$transactions->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection
