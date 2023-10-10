

@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
   <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Commission</li>
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

<form action="{{url('admin/customer/commissionstore',[encrypt($customer->id)])}}" method="post" >
<div class="card card-style">
  <div style="background-color:#167bea; color: white;">&nbsp;Edit Customer Commission</div>
   <div class="container">
      {{ csrf_field() }}
         <div class="col-md-12 maini">
            <div class="mania">&nbsp;
               <div class="row">
                  <div class="col-md-5 form-group">
                     <label for="customer">Customer <span class="text-danger">*</span></label>
                     <input type="text" onChange="calcomm(this.value)" id="customer" class="form-control customer" name="customer_invest" placeholder="Enter Customer Invest Percentage"   value="{{ $customer_interest_percent->interest_percent }}" readonly="">
                     @if ($errors->has('customer_invest'))
                        <span class="help-block text-danger d-block">
                          <strong>{{ $errors->first('customer_invest') }}</strong>
                        </span>
                      @endif
                  </div>
                  <div class="col-md-5 form-group">
                     <label for="customer">Sum Of Commission</label>
                     <input type="text" value="{{$customer_interest_percent->interest_percent+$customer->associatecommissions->where('end_date',NULL)->where('status',1)->sum('commission_percent')}}" id="sum_of_commission" name="sum_of_commission" class="form-control sum_of_commission" placeholder="Enter Customer Commission Percentage" readonly="" style="width:86%">
                  </div>
                  <div class="col-md-1" style="padding-top:4%">
                    <a class="btn  btn-default bigger-130 text-danger editInterest" data-id ="{{ $customer->id }}"><i class="material-icons text-info">edit</i></a>
                  </div>
                  <!-- <div class="col-md-1" style="padding-top:4%">
                    <a href=""
                        class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" 
                        title="Delete"><i class="material-icons">delete_outline</i></a>
                  </div> -->
                  
               </div>
                @foreach($customer->associatecommissions->where('end_date',NULL)->where('status',1) as $key => $commission)
                  <div class="row associate-row">
                    <div class="col-md-5 form-group associate-div">
                       <label for="associate">Associate</label>
                       <input type="text" value="{{$commission->associate->name}}" name="associate_name[]"  id="associate" class="form-control associate"   placeholder="Associate One" readonly="">
                       <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"   placeholder="Associate One" readonly="">
                    </div>
                    <div class="col-md-5 form-group">
                       <label for="commission">Commission</label>
                       <input type="text" value="{{$commission->commission_percent}}" id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission " readonly="">
                    </div>
                    <div class="col-md-1" style="padding-top:4%">
                      <a class="btn btn-default bigger-130 text-danger editAssociateWithdrawl" data-id="{{ $customer->id }},{{$commission->associate->id}}"><i class="material-icons text-info">edit</i></a>
                    </div>
                    <!-- <div class="col-md-1" style="padding-top:4%">
                      <a href=""
                        class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" 
                        title="Delete"  data-id="{{ $customer->id }},{{$commission->associate->id}}"><i class="material-icons">delete_outline</i></a>
                    </div> -->
                    <!-- {{('admin.associateCommissionDelete')}} -->
                  </div>
                @endforeach
            </div>
            <div class="form-group sub-btn">
               <button class="btn btn-info col-md-1 " style="margin-left:42.5%" type="submit">Submit</button>
               <button class="btn btn-info btn-sm float-right add-more" id="add-more"><i class="material-icons">add</button></i>
            </div>
         </div>
      
   </div>
  </div>
</div>
<hr>

</form>
<!------------------------customer withdraw form--------------------->
<div class="modal fade" id="interestModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.editInterest')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add New Customer Interest Rate</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="interest-container">
          
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

<!------------------------Associate withdraw form--------------------->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.editCommission')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add New Associate Commission Rate</h6>
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

<!---------------------------------------------------------------------------->
@endsection
@section('page_js')

<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>

 $(document).ready(function() {
    associateauto();
   var max_fields      = 15; //maximum input boxes allowed
   var wrapper       = $(".mania"); //Fields wrapper
   var add_button      = $(".add-more"); //Add button ID
   
   var numberWords = ['One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','twelve','Thirteen','Fourteen','Fifteeen'];

   var x = 6; //initlal text box count
   $(add_button).click(function(e){
      // alert(x);
       //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
         x++; //text box increment
         $(wrapper).append('<div class="row associate-row"><div class="col-md-5 form-group associate-div"><label for="associate">Associate</label><select class="form-control" name="associate_name[]">                        <option value="">Select Associate</option>                        @foreach($associate as $associat)                           <option value="{{$associat->id}}">{{$associat->name.'-'.$associat->code}}</option>                        @endforeach                     </select>         <input type="hidden" name="associate[]" value="" id="associate_id" class="form-control associate_id"  placeholder="Associate Six">@error("associate.*") <span class="help-block text-danger d-block"><strong>{{$message}}</strong></span>@enderror</div> <div class="col-md-5 form-group"><label for="commission">Commission</label><input type="text" value=""   id="commission" name="commission[]" class="form-control commission" placeholder="Enter Commission ">  <a class="remove_field btn btn-danger pt-2 mt-1">--</a> @error("commission.*") <span class="help-block text-danger d-block"> <strong>{{$message}}</strong></span>@enderror</div></div>');
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

$('.editInterest').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.editInterestForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#interest-container').html(data);
        $('#interestModal').modal('show');
      
    }
  });
});

$('.editAssociateWithdrawl').click(function(){
  var id = $(this).data('id');
  // var associate_id = $(this).data('associate_id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.editAssociateWithdrawlForm")}}',
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

