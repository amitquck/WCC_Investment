@extends('layouts.admin.default')
@section('content')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customers Wise Investment Report</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  @if($customer_deposits->count()>0)
    @php
        $params = [];
        if(Request::has('id'))
           $params = array_merge(['id' => Request::input('id')],$params);
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
    @endphp
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_customer_investments',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
<hr>
<br>
<form action="" method="get">
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
        <label for="from_date">Customer</label>
        <input type="text" class="form-control" placeholder="Search By N,M&C" name="" id="cust_invest" autocomplete="off"><input type="hidden" name="id" id="hidden_cust_invest">
      </div>
      <div class="col-md-1 mt-2">
         <label></label>
        <button type="submit" class="btn btn-primary btn-sm">
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
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Deposit Amount</th>
            <th scope="col">Type</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
            <!-- <th scope="col">Action</th> -->
          </tr>
        </thead>
        <tbody><br>
          @isset($customer_deposits)
            @if($customer_deposits->count()>0)
              @foreach($customer_deposits as $key => $deposit)
                <tr>
                  <td>{{$key+1}}.</td>
                  <td>{{$deposit->user->name}}</td>
                  <td class="text-success">₹ {{$deposit->amount}}</td>
                  <td>{{$deposit->transaction_type}}</td>
                  <td>{{$deposit->payment_type}}</td>
                  <td>
                    @if($deposit->payment_type == 'cash' || $deposit->payment_type == 'null')
                      {{'Cash Payment'}}
                    @else
                      <strong>Bank Name : </strong>{{$deposit->bankname->bank_name}}
                    <br>
                      <strong>Cheque/DD Number : </strong>{{$deposit->cheque_dd_number}}
                    @endif
                  </td>
                  @php $dep = Carbon\Carbon::parse($deposit->deposit_date)->format('j-M-Y'); @endphp
                  <td>{{ $dep}}</td>
                  <td>
                    @php $created_at = Carbon\Carbon::parse($deposit->created_at)->format('j-M-Y'); @endphp
                  {{$created_at}}</td>
                  <td>
                    <!-- <form action="{{route('admin.deleteCustomerDeposit',$deposit->user->id)}}" id="delete-form-{{$deposit->user->id}}" method="post" >
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                    <a href="{{route('admin.deleteCustomerDeposit',encrypt($deposit->user->id))}}"
                        class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" 
                        title="Delete"><i class="material-icons">delete_outline</i></a> -->
                  </td>
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h5 class="text-center text-danger">No Record Found</h5></td></tr>
            @endif
          @endisset
        </tbody>
      </table>
    </div>
  </div>
{{$customer_deposits->links()}}
@endsection

@section('page_js')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $('#cust_invest').autocomplete({
    source:"{{route('admin.getCustomers')}}",
    minLength: 2,
    select:function(event,ui){
      // alert(ui.item.id);
      $('#cust_invest').val(ui.item.name);
      $('#hidden_cust_invest').val(ui.item.id);
      $('#excel_customer_investments').val(ui.item.id);

    }
  });
  </script>
<script>
  

  $('.customerWithdrawForm').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.customerWithdrawForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#withdraw-container').html(data);
        $('#withdrawModal').modal('show');
      
    }
  });
});
</script>
@endsection