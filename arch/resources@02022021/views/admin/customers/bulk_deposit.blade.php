@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb breadcrumb-style2">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customer Bulk Deposit</li>
</ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')	
  
 <div class="card card-style-1 mt-3">
    <div class="table-responsive">
    <form method="post" action="{{route('admin.bulk_submit_transactions')}}">
      <table class="table table-nostretch table-align-middle mb-0">
        <div class="">
            <thead>
              <tr>
                <th>
                    <label>Deposit Date <sup class="text-danger">*</sup></label>
                    <input type="date" name="deposit_date" class="form-control" value="{{date('Y-m-d')}}">
                </th>
                <th>
                    <label>Number of entries</label>
                    <input type="text" name="total_entry" id="total_entry" class="form-control" placeholder="No Of Entries">
                </th>
                <th>
                    <button class="btn btn-primary btn-sm add-more" style="margin-top: 20px;">Create</button>
                </th>
              </tr>
            </thead>
        </div>
        <tbody>
            <tr></tr>
        </tbody>
      </table>
      <button class="btn btn-md btn-primary" id="submit" style="margin-top: 15px;">Submit</button>
    </form>
    </div>
  </div>
@endsection
@section('page_js')

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>


 $(document).ready(function() {
   var wrapper       = $("tbody"); //Fields wrapper
   var add_button      = $(".add-more"); //Add button ID
   
   var numberWords = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','twelve','Thirteen','Fourteen','Fifteeen'];
   var max_fields      = $('#total_entry').val();
   var x = 1; //initlal text box count
   $(add_button).click(function(e){
   var max_fields      = $('#total_entry').val(); //maximum input boxes allowed
   // alert(max_fields);
      // alert(x);
       //on add input button click
      e.preventDefault();
      while(x <= max_fields){ //max input box allowed
         x++; //text box increment
         $(wrapper).append('<tr class="create-new"><th><select class="form-control" name="transaction_type[]"><option value="">Select Type</option><option value="deposit" selected="selected">Investment</option><option value="withdraw">Withdrawl</option></select></th><td><input type="text" name="" class="form-control cust_search" placeholder="Customer" id="cust_search" autocomplete="off"><input type="hidden" name="customer_id[]" id="hidden_cust_search" class="hidden_cust_search"></td><td><input type="text" name="amount[]" class="form-control" placeholder="Amount (Deposit/Withdrawl) "></td><td><select class="form-control" name="payment_type[]" onChange="payment(this)"><option value="cash">Cash</option><option value="cheque">Cheque</option><option value="dd">DD</option><option value="NEFT">NEFT</option><option value="RTGS">RTGS</option></select></td><td style="display:none;" class="payment_mode"><select name="bank_id[]" class="form-control ">         <option value="">Select Bank Name</option>         @foreach($banks as $bank)           <option value="{{$bank->id}}">{{$bank->bank_name}}</option>         @endforeach       </select>&nbsp;<input type="text" class="form-control"  name="cheque_dd_number[]" id="cheque_dd_number" placeholder="Cheque/DD Number">&nbsp;<input type="hidden" class="form-control"  name="date[]" id="date" placeholder="Enter date" data-toggle="tooltip" title="Cheque/DD Date" value="<?php echo date('Y-m-d')?>"></td><td><textarea name="remarks[]" rows="1" class="form-control" placeholder="Remarks..."></textarea></td><td><a class="remove_field btn text-danger pt-2 mt-1"><i class="material-icons">delete</i></a></td></tr>');
         autocomplete_customer();
         // payment();
      }
      //autocomplete_customer();//payment_mode  style="display:none;"
      if(max_fields == x){
         $(".add-more").css('display','none');
      }
   });
   
   $(wrapper).on("click",".remove_field", function(e){ 
    // alert(max_fields);
      if(x <= max_fields){
         $(".add-more").css('display','block');
      }
      //user click on remove text
      e.preventDefault(); $(this).parents('.create-new').remove(); x--;
      
   })
  
});
autocomplete_customer();
// payment();

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
</script>
@endsection