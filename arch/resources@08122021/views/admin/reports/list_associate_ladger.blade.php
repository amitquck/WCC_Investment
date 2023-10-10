@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Ladger Balance</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  @if($transactions)
    @php
        $params = [];
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
        if(Request::has('associate'))
           $params = array_merge(['associate' => Request::input('associate')],$params);
    @endphp
    @if(auth()->user()->login_type == 'superadmin')
        <div class="col-md-3" style="margin-left:85%">
            <a href="{{route('admin.excel_associate_ladger',$params)}}" id="excel_associate_ladger" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
        </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="from_date">From Date </label>
        <input type="text" name="from_date" class="form-control datepicker" placeholder="From Date">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date </label>
        <input type="text" name="to_date" class="form-control datepicker" placeholder="To Date">
      </div>
     <p>OR</p>
      <div class="col-md-3">
        <label for="associate">Associate</label>
        <input type="text" name="associate" class="form-control" placeholder="Code">
      </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
        </button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">

    <div class="table-responsive">
        @if(Request::has('associate'))
            @php $ass = App\User::where('code',Request::input('associate'))->first(); @endphp
            <div class="bg-primary text-white text-center">{{ $ass->name }} ({{ Request::input('associate')}})</div>
        @elseif(auth()->user()->login_type == 'associate')
            <div class="bg-primary text-white text-center">{{ auth()->user()->name }} ({{ auth()->user()->code}})</div>
        @endif
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>User</th>
            <th>Code</th>
            <th>Name</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Running Balnace</th>
            <th scope="col">Credit/Debit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($transactions)
            @php $total_credit = $total_debit = $running_balance = 0; @endphp
              @foreach($transactions as $key => $transaction)
                @if($transaction->cr_dr == 'cr')
                  @php
                    $total_credit += $transaction->amount;
                  @endphp
                @else
                  @php
                    $total_debit += $transaction->amount;
                  @endphp
                @endif
                <tr>
                  <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>

                  @if($transaction->customer_id != null)
                    <td>Customer</td>
                    <td class="text-info"><a href="{{route('admin.customer_detail',encrypt($transaction->customer->id))}}" data-toggle="tooltip" title="View Details">{{($transaction->customer->code)}}</td>
                  <td>{{ucwords($transaction->customer->name)}}</td>
                  @else
                    <td>Associate</td>
                    <td class="text-info"><a href="{{route('admin.associate_view',$transaction->associate->id)}}" data-toggle="tooltip" title="View Details">{{($transaction->associate->code)}}</td>
                  <td>{{ucwords($transaction->associate->name)}}</td>
                  @endif


                  @if($transaction->transaction_type == 'commission')
                    <td class="text-success">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                  @else
                    <td class="text-success">₹ 0.00</td>
                  @endif

                  @if($transaction->transaction_type == 'withdraw')
                    <td class="text-danger">₹ {{$transaction->amount?$transaction->amount:0.00}}</td>
                  @else
                    <td class="text-danger">₹ 0.00</td>
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


                  <td>{{$transaction->deposit_date?Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y'):''}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>

                </tr>
              @endforeach
              <tr><td></td>
                <td colspan="3"><span class="text-primary">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Total Debit :  ₹ {{$total_debit}}</span>   </td>
              </tr>
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
@php
  $params = [];
  if(Request::has('from_date'))
     $params = array_merge(['from_date' => Request::input('from_date')],$params);
  if(Request::has('to_date'))
     $params = array_merge(['to_date' => Request::input('to_date')],$params);
  if(Request::has('associate'))
     $params = array_merge(['associate' => Request::input('associate')],$params);
@endphp
@if($transactions)
{{$transactions->appends($params)->links()}}

@endif
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
$('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat : "dd-mm-yy"
});
// $.fn.datepicker.defaults.format = "dd/mm/yyyy";
</script>
<!-- <script>
$('.AddTransationForm').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.associateAddTransationForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#add-transaction-container').html(data);
        $('#addTransationModal').modal('show');

    }
  });
});
function payment(elem)
    {
      // alert('sgd');
      if($(elem).val()=='cash')
      {
        $(".payment_mode").hide();
      }
      else if($(elem).val()== '')
      {
        $(".payment_mode").hide();
      }
      else
      {
      $(".payment_mode").show();
      }
    }
</script> -->
@endsection





