@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Monthly Report By Associate List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($associate_txn->count()>0)
    @php
        $params = [];
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
        if(Request::has('payment_type'))
           $params = array_merge(['payment_type' => Request::input('payment_type')],$params);
    @endphp
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_all_transactions',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
<form action="" method="get"><!-- 
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="from_date">From Date</label>
        <input type="date" name="from_date" class="form-control" placeholder="From Date">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date</label>
        <input type="date" name="to_date" class="form-control" placeholder="To Date">
      </div>
      <div class="col-md-3">
       <label for="name">Search Payment Mode</label>
       <select name="payment_type" class="form-control" onChange="payment(this)">
         <option value="">Select</option>
         <option value="cash">Cash</option>
         <option value="cheque">Cheque</option>
         <option value="dd">DD</option>
         <option value="NEFT">NEFT</option>
         <option value="RTGS">RTGS</option>
       </select>
    </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
        </button>
      </div>
    </div>
  </div> -->
</form>
  <div class="card card-style-1 mt-3">
  <div class="text-center bg-primary text-white">
    @if($associate_txn)
      @foreach($associate_txn->take(1) as $txn)
        {{$txn->customer->name.' ('.$txn->customer->code.')'}}
      @endforeach
    @endif
  </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Amount</th>
            <th scope="col">Reward Type</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>

            @if($associate_txn->count()>0)
              @foreach($associate_txn as $key => $transaction)
              <tr>
                <td>{{($associate_txn->currentpage()-1)*$associate_txn->perpage()+$key+1}}.</td>
                <td>{{$transaction->amount}}</td>
                <td>{{$transaction->transaction_type}}</td>
                <td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y')}}</td>
                <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
  {{$associate_txn->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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




         
