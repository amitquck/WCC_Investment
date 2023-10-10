@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Transaction List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
<form action="" method="get">
  <!-- <div class="row">
    <div class="col-md-2">
      <input type="text" class="form-control" placeholder="Name" name="associate_name" id="associate_name" autocomplete="off">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-primary btn-sm">
        <i class="material-icons">search</i>
      </button>
    </div>
  </div> -->
</form>
  <div class="card card-style-1 mt-3">
    <div style="background-color:blue;color: white;">&nbsp; Investment Detail</div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Amount</th>
            <th scope="col">Transaction Type</th>
            <th scope="col">Interest %</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Name</th>
            <th scope="col">Cheque/DD Number</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($customertransactions)
              <tr>
                @php $key = 1; @endphp
                <td>{{$key++}}.</td>
                <td>₹ {{$customertransactions->amount}}</td>
                <td>{{$customertransactions->transaction_type}}</td>
                <td>{{$customerinterest->customer_interest_rate}} %</td>
                <td>{{$customertransactions->payment_type}}</td>
                <td>{{$customertransactions->bank_name?$customertransactions->bank_name:'N/A'}}</td>
                <td>{{$customertransactions->cheque_dd_number?$customertransactions->cheque_dd_number:'N/A'}}</td>
                <td>{{$customertransactions->deposit_date}}</td>
                <td>
                  <form action="{{route('admin.customerAllInvestInterestDelete',$customertransactions->customer_investment_id)}}" id="delete-form-{{$customertransactions->customer_investment_id}}" method="post" >
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                  </form>
                  <a href="{{route('admin.customerAllInvestInterestDelete',encrypt($customertransactions->customer_investment_id))}}" class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" title="Delete"><i class="material-icons">delete_outline</i></a>
                </td>
              </tr>
          @else
            <tr><td colspan="9"><h4 class="text-danger text-center">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
@endsection

