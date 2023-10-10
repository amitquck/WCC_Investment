@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Investments</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="d-flex">
    <div class="list-with-gap">
     
    </div>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="">Sr No:</th>
            <th scope="col" class="">Amount</th>
            <th scope="col" class="">Interest %</th>
            <th scope="col" class="">Payment Type</th>
            <th scope="col" class="">Bank Details</th>
            <th scope="col" class="">Deposit Date</th>
          </tr>
        </thead>
        <tbody>
         @if($user_txns->count()>0)
          @foreach($user_txns as $key => $txn)
          <tr>
            <td class="">{{($user_txns->currentpage()-1)*$user_txns->perpage()+$key+1}}.</td>
            <td class="text-success">{{$txn->amount}}</td>
            <td class="">{{$txn->customerinvestment->customer_interest_rate}} % </td>
            <td>
              @if($txn->payment_type ==='null' )
               N/A 
              @else 
                {{ucwords($txn->payment_type)}}  
              @endif
            </td>
            <td> 
              <strong>Bank Name - </strong> 
              @if($txn->bank_id === null) N/A @else 
              <a class="">{{$txn->bankname->bank_name}}</a> @endif  <br>

              <strong>Cheque/DD Number - </strong>
              @if($txn->cheque_dd_number === null) N/A @else 
              <a class="">{{$txn->cheque_dd_number}}</a> 
              @endif <br> 
              Cheque/DD date - @if($txn->cheque_dd_date  === null) N/A @else {{date('d-m-Y',strtotime($txn->cheque_dd_date))}} @endif 
            </td>
            <td>{{date('d-m-Y',strtotime($txn->deposit_date))}}</td>
            <!-- <td class="text-center">
              <a href="{{route('customer.customer_investment_transaction',encrypt($txn->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary" data-toggle="tooltip" title="View Customer Investment Transaction" ><i class="material-icons">visibility</i></a>
            </td> -->
          </tr>
          @endforeach
          @else
            <tr><td colspan="10"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
{{$user_txns->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection