@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Investment Transactions</li>
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
            <th scope="col" class="text-center">Deposit Date</th>
            
          </tr>
        </thead>
        <tbody>
        @php $current_credit = 0 @endphp
        @php $current_debit = 0 @endphp
          @foreach($users as $key => $user)
          <tr>
            <td class="text-center">{{($users->currentpage()-1)*$users->perpage()+$key+1}}.</td>
            @if($user->cr_dr == 'cr') 
              @php $current_credit +=  $user->amount @endphp
              @else
              @php $current_debit +=  $user->amount @endphp
            @endif
            <td class="text-center">₹ {{$user->amount}}</td>
            <td class="text-center">@if($user->payment_type ==='null' ) N/A @else {{$user->payment_type}}  @endif <br>@if($user->cr_dr == 'cr') Credit ({{ucwords($user->transaction_type)}}) @else Debit ({{ucwords($user->transaction_type)}}) @endif</td>
           
            <td class=""> <strong>Bank Name - </strong> @if($user->bank_name === null) N/A @else <a class="btn-primary">{{$user->bank_name}}</a> @endif  <br><strong>
            Cheque/DD Number - </strong>@if($user->cheque_dd_number === null) N/A @else <a class="btn-danger">{{$user->cheque_dd_number}}</a> @endif <br> Cheque/DD date - @if($user->cheque_dd_date  === null) N/A @else {{date('d-m-Y',strtotime($user->cheque_dd_date))}} @endif </td>
            <td class="text-center">{{date('d-m-Y',strtotime($user->deposit_date))}}</td>
           
          </tr>
        
          @endforeach
            
              <tr ><td colspan="4">Credit : ₹ {{$current_credit}} </td><td >Total Credit : <b>{{ $credit }} ₹</b></td><tr><td colspan="4"> Debit:₹ {{ $current_debit }}</td><td >Total Debit: <b>{{ $debit }} ₹</b></td></tr>
              <td colspan="4"> Balance: ₹ {{$current_credit - $current_debit}} </td><td >Total Balance: <b>{{ $credit-$debit }} ₹</b></td></tr>
            
            

        </tbody>
      </table>
    </div>
  </div>
{{$users->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection