var checkout_DZ = {
	getCSCAction : function(){
	alert('adf');

		$("body").on( 'blur','.get-csc', checkout_DZ.getCSC );
	},
	getCSC : function(){
		var elem = $(this);
		var zip = $(this).val();
		var token = $('meta[name="csrf-token"]').attr('content');
		if(zip.length == 6){
			$.ajax({
				type: 'POST',
				data: 'zipcode=' + zip + '&_token=' + token,
		        url: _checkout_config.get_csc_url,
		        dataType: 'json',
		        success: checkout_DZ.getCSCSuccess.bind(this),
		        error: checkout_DZ.getCSCFail
			});
		}else{
			checkout_DZ.getCSCFail;
		}
	},
	getCSCFail : function(error){
		alertify.alert('Error!', 'Invalid Zipcode');
	},
	getCSCSuccess : function(data){
		if(data != null && data.country != null){
			$('#'+$(this).data('target-country')).html('<option value="'+data.country+'">'+data.country_name+'</option>');
			$('#'+$(this).data('target-state')).html('<option value="'+data.state+'">'+data.state_name+'</option>');
			$('#'+$(this).data('target-city')).html('<option value="'+data.city+'">'+data.city_name+'</option>');
		}else{
			checkout_DZ.getCSCFail;
		}
	},
	initValidate : function(){
		$.validate({
	        modules : 'location, date, security, file, logic',
	        onModulesLoaded : function() {

	        }
	    });
	},
	init : function(){
      this.getCSCAction();
      this.initValidate();
    }
}


$(document).ready(function() {
  checkout_DZ.init();
  coupon_DZ.init();
  use_Shipping.init();
  use_Billing.init();
  if(getCSC){
  	$('.get-csc:not(.ignore-init)').trigger('blur');
  }
  if(getBi llingCSC){
  	$('.get-csc.ignore-init').trigger('blur');
  }
  if(getCoupon){
  	$('#'+_coupon_config.submit_btn).trigger('click');
  }


});