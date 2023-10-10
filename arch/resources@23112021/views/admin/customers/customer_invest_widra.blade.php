
@extends('layouts.admin.default')
@section('content')
<div class="grid-form1">
  	     
<div class="tab-content">
		
						<div class="tab-pane active" id="horizontal-form">
							<form class="form-horizontal" action="#" method="post" enctype="multipart/form-data">
							   <h4 style="color:#CC3333">Customer Investment</h4>
							   <div class="form-group">
									
									<label for="focusedinput" class="col-sm-2 control-label">Withdraw/Deposit<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<select  name="ptype" id="ptype" class="form-control1">
										<option value="1">Deposit</option>
										<option value="2">Withdraw</option>
										</select>
									</div>
								</div>
								<script>
								    function showUser(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","getuser.php?q="+str,true);
        xmlhttp.send();
    }
}
								</script>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Customer Id<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
									<input list="browsers" name="customer_id" id="customer_id" class="form-control"  onblur="showUser(this.value);" required >

									<datalist id="browsers">
																		  <option value="test1">[ dummy ]</option>
									 									  <option value="test2">[ dummy ]</option>
									 									</datalist>
									<br>
								<div id="txtHint"><b></b></div>
									</div>
									<label for="focusedinput" class="col-sm-2 control-label">Investment Date <span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<input name="investment_date" type="date" required class="form-control" value="2020-12-03" id="titles" />
									</div>
									
								</div>
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label"> Amount<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<input  type="text" class="form-control" name="investment_amt" id="investment_amt" required>
                  
									
									</div>
									<label for="focusedinput" class="col-sm-2 control-label">Payment Mode<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<select  name="mode" id="mode" class="form-control1" onchange="show(this.vlaue)">
										<option value="1">CASH</option>
										<option value="2">CHEQUE/OTHER</option>
										</select>
									</div>
								</div>
								
								<div class="form-group" style="display: none" id="myP">
									<label for="focusedinput" class="col-sm-2 control-label">Cheque No/Ref.no.<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<input  type="text" class="form-control" name="cheque_no" id="cheque_no">
                  
									
									</div>
									
									<label for="focusedinput" class="col-sm-2 control-label">Dated<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<input  type="text" class="form-control" name="dated" id="dated">
                  
									
									</div>
								</div>
								
								<div class="form-group">
									<label for="focusedinput" class="col-sm-2 control-label">Bank<span style="color:#CC3333">*</span></label>
									<div class="col-sm-4">
										<select class="form-control" name="bank_dep_wid">
										    <option>Please Select</option>
										                        					      <option value="cash">cash</option>
                    						                    					      <option value="PNB">PNB</option>
                    																    
										</select>
									</div>
										<label for="focusedinput" class="col-sm-2 control-label">Remark</label>
									<div class="col-sm-4">
										<input  type="text" class="form-control" name="remark" id="remark">
                  
									
									</div>
									
								</div>
								
								
								
<script>
function show(frm_val)
{
var val=document.getElementById("mode").value;
if(val=="2")
document.getElementById("myP").style.display = "block";
else
document.getElementById("myP").style.display = "none";
}
</script>						
								
								
								
								
								
								 <div class="panel-footer">
									<div class="row">
										<div class="col-sm-12 col-sm-offset-2">
										<input type="submit" class="btn-primary btn" onclick="return confirm('Are you Sure Submit!');" value="+ Save Record" />
										</div>
									</div>
								 </div>
                            <input name="hdnsave" type="hidden" id="hdnsave" value="1">
												
</form>
						</div>
					</div>
					</div>

@endsection