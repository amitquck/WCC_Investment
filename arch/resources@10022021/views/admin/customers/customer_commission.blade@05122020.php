

@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page"> Customer Commission</li>
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
<!-----------------------------add customer---------------------------------------------->
<div class="card card-style">
   <div class="container">
      <form action="{{url('admin/customer/commissionstore',[$customer->id])}}" method="post" >
      {{ csrf_field() }}
         <div class="col-md-12 maini">
            <div class="mania">
               <div class="row">
                  <div class="col-md-6 form-group">
                     <label for="customer">Customer <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" onChange="calcomm(this.value)" id="customer" class="form-control customer" name="customer_invest" placeholder="Enter Customer Invest Percentage"   value="{{ old('customer_invest') }}">
                     @if ($errors->has('customer_invest'))
                              <span class="help-block text-danger d-block">
                              <strong>{{ $errors->first('customer_invest') }}</strong>
                              </span>
                              @endif
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="customer">Sum Of Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" id="sum_of_commission" name="sum_of_commission" class="form-control sum_of_commission" placeholder="Enter Customer Commission Percentage" readonly="" style="width:86%">
                  </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 1 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]"  id="associate" class="form-control associate"   placeholder="Associate One" >                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"   placeholder="Associate One">
                     @error('associate.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{ $message}} </strong>
                              </span>
                     @enderror
                     
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                        @error('commission.*')
                            <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                        @enderror               </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 2 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Two">                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Two">
                     @error('associate.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                     @error('commission.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror               </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div ">
                     <label for="associate">Associate 3 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Three">                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Three">
                     @error('associate.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""  id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                     @error('commission.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror               </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 4 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Four">                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Four">
                     @error('associate.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                     @error('commission.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror               </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 5 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Five">                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Five">
                     @error('associate.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                     @error('commission.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror               </div>
               </div>
               <div class="row associate-row">
                  <div class="col-md-6 form-group associate-div">
                     <label for="associate">Associate 6 <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value="" name="associate_name[]" id="associate" class="form-control associate"  placeholder="Associate Six">                  
                     <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">
                     @error('associate.*')
                        <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror
                  </div>
                  <div class="col-md-6 form-group">
                     <label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label>
                     <input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">
                     @error('commission.*')
                         <span class="help-block text-danger d-block">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror               
                  </div>
               </div>
            </div>
            <div class="form-group sub-btn">
               <button class="btn btn-info col-md-1 " style="margin-left:46%" type="submit">Submit</button>
               <?php /*<i class="btn btn-primary col-md-1 float-right add-more" id="add-more" ><i class="material-icons">add</i></i>*/ ?>
            </div>
         </div>
      </form>
   </div>

</div>



<!---------------------------------------------------------------------------->
@endsection
@section('page_js')
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


<script>

 $(document).ready(function() {
    associateauto();
	var max_fields      = 15; //maximum input boxes allowed
	var wrapper   		= $(".mania"); //Fields wrapper
	var add_button      = $(".add-more"); //Add button ID
	
   var numberWords = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','twelve','Thirteen','Fourteen','Fifteeen'];

	var x = 6; //initlal text box count
	$(add_button).click(function(e){
      // alert(x);
       //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="row associate-row"><div class="col-md-6 form-group associate-div"><label for="associate">Associate '+x+' <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value="" id="associate" class="form-control associate ui-autocomplete-input"  placeholder="Associate '+numberWords[x-1]+'" name="associate_name[]"><input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">@error("associate.*") <span class="help-block text-danger d-block"><strong>{{$message}}</strong></span>@enderror</div> <div class="col-md-6 form-group"><label for="commission">Commission <sup class="" style="color:red;font-size:14px;" >*</sup></label><input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">  <a class="remove_field btn btn-danger pt-2 mt-1">--</a> @error("commission.*") <span class="help-block text-danger d-block"> <strong>{{$message}}</strong></span>@enderror</div></div>');
         associateauto(); 
		}
      if(max_fields == x){
         $(".add-more").css('display','none');
      }
	});
	
	$(wrapper).on("click",".remove_field", function(e){ 
      if(x <= max_fields){
         $(".add-more").css('display','block');
      }
      //user click on remove text
		e.preventDefault(); $(this).parents('.associate-row').remove(); x--;
      
	})
  
});
function calcomm(elem){
   return $('#sum_of_commission').val(elem);
}
$(document).on("keyup", ".commission", function() {
    var sum = total = 0;
    $(".commission").each(function(){
        sum += +$(this).val();
    });
   // var s = calcomm();
   var s =   $("#customer").val();
// alert(s);var 
   total = (parseInt(s) + parseInt(sum));   
    $("#sum_of_commission").val(total);
    if(total > 36){
       alert('Total Sum of Commission Not Greater Than 36');
       $('#customer').val(0);
       $('#sum_of_commission').val(0);

       $('.commission').each(function(){
          $(this).val(0);
       });
       return false;
    }
    return true;
});
   



 function associateauto() {
    
   //  var data = $('.main').parents('.associate-div').find('.associate').val();
   //  alert(data);
     $('.associate').autocomplete({
        source:"{{url('admin/customer/commission/associate/autocomplete')}}",
              select: function( event, ui ) {
               //   console.log(ui.item);
      //   ( "Selected: " + ui.item.value );
            $(this).next().val(ui.item.id);
      //   $('.associate-row').parents('.maini').find('.associate_id').attr("value", ui.item.id);

      }

    });
    return false;
            
}
</script> 
@endsection

