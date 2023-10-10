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
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Month</th>
            <th>Year</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if($payouts->count()>0)
                @foreach($payouts as $key => $payout)
                    <tr>
                        <td>{{($payouts->currentpage()-1)*$payouts->perpage()+$key+1}}.</td>
                        <td>{{Carbon\Carbon::parse($payout->start_date)->format('M')}}</td>
                        <td>{{$payout->year}}</td>
                        <td>
                            <a href="{{route('admin.delete_gen_payouts',['month'=>$payout->month,'year'=>$payout->year])}}" class="text-danger" onclick="return confirm('Are You Sure')"><i class="material-icons">delete_outline</i></a>
                        </td>
                    </tr><!--  -->
                @endforeach
            @else
                <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
					

{{$payouts->links()}}
@endsection