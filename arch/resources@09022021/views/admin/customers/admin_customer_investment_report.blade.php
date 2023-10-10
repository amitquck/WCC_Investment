@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="col-md-12">
    <form action="{{route('admin.customer_investments')}}" method="get">
      <div class="row">
        <div class="col-md-3">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Search By N,M&C" name="" id="cust_invest" autocomplete="off"><input type="hidden" name="id" id="hidden_cust_invest">
            </th>
          </tr>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
          </button>
        </div>
      </div>
    </form>
  </div>
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
            <th scope="col">Balance Amount</th>
            <th scope="col" class="text-center">Action</th>
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
                      <strong>Bank Name : </strong>{{$deposit->bank_name}}
                    <br>
                      <strong>Cheque/DD Number : </strong>{{$deposit->cheque_dd_number}}
                    @endif
                  </td>
                  <td>{{$deposit->deposit_date}}</td>
                  <td>{{$deposit->created_at}}</td>
                  <td class="text-danger">₹ {{$deposit->user->customer_current_balance()}}</td>
                <td class="text-center">
                  <a href="{{route('admin.customerAllTransactions',encrypt($deposit->customer_id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="view Transactions"  data-id="{{$deposit->customer_id}}"><i class="material-icons">visibility</i></a>

                  <!-- <a   class="btn  btn-default bigger-130 text-danger" data-toggle="tooltip" title="Add Withdraw"  data-id="{{$deposit->customer_id}},{{$deposit->id}}"><i class="material-icons">account_balance_wallet</i></a> -->
                  

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
         
<div class="modal fade" id="addTransationModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/associate/addTransaction') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Withdraw Amount</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="add-transaction-container">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    </div>
</div>


<!------------------------customer withdraw form--------------------->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerWithdraw')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Withdraw Amount</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="withdraw-container">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!------------------------------------------------------------------->

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
    source:"{{route('admin.getCustomerInterest')}}",
    minLength: 3,
    select:function(event,ui){
      $('#cust_invest').val(ui.item.name);
      $('#hidden_cust_invest').val(ui.item.id);

    }
  });

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




         
