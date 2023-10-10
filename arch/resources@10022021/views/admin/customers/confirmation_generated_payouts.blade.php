@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb breadcrumb-style2">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customer Genereted Payouts</li>
</ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')	

<div class="card card-style-1 mt-3">
    <div class="table-responsive">
    <form method="get" action="{{route('admin.confirm_payout',[$month,$year])}}">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Name</th>
            <th>Amount</th>
            <th>Month</th>
            <th>Year</th>
            <th>Total Amount</th>
            <th>Interest/Commission %</th>
            <th scope="col">Reward Type</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @foreach($transactions as $key => $transaction)
                <tr>
                    <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>
                    <td>
                        
                        @if($transaction->reward_type == 'commission')
                            Associate Name : {{ucwords($transaction->associate->name)}}<br>
                            Code : {{$transaction->associate->code}}
                        @else
                            Customer Name : {{ucwords($transaction->customer->name)}}<br>
                            Code : {{$transaction->customer->code}}
                        @endif
                    </td>
                    <td class="text-primary">
                        @if($transaction->reward_type == 'commission')
                                ₹ {{$transaction->sum_commission_amount}}
                        @else
                                ₹ {{$transaction->sum_interest_amount}}
                        @endif

                    </td>
                    <td>{{$transaction->month}}</td>
                    <td>{{$transaction->year}}</td>
                    <td>{{$transaction->total_amount}}</td>
                    <td>{{$transaction->interest_percent}} %</td>
                    <td>{{$transaction->reward_type}}</td>
                    <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-M-Y')}}</td>
                </tr>
            @endforeach
        </tbody>
      </table><br>
        <div class="text-center">
            <button type="button" class="btn btn-warning" data-dismiss="modal"><a href="{{route('admin.payout')}}">Cancel</a></button>&nbsp;
            <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
    </form><br>
    </div>
  </div>
					
{{$transactions->links()}}

@endsection