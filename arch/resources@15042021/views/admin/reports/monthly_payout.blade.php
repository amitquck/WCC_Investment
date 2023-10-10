@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Monthly Payout List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($cust_reward->count()>0)
    @php
      $params = [];
      if(Request::has('month'))
           $params = array_merge(['month' => Request::input('month')],$params);
      if(Request::has('year'))
           $params = array_merge(['year' => Request::input('year')],$params);
      if(Request::has('payment_type'))
           $params = array_merge(['payment_type' => Request::input('payment_type')],$params);
    @endphp
      <div class="row">
        <div class="col-md-6 offset-md-6 text-right">
          @if(empcan('import_monthly_payout') || Auth::user()->login_type == 'superadmin')  
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importTransaction"><i class="material-icons">import_export</i> Excel Import</button>
          @endif
          @if(empcan('export_monthly_payout') || Auth::user()->login_type == 'superadmin')
            <a href="{{route('admin.excel_monthly_payout',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
          @endif
        </div>
    </div><br>
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <select class="form-control" name="month">
          <option value=""> Select Month</option>
              @for($i=01; $i<=12; $i++)
                  <option value="{{$i}}" @if(old('month') == $i) selected="selected" @endif>{{Carbon\Carbon::parse('01-'.$i.'-'.date('Y'))->format('M')}}</option>
              @endfor
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-control" name="year">
            <option value="">Select Year</option>
            @for($i=date('Y'); $i>=2019; $i--)
                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
            @endfor
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-control" name="payment_type">
            <option value="">Select Type</option>
            <option value="cash">Cash</option>
            <option value="accumulate">Cumilate</option>
            <option value="bank">Bank</option>
            <option value="0">Hold</option>
            <option value="no_interest">No Interest</option>
        </select>
      </div>
      <div class="col-md-1 mt-2">
        <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
        </button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          @if(Request::input('payment_type') == 'bank')
            <tr>
              <th scope="col">Sr No:</th>
              <th scope="col">Customer Name</th>
              <th scope="col">Account No</th>
              <th scope="col">IFSC Code</th>
              <th scope="col">Interest Amount</th>
              <th scope="col">Remarks</th>
            </tr>
          @elseif(Request::input('payment_type') == 'cash' || Request::input('payment_type') == 'hold' || Request::input('payment_type') == 'accumulate' || Request::input('payment_type') == 'no_interest')
            <tr>
              <th scope="col">Sr No:</th>
              <th scope="col">Customer Name</th>
              <th scope="col">Customer Code</th>
              <th scope="col">Interest Amount</th>
            </tr>
          @else
            <tr>
              <th scope="col">Sr No:</th>
              <th scope="col">Customer Code</th>
              <th scope="col">Customer Name</th>
              <th scope="col">Interest Amount</th>
              <th scope="col">Month</th>
              <th scope="col">Year</th>
              <th scope="col">Amount</th>
              <th scope="col">Account No</th>
              <th scope="col">IFSC Code</th>
            </tr>
          @endif
        </thead>
        <tbody>
          @if($cust_reward->count()>0)
            @foreach($cust_reward as $key => $reward)
              @if(Request::input('payment_type') == 'bank')
                <tr>
                   <td>{{($cust_reward->currentpage()-1)*$cust_reward->perpage()+$key+1}}.</td>
                   <td>{{$reward->customer->name}}</td>
                   <td>{{$reward->customer->customerdetails->account_number?$reward->customer->customerdetails->account_number:'N/A'}}</td>
                   <td>{{$reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A'}}</td>
                   <td> 
                    @if($reward->sum_monthly_payout_customer_wise > 0)
                      <p class="text-success">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                    @else
                      <p class="text-danger">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                    @endif
                   </td>
                   <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($reward->customer->id))}}" data-toggle="tooltip" title="View Details">{{$reward->customer->code}}</td>
                </tr>
              @elseif(Request::input('payment_type') == 'cash' || Request::input('payment_type') == 'hold' || Request::input('payment_type') == 'accumulate' || Request::input('payment_type') == 'no_interest')
                <tr>
                   <td>{{($cust_reward->currentpage()-1)*$cust_reward->perpage()+$key+1}}.</td>
                   <td>{{$reward->customer->name}}</td>
                   <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($reward->customer->id))}}" data-toggle="tooltip" title="View Details">{{$reward->customer->code}}</td>
                   <td> 
                    @if($reward->sum_monthly_payout_customer_wise > 0)
                      <p class="text-success">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                    @else
                      <p class="text-danger">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                    @endif
                   </td>
                </tr>
              @else
                <tr>
                 <td>{{($cust_reward->currentpage()-1)*$cust_reward->perpage()+$key+1}}.</td>
                 <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($reward->customer->id))}}" data-toggle="tooltip" title="View Details">{{$reward->customer->code}}</td>
                 <td>{{$reward->customer->name}}</td>
                 <td> 
                  @if($reward->sum_monthly_payout_customer_wise > 0)
                    <p class="text-success">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                  @else
                    <p class="text-danger">₹ {{$reward->sum_monthly_payout_customer_wise}}</p>
                  @endif
                 </td>
                 <td>{{$reward->month}}</td>
                 <td>{{$reward->year}}</td>
                 <td>{{$reward->total_amount}}</td>
                 <td>{{$reward->customer->customerdetails->account_number?$reward->customer->customerdetails->account_number:'N/A'}}</td>
                 <td>{{$reward->customer->customerdetails->ifsc_code?$reward->customer->customerdetails->ifsc_code:'N/A'}}</td>
                </tr>
              @endif
            @endforeach
          @else
            <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <!-- Import Questions Modal -->
  <div id="importTransaction" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Import Client Transactions</h4>
        </div>
        <form method="post" action="{{route('admin.import_excel_transactions')}}" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <label>Import Excel</label>
                  <input type="file" name="import_txns">
                  <p class="text-danger">Only Excel File (.CSV and .XLS)</p>
                </div>
                <div class="col-md-6">
                  <label>Date</label>
                  <input type="text" name="date" data-alias="dd/mm/YYYY" class="form-control"> 
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
          <button type="submit" class="btn btn-primary" >import</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@php
  $params = [];
  if(Request::has('from_date'))
     $params = array_merge(['from_date' => Request::input('from_date')],$params);
  if(Request::has('to_date'))
     $params = array_merge(['to_date' => Request::input('to_date')],$params);
  if(Request::has('payment_type'))
     $params = array_merge(['payment_type' => Request::input('payment_type')],$params);
@endphp
{{$cust_reward->appends($params)->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
  // $("input.date").inputmask("date", { "placeholder": "dd-mm-yyyy" });


 $('input').inputmask("date",{
    mask: "1-2-y", 
    placeholder: "dd-mm-yyyy", 
    leapday: "-02-29", 
    separator: "-", 
    alias: "dd/mm/yyyy"
  });
</script>
@endsection




         
