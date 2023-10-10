

@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Add Customer Investment Amount</li>
   </ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')
@section('admin_head')
<style>

.form-control {
    display: inline-block !important;
    width: 86%;
}

</style>
@endsection

<!------------------------customer investment------------------------->

<form action="{{url('admin/customer/addInvestment',[encrypt($customer->id)])}}" method="post" >
<div class="card card-style">
  <div class="text-center" style="background-color:#167bea; color: white;">{{ucwords($customer->name).' ('.$customer->code.')'}}</div>
  <div class="col-md-12">&nbsp;
    <div class="row">
      <div class="form-group col-md-6">
        <label for="amount">Deposit Amount <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter amount">
        @if ($errors->has('amount'))
          <span class="help-block text-danger d-block">
            <strong>{{ $errors->first('amount') }}</strong>
          </span>
        @endif
      </div>
      <div class="form-group col-md-6">
         <label for="deposit_date">Deposit Date <span class="text-danger">*</span></label>
         <input type="text" class="form-control datepicker" name="deposit_date" id="deposit_date" placeholder="dd-mm-yyyy">
        @if ($errors->has('deposit_date'))
          <span class="help-block text-danger d-block">
            <strong>{{ $errors->first('deposit_date') }}</strong>
          </span>
        @endif
      </div>
    </div>
  </div>

         

  <div class="col-md-12">
    <div class="row">
      <div class="form-group col-md-6">
         <label for="name">Payment Type<span class="text-danger">*</span></label>
         <select name="payment_type" class="form-control" onChange="payment(this)">
           <option value="">Select</option>
           <option value="cash">Cash</option>
           <option value="cheque">Cheque</option>
           <option value="dd">DD</option>
           <option value="NEFT">NEFT</option>
           <option value="RTGS">RTGS</option>
         </select>
        @if ($errors->has('payment_type'))
          <span class="help-block text-danger d-block">
            <strong>{{ $errors->first('payment_type') }}</strong>
          </span>
        @endif
      </div>
      <div class="form-group col-md-6">
        <label for="remarks">Remarks<span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter remarks">
        @if ($errors->has('remarks'))
          <span class="help-block text-danger d-block">
            <strong>{{ $errors->first('remarks') }}</strong>
          </span>
        @endif
      </div>
    </div>
  </div>
  <div class="col-md-12 payment_mode" style="display:none;">
    <div class="row">
      <div class="form-group col-md-6">
        <label for="bank_id">Bank Name</label>
        <select name="bank_id" class="form-control" id="bank_name">
        <option value="">Select Bank</option>
        @foreach($companyBank as $bank)
          <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
        @endforeach
      </select>
        
      </div>
      <div class="form-group col-md-6">
         <label for="cheque_dd_number">Cheque/DD/NEFT/RTGS Number</label>
         <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
      </div>
      <div class="form-group col-md-6">
         <label for="date">Cheque/DD Date</label>
         <input type="text" class="form-control datepicker" name="date" id="date" placeholder="Enter date">
        
      </div>
    </div>
  </div>
<!-------------------------------------------------------------------->

<div class="form-group sub-btn">
   <button class="btn btn-info col-md-1 " style="margin-left:42.5%" type="submit">Submit</button>
   <?php /*<i class="btn btn-primary col-md-1 float-right add-more" id="add-more" ><i class="material-icons">add</i></i>*/ ?>
</div>
</div>

<!-----------------------------add customer---------------------------------------------->

</form>


<!---------------------------------------------------------------------------->
@endsection
@section('page_js')
<?php /*
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script src="{{asset('js/script.min.js')}}"></script>
<script src="{{asset('js/app.min.js')}}"></script>
<script src="{{asset('css/jquery-ui.css')}}"></script>
<!-- Plugins -->
<script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('plugins/clockpicker/bootstrap-clockpicker.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
*/ ?>

<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat: 'dd-mm-yy'
  });

  function payment(elem){   
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

//  $(document).ready(function() {
//     associateauto();
// 	var max_fields      = 15; //maximum input boxes allowed
// 	var wrapper   		= $(".mania"); //Fields wrapper
// 	var add_button      = $(".add-more"); //Add button ID
	
//    var numberWords = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','twelve','Thirteen','Fourteen','Fifteeen'];

// 	var x = 6; //initlal text box count
// 	$(add_button).click(function(e){
//       // alert(x);
//        //on add input button click
// 		e.preventDefault();
// 		if(x < max_fields){ //max input box allowed
// 			x++; //text box increment
// 			$(wrapper).append('<div class="row associate-row"><div class="col-md-6 form-group associate-div"><label for="associate">Associate '+x+' <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value="" id="associate" class="form-control associate ui-autocomplete-input"  placeholder="Associate '+numberWords[x-1]+'" name="associate_name[]"><input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">@error("associate.*") <span class="help-block text-danger d-block"><strong>{{$message}}</strong></span>@enderror</div> <div class="col-md-6 form-group"><label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">  <a class="remove_field btn btn-danger pt-2 mt-1">--</a> @error("commission.*") <span class="help-block text-danger d-block"> <strong>{{$message}}</strong></span>@enderror</div></div>');
//          associateauto(); 
// 		}
//       if(max_fields == x){
//          $(".add-more").css('display','none');
//       }
// 	});
	
// 	$(wrapper).on("click",".remove_field", function(e){ 
//       if(x <= max_fields){
//          $(".add-more").css('display','block');
//       }
//       //user click on remove text
// 		e.preventDefault(); $(this).parents('.associate-row').remove(); x--;
      
// 	})
  
// });
// function calcomm(elem){
//    return $('#sum_of_commission').val(elem);
// }
// $(document).on("keyup", ".commission", function() {
//     var sum = total = 0;
//     $(".commission").each(function(){
//         sum += +$(this).val();
//     });
//    // var s = calcomm();
//    var s =   $("#customer").val();
// // alert(s);var 
//    total = (parseFloat(s) + parseFloat(sum));   
//     $("#sum_of_commission").val(total);
//     if(total > 36){
//        alert('Total Sum of Commission Not Greater Than 36');
//        $('#customer').val(0);
//        $('#sum_of_commission').val(0);

//        $('.commission').each(function(){
//           $(this).val(0);
//        });
//        return false;
//     }
//     return true;
// });
   



// function associateauto() {
    
//    //  var data = $('.main').parents('.associate-div').find('.associate').val();
//    //  alert(data);
//      $('.associate').autocomplete({
//         source:"{{url('admin/customer/commission/associate/autocomplete')}}",
//               select: function( event, ui ) {
//                //   console.log(ui.item);
//       //   ( "Selected: " + ui.item.value );
//             $(this).next().val(ui.item.id);
//       //   $('.associate-row').parents('.maini').find('.associate_id').attr("value", ui.item.id);

//       }

//     });
//     return false;
            
// }



</script> 
@endsection

