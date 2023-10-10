@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Transaction List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  <div class="card card-style-1 mt-3">
  <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
    <h6 class="text-center">{{($user->name) .' ('.$user->code.')' }}</h6>
  </div>  
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
          </tr>
        </thead>
        <tbody>
            @if($transactions->count()>0)
                @foreach($transactions as $key => $transaction)
                    <tr>
                      <td>{{$key+1}}.</td>
                      <td class="text-success">₹ {{($transaction->transaction_type == 'commission')?$transaction->sum_monthly_commission:$transaction->amount}}</td>
                      <td>{{$transaction->transaction_type}}</td>
                    <td>
                      @if($transaction->payment_type == 'null')
                        {{'N/A'}}
                      @else
                      {{$transaction->payment_type}}
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
                    <td>{{Carbon\Carbon::parse($transaction->deposit_date)->format('j-m-Y')}}
                    </td>
                    <td>
                    {{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
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




         
