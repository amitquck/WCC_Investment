@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Company Bank List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
      <h6 class="text-center">{{$bank->bank_name}}</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
          @if($transactions->count()>0)
          @php $total_credit = $total_debit = 0; @endphp
            @foreach($transactions as $key => $transaction)
                <tr>
                   @if($transaction->cr_dr == 'cr')
                    @php
                      $total_credit += $transaction->amount;
                    @endphp
                    @else
                    @php
                      $total_debit += $transaction->amount;
                    @endphp
                   @endif
                  <td>{{$key+1}}.</td>
                  <td>
                    <strong>Remarks : </strong>{{ucfirst($transaction->remarks?$transaction->remarks:'N/A')}}<br>
                    <strong>Cheque/DD Number : {{$transaction->cheque_dd_number}}</strong><br>
                    @php
                      $depositdate= Carbon\Carbon::parse($transaction->deposit_date)->format('j-M-Y');
                    @endphp
                    
                    <strong>Deposit/Withdrwal Date : </strong>{{$depositdate}}
                  </td>
                  <td class="text-success">@if($transaction->cr_dr == 'cr')₹  {{$transaction->amount}} @endif</td>
                  <td class="text-danger">@if($transaction->cr_dr == 'dr')₹  {{$transaction->amount}} @endif</td>
                  <td>
                    @php
                      $created_at= Carbon\Carbon::parse($transaction->created_at)->format('j-M-Y');
                    @endphp
                    {{$created_at}}
                  </td>
                </tr>
            @endforeach
                <tfoot>
                  <tr>
                    <td colspan="6"><span class="text-success">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Pending :  ₹ {{$total_credit - $total_debit}}</span>    &nbsp;  &nbsp;   ||  &nbsp;  &nbsp;  <span class="text-primary">Total Debit :  ₹ {{$total_debit}}</span>   </td>
                  </tr>
                </tfoot>
          @else
            <tr>
              <td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
{{$transactions->links()}}
@endsection




         
