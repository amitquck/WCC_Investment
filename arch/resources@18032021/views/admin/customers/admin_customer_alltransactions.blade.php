@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Transactions List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Amount</th>
            <th scope="col">Transaction</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Bank Details</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
            <th scope="col">Action</th>

          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
              @foreach($transactions as $key => $transaction)
                <tr>
                  <td>{{($transactions->currentpage()-1)*$transactions->perpage()+$key+1}}.</td>

                  @if($transaction->transaction_type == 'deposit')
                    <td class=" text-success">₹ {{$transaction->amount}}</td>
                  @elseif($transaction->transaction_type == 'interest')
                    <td class=" text-success">₹ {{$transaction->sum_monthly_interest}}</td>
                  @else
                    <td class=" text-danger">₹ {{$transaction->amount}}</td>
                  @endif

                  <td>{{ucwords($transaction->transaction_type)}}</td>
                  <td>
                    @if($transaction->payment_type == 'null')
                      {{'N/A'}}
                    @else
                    {{ucwords($transaction->payment_type)}}
                    @endif
                  </td>
                  <td>
                    @if($transaction->payment_type == 'cash' || $transaction->payment_type == 'null')
                      {{'Cash Payment'}}
                    @else
                      <strong>Bank Name : </strong>{{$transaction->bankname?$transaction->bankname->bank_name:'N/A'}}
                    <br>
                      <strong>Cheque/DD Number : </strong>{{$transaction->cheque_dd_number?$transaction->cheque_dd_number:'N/A'}}
                    @endif
                  </td>
                  <td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y')}}</td>
                  <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
                  <td>
                    @if(Auth::user()->login_type == 'superadmin' && $transaction->transaction_type == 'deposit')
                      <a class="btn btn-xs text-primary @if(!$transaction->is_month_payout_generate) edit_deposit @else disabled @endif"  data-id="{{$transaction->customer_id}},{{$transaction->id}}"><i class="material-icons" data-toggle="tooltip" data-placement="top" title="Edit Investment">edit</i></a>

                      <a class="btn btn-link btn-icon bigger-130 text-danger @if(!$transaction->is_month_payout_generate) delete_confirm @else disabled @endif"  data-id="{{$transaction->customer_id}},{{$transaction->id}}"><i class="material-icons" data-toggle="tooltip" title="Delete" data-placement="top">delete_outline</i></a>
                    @elseif(Auth::user()->login_type == 'superadmin' && $transaction->transaction_type == 'withdraw')
                      <a class="btn btn-xs text-success @if(!$transaction->is_month_payout_generate) edit_withdraw @else disabled @endif"  data-id="{{$transaction->customer_id}},{{$transaction->id}}"><i class="material-icons" data-toggle="tooltip" title="Edit Withdraw" data-placement="top">edit</i></a>
                      <a class="btn btn-link btn-icon bigger-130 text-danger @if(!$transaction->is_month_payout_generate) delete_confirm @else disabled @endif"  data-id="{{$transaction->customer_id}},{{$transaction->id}}"><i class="material-icons" data-toggle="tooltip" title="Delete" data-placement="top">delete_outline</i></a>
                    @endif
                  </td>
                 
                </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
    {{$transactions->links()}}  

    <!------------------------edit deposit customer --------------------->
<div class="modal fade" id="edit_depositModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.edit_deposit')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Customer Update Investment</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="edit_deposit-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-sm">Update</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!------------------------------------------------------------------->

<!------------------------edit withdraw customer --------------------->
<div class="modal fade" id="edit_withdrawModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.edit_withdraw')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Customer Update Withdrawl</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="edit_withdraw-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-sm">Update</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!------------------------------------------------------------------->

<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerDeleteDepositWithdraw')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Delete Customer</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="delete_customer-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-xs">Delete</button>
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

<script type="text/javascript">
  $('.edit_deposit').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.edit_depositForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#edit_deposit-container').html(data);
        $('#edit_depositModal').modal('show');
      
    }
  });
});
  $('.edit_withdraw').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.edit_withdrawForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#edit_withdraw-container').html(data);
        $('#edit_withdrawModal').modal('show');
      
    }
  });
});

$('.delete_confirm').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.delete_deposit_withdraw")}}',
    type:'post',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_customer-container').html(data);
        $('#delete_customer').modal('show');
      
    }
  });
});
</script>
<!-- <script>($transaction->customertransactions as $txn) ($txn->is_month_payout_generate) disabled edit_deposit 
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




         
