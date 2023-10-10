@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Associate</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate's Direct Customer Transactions</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  <div class="d-flex">
    <div class="list-with-gap">

    </div>
  </div>
    @php
        $params = [];
        if(Request::has('custId'))
        $params = array_merge(['custId' => Request::input('custId')],$params);
    @endphp
    @if(empcan('export_associate_cust_txn') || Auth::user()->login_type == 'associate')
    <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('associate.export_associate_cust_txn',$params)}}" id="" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
    @endif
  <div class="card card-style-1 mt-3">
      <span class="bg-primary text-center text-white">{{$cust->name}} ( {{ $cust->code }} )</span>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="">Sr No:</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Transaction Type</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            {{--  <th scope="col">Remarks</th>  --}}
            {{--  <th scope="col">Created At</th>  --}}

          </tr>
        </thead>
        <tbody>
            @if($customers->count()>0)

              @foreach($customers as $key => $txn)
                    <tr>
                        <td>{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>

                        @if($txn->transaction_type == 'deposit')
                            <td class="text-success">₹ {{$txn->amount?$txn->amount:0.00}}</td>
                        @else
                            <td class="text-success">₹ 0.00</td>
                        @endif

                        @if($txn->transaction_type == 'withdraw')
                            <td class="text-danger">₹ {{$txn->amount?$txn->amount:0.00}}</td>
                        @else
                            <td class="text-danger">₹ 0.00</td>
                        @endif

                        @if($txn->transaction_type == 'deposit')
                            <td class="text-success">{{ucwords($txn->transaction_type)}}</td>
                        @else
                            <td class="text-danger">{{ucwords($txn->transaction_type)}}</td>
                        @endif



                        <td>
                            @if($txn->payment_type == 'null')
                                Cash Payment
                            @else
                                {{$txn->payment_type}}
                            @endif
                        </td>
                        <td>
                            @if($txn->payment_type == 'cash' || $txn->payment_type == 'null')
                                Cash Payment
                            @else
                                <strong>Bank Name : </strong>{{$txn->bankname?$txn->bank_name:'N/A'}}
                                <br>
                                <strong>Cheque/DD Number : </strong>{{$txn->cheque_dd_number?$txn->cheque_dd_number:'N/A'}}
                            @endif
                        </td>
                        <td>
                            @php $deposit_date = $txn->deposit_date?Carbon\Carbon::parse($txn->deposit_date)->format('j-m-Y'):''; @endphp
                            {{$deposit_date}}
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
  {{$customers->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection
