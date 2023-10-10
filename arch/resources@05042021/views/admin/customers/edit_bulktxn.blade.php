@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb breadcrumb-style2">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customer Edit Bulk Transaction</li>
</ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')	
@php
    $params = [];
    if(Request::has('created_at'))
         $params = array_merge(['created_at' => Request::input('created_at')],$params);
  @endphp
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
          <label for="date">Entry Date <sup class="text-danger">*</sup></label>
          <input type="text" name="created_at" class="form-control datepicker" placeholder="Transaction Entry Date">
        </div>
        <div class="col-md-1">
          <label style="padding-top:16px;"></label>
          <input type="submit" name="search" class="btn btn-primary btn-md" id="on_search" value="search">
        </div>
    </div>
  </div><br>
</form>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
    <form method="post" action="{{route('admin.update_bulk_txn')}}">
      <table class="table table-nostretch table-align-middle mb-0">
        <div class="">
            <thead>
              <tr>
                <th>
                    <label style="padding-top: 35px;">Deposit Date <sup class="text-danger">*</sup></label>
                    
                </th>
                <th>
                    <label>Number of entries</label>
                    @if($customer_txn)
                    <input type="text" name="total_entry" id="total_entry" class="form-control" placeholder="No Of Entries" value="{{$customer_txn->count()}}">
                    @endif
                </th>
                <!-- <th>
                    <button class="btn btn-primary btn-sm add-more" style="margin-top: 20px;">Create</button>
                </th> -->
              </tr>
            </thead>
        </div>
        <tbody>
          @if($customer_txn)
            @foreach($customer_txn as $key => $txn)
              <tr>
                <th>
                  <input type="text" name="deposit_date[]" class="form-control" value="{{date('d-m-Y',strtotime($txn->deposit_date))}}" readonly="readonly">
                </th>
                <th>
                  <input type="hidden" name="table_id[]" class="form-control" value="{{$txn->id}}">
                  <input type="hidden" name="respective_table_id[]" class="form-control" value="{{$txn->respective_table_id}}">
                  <select class="form-control" name="transaction_type[]" id="select_payment" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                      <option value="">Select Type</option>
                      <option value="deposit" @if($txn->transaction_type == 'deposit') selected="selected" @endif>Investment</option>
                      <option value="withdraw" @if($txn->transaction_type == 'withdraw') selected="selected" @endif>Withdrawl</option>
                  </select>
                </th>
                <td>
                  <input type="text" name="" class="form-control cust_search" placeholder="Customer" id="cust_search" autocomplete="off" value="{{$txn->user->name.' ('.$txn->user->code.')'}}" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                  <input type="hidden" name="customer_id[]" id="hidden_cust_search" class="hidden_cust_search" value="{{$txn->customer_id}}">
                </td>
                <td>
                  <input type="text" name="amount[]" class="form-control" placeholder="Amount (Deposit/Withdrawl)" value="{{$txn->amount}}" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                </td>
                <td>
                   <select class="form-control" name="payment_type[]" onChange="payment(this)" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                      <option value="cash"@if($txn->transaction_type == 'cash') selected="selected" @endif>Cash</option>
                      <option value="cheque"@if($txn->transaction_type == 'cheque') selected="selected" @endif>Cheque</option>
                      <option value="dd"@if($txn->transaction_type == 'dd') selected="selected" @endif>DD</option>
                      <option value="NEFT"@if($txn->transaction_type == 'NEFT') selected="selected" @endif>NEFT</option>
                      <option value="RTGS"@if($txn->transaction_type == 'RTGS') selected="selected" @endif>RTGS</option>
                   </select>
                </td>
                <td style="display:none;" class="payment_mode">
                  <select name="bank_id[]" class="form-control" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                      <option value="">Select Bank Name</option>
                      @foreach($banks as $bank)           
                      <option value="{{$bank->id}}"@if($txn->bank_id == $bank->id) selected="selected" @endif>{{$bank->bank_name}}</option>
                      @endforeach       
                  </select>
                   &nbsp;<input type="text" class="form-control"  name="cheque_dd_number[]" id="cheque_dd_number" placeholder="Cheque/DD Number" value="{{$txn->cheque_dd_number}}" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>&nbsp;<input type="hidden" class="form-control"  name="date[]" id="date" placeholder="Enter date" data-toggle="tooltip" title="Cheque/DD Date" value="{{$txn->cheque_dd_date}}" @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>
                </td>
                <td>
                  <textarea name="remarks[]" rows="1" class="form-control" placeholder="Remarks..." @if(!$txn->is_month_payout_generate)  @else disabled="disabled" @endif>{{$txn->remarks}}</textarea>
                </td>
                <td>
                  <a class="@if(!$txn->is_month_payout_generate) remove_field @else disabled @endif btn text-danger pt-2 mt-1" data-id="{{$txn->id}},{{$txn->customer_id}},{{$txn->respective_table_id}}" data-toggle="tooltip" title="{{ucwords($txn->transaction_type)}} Delete"><i class="material-icons">delete</i></a>
                </td>
              </tr>
              @if($key+1 == $customer_txn->count())
              <tr>
                <td>
                <button class="btn btn-md btn-primary" id="submit" style="margin-top: 15px;margin-left: 15px;" >Submit</button>
                </td>
                <td>
                <a @if(!$txn->is_month_payout_generate) href="{{route('admin.delete_all_bulktxn',$params)}}" @endif style="margin-top: 15px;" class="btn btn-danger">Delete All</a>
                </td>
              </tr>
              @endif
            @endforeach
          @endif
        </tbody>
      </table>
    </form><br>
    </div>
  </div>


<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.deleteOneInBulkTxn')}}" method="POST">
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
  
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
$('.datepicker').datepicker({
  startDate: '-3d',
  dateFormat: 'dd-mm-yy'
});


autocomplete_customer();
// payment();,"selectPayment"


// function autocomplete_customer(){
//     $('.cust_search').autocomplete({
//         source:function(request, response) {
//           $.getJSON("{{route('admin.getCustomers')}}", { selectPayment: this.element.val() }, 
//                     response);
//         },
//         minLength: 2,
//         select:function(event,ui){
//             // $('#cust_search').val(ui.item.name);
//             // $('#hidden_cust_search').val(ui.item.id);
//             $(this).next().val(ui.item.id);
//         }
//     });
// }




function autocomplete_customer(){
  $('.cust_search').autocomplete({
        source:"{{route('admin.getCustomers')}}",
        minLength: 2,
        select:function(event,ui){
            // $('#cust_search').val(ui.item.name);
            // $('#hidden_cust_search').val(ui.item.id);
            $(this).next().val(ui.item.id);
        }
    });  

}
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
function payment(elem)
{
  // alert('sgd');      
  if($(elem).val()=='cash')
  {
    $(elem).parents('tr').find('.payment_mode').hide();//.parents('tr').find('.payment_mode')
  }
  else if($(elem).val()== '')
  {
    $(elem).parents('tr').find('.payment_mode').hide();
  }
  else
  {
  $(elem).parents('tr').find('.payment_mode').show();
  }
}

$(document).ready(function(){
    var entity = $('#total_entry').val();
    // if(entity <= 0){
    //     $('#submit').prop( "disabled", true );
    // }else{
    //     $('#submit').prop( "disabled", false );
    // }
});

$('.remove_field').click(function(){
  var id = $(this).data('id');
  // var id = ids.split(',');
  // alert(id[0]);
  // alert(id[1]);
  $.ajax({
    url:'{{route("admin.deleteOneInBulkTxnForm")}}',
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
@endsection