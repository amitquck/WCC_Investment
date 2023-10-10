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
@if($transactions->count()>0)
  @php $trans = $transactions->take(1); @endphp
    @foreach($trans as $tran)
        <div class="row">
            <div class="col-md-6 offset-md-6 text-right">
              <a href="{{route('admin.excel_before_confirmation_payout',['month'=>$tran->month, 'year'=>$tran->year])}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
            </div>
        </div><br>
    @endforeach    
@endif
<div class="card card-style-1 mt-3">
    <div class="table-responsive">
    <form method="get" action="{{route('admin.confirm_payout',[$month,$year])}}">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Code</th>
            <th>Name</th>
            <th>Interest</th>
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
                        
                    @if($transaction->reward_type == 'commission')
                        <td class="text-primary"><a href="{{route('admin.associate_view',$transaction->associate->id)}}"></a>{{$transaction->associate->code}}</td>
                        <td>Associate Name : {{ucwords($transaction->associate->name)}}</td>
                    @else
                        <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($transaction->customer->id))}}" data-toggle="tooltip" title="View Details">{{$transaction->customer->code}}</a></td>
                        <td>Customer Name : {{ucwords($transaction->customer->name)}}</td>
                    @endif
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
                    <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
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