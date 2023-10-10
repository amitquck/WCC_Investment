@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Transaction History</li>
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
            <th scope="col" class="">Payment Type</th>
            <th scope="col" class="">Transaction Type</th>
            <th scope="col" class="">Bank Details</th>
            <th scope="col" class="">Deposit Date</th>
            
          </tr>
        </thead>
        <tbody>
          @foreach($transactions as $key => $user)
          <tr>
            <td class="">{{$key+1}}.</td>
            
            @if($user->cr_dr == 'cr')
              <td class=" text-success">₹ {{$user->amount}}</td>
            @else
              <td class=" text-danger">₹ {{$user->amount}}</td>
            @endif

            <td class="">
              @if($user->payment_type ==='null' )
               N/A 
              @else 
                {{ucwords($user->payment_type)}}  
              @endif
            </td>
             @if($user->cr_dr == 'cr')
              <td class=" text-success"> {{ucwords($user->transaction_type)}}</td>
            @else
              <td class=" text-danger">{{ucwords($user->transaction_type)}}</td>
            @endif
           
            <td class=""> <strong>Bank Name - </strong> @if($user->bank_id === null) N/A @else <a class="">{{$user->bankname->bank_name}}</a> @endif  <br><strong>
            Cheque/DD Number - </strong>@if($user->cheque_dd_number === null) N/A @else <a class="">{{$user->cheque_dd_number}}</a> @endif <br> Cheque/DD date - @if($user->cheque_dd_date  === null) N/A @else {{date('d-m-Y',strtotime($user->cheque_dd_date))}} @endif </td>

            @if($user->transaction_type == 'interest')
              <td class="">{{date('d-m-Y',strtotime($user->posting_date))?date('d-m-Y',strtotime($user->posting_date)):date('d-m-Y',strtotime($user->deposit_date))}}</td>
            @else
              <td class="">{{date('d-m-Y',strtotime($user->deposit_date))}}</td>
            @endif
           
          </tr>
          @endforeach
            

        </tbody>
      </table>
    </div>
  </div>
  {{$transactions->links()}}
<!------------------------------------------------------------------->
@endsection


@section('page_js')

@endsection