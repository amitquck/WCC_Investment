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
            <th scope="col" class="text-center">Sr No:</th>
            <th scope="col" class="text-center">Amount</th>
            <th scope="col" class="text-center">Payment Type</th>
            <th scope="col" class="text-center">Bank Details</th>
            <!-- <th scope="col" class="text-center">Deposit Date</th> -->
          </tr>
        </thead>
        <tbody>
         @if($users->count()>0)
          @foreach($users as $key => $user)
          <tr>
            <td class="text-center">{{($users->currentpage()-1)*$users->perpage()+$key+1}}.</td>
            <td class="text-center">{{$user->amount}}</td>
            <td class="text-center">{{$user->customer_interest_rate}} % </td>
            <td class="text-center">{{date('d-m-Y',strtotime($user->deposit_date))}}</td>
            <!-- <td class="text-center">
              <a href="{{route('customer.customer_investment_transaction',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary" data-toggle="tooltip" title="View Customer Investment Transaction" ><i class="material-icons">visibility</i></a>
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
{{$users->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection