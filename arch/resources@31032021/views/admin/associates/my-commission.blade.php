@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">My Commission</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
<div class="col-md-12">
     
    <div class="row">
    <strong>Totol Commisssion:</strong> ₹  <a class="btn-link ml-2 "><b class="text-decoration:center">{{ $total_commission }}</b></a>
    </div>
</div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr class="text-center">
            <th scope="col">Sr No:</th>
            <th scope="col">Customer</th>
            <th scope="col">Commission Amount</th>
            <th scope="col">Commission %</th>
            <th scope="col">Month-Year</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
              @foreach($transactions as $key => $transaction)
              <tr>
             
                <td class="text-center">{{$key+1}}.</td>
                <td class="text-center">{{$transaction->customer->name}}</td>
                <td class="text-center">{{$transaction->customer_sum_monthly_commission}}</td>
                <td class="text-center">{{$transaction->commission_percent}}</td>
                <td class="text-center">{{date('M-Y',strtotime('01-'.$transaction->month.'-'.$transaction->year))}}</td>
                <td class="text-center">{{date('d-m-Y',strtotime($transaction->created_at))}}</td>
                  
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div> 


@endsection


@section('page_js')

@endsection




         
