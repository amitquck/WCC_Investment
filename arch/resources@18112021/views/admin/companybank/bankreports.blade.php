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
@if($transactions->count()>0)
    @php
        $params = [];
        if(Request::has('bank_id'))
           $params = array_merge(['bank_id' => Request::input('bank_id')],$params);
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
    @endphp
    @if(Request::has('bank_id') != '')
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_bank_transactions',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-4">
        <label for="from_date">From Date</label>
        <input type="date" name="from_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="to_date">To Date</label>
        <input type="date" name="to_date" class="form-control">
        <input type="hidden" name="bank_id" class="form-control" value="{{$bank->id}}">
      </div>
      <div class="col-md-4" style="padding-top:30px;">
        <button type="submit" class="btn btn-md btn-primary">search</button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
      <h6 class="text-center">{{$bank->bank_name}}</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Decription</th>
            <th scope="col">Cheque/DD Number</th>
            <th scope="col">Cheque/DD Date</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Running Balance</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
          @if($transactions->count()>0)
          @php $total_credit = $total_debit = $running_balance = 0; @endphp
            @foreach($transactions as $key => $transaction)
                <tr>
                    <td>{{$key+1}}.</td>
                    @if($transaction->cr_dr == 'cr')
                        <td>{{ucfirst($transaction->remarks?$transaction->remarks:'N/A')}}</td>
                        @php
                            $total_credit += $transaction->amount;
                        @endphp
                    @else
                        <td>{{ucfirst($transaction->remarks?$transaction->remarks:'N/A')}}</td>
                        @php
                            $total_debit += $transaction->amount;
                        @endphp
                    @endif
                    <td>{{$transaction->cheque_dd_number?$transaction->cheque_dd_number:'cash transaction'}}</td>
                    <td>{{Carbon\Carbon::parse($transaction->cheque_dd_date)->format('j-m-Y')}}</td>
                    @if($transaction->cr_dr == 'cr')
                        <td class="text-success">
                            ₹  {{$transaction->amount}}
                        </td>
                        <td class="text-danger">
                            ₹  0.00
                        </td>
                    @else
                        <td class="text-success">
                            ₹  0.00
                        </td>
                        <td class="text-danger">
                            ₹  {{$transaction->amount}}
                        </td>
                    @endif
                    @if($transaction->cr_dr == 'cr')
                        @php
                            $running_balance += $transaction->amount;
                        @endphp
                    @else
                        @php
                            $running_balance -= $transaction->amount;
                        @endphp
                    @endif

                    <td class="text-primary">₹ {{number_format($running_balance,2)}}</td>
                    <td>{{Carbon\Carbon::parse($transaction->transaction_date)->format('j-m-Y')}}</td>
                    <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
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





