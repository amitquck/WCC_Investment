@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Company Bank List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  <div class="col-md-12">
    <div class="row">
        @if(empcan('add_company_bank') || Auth::user()->login_type == 'superadmin')
            <div class="col-md-9">
                <button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addbank">Add Bank</button>
            </div>
        @endif
        @if(Auth::user()->login_type == 'superadmin')
            <div class="col-md-1">
                <span data-toggle="modal" data-target="#cranddr">
                    <a data-toggle="tooltip" title="Add Deposit & Deduction" class="btn btn-primary text-white"><i class="material-icons">exposure</i></a>
                </span>
            </div>
            <div class="col-md-1">
                <span data-toggle="modal" data-target="#money-transfer">
                    <a data-toggle="tooltip" title="Money Transfer" class="btn btn-warning"><i class="material-icons">account_balance</i></a>
                </span>
            </div>
        @endif
        @if(empcan('export_company_bank') || Auth::user()->login_type == 'superadmin')
            <div class="col-md-1">
                <a href="{{route('admin.excel_company_bank')}}" class="btn btn-success pull-right" data-toggle="tooltip" title="Excel Export"><i class="material-icons">import_export</i></a>
            </div>
        @endif
    </div>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Bank Name</th>
            <th scope="col">Opening Balance</th>
            <th scope="col">Balance</th>
            <th scope="col">Created At</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($banks->count()>0)
            @foreach($banks as $key =>$bank)
              <tr>
                <td>{{($banks->currentpage()-1)*$banks->perpage()+$key+1}}.</td>
                <td>{{$bank->bank_name}}</td>
                <td>
                  @if($bank->amount > 0)
                  <p class="text-success">₹ {{$bank->amount}}</p>
                  @else
                  <p class="text-danger">₹ {{$bank->amount}}</p>
                  @endif
                </td>
                <td>
                  @if($bank->balance->balance > 0)
                    <p class="text-success">₹ {{$bank->balance->balance?$bank->balance->balance:'0.00'}}</p>
                  @else
                    <p class="text-danger">₹ {{$bank->balance->balance?$bank->balance->balance:'0.00'}}</p>
                  @endif
                </td>
                <td>{{Carbon\Carbon::parse($bank->created_at)->format('j-m-Y')}}</td>
                <td>
                  @if(empcan('view_transaction_company_bank') || Auth::user()->login_type == 'superadmin')
                    <a href="{{url('admin/perticularBankTransaction/'.encrypt($bank->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewBankTransaction" data-toggle="tooltip" title="View Bank Transaction"  data-id="{{$bank->id}}"><i class="material-icons">visibility</i></a>
                  @endif
                  @if(empcan('edit_company_bank') || Auth::user()->login_type == 'superadmin')
                    <a href="{{url('admin/editBank/'.encrypt($bank->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-success" data-toggle="tooltip" title="Edit" data-id="{{$bank->id}}" ><i class="material-icons">edit</i></a>
                  @endif
                  @if(Auth::user()->login_type == 'superadmin')
                    <a class="btn btn-default btn-link btn-icon bigger-130 text-danger delete_confirm" data-toggle="tooltip" title="Delete" data-id="{{$bank->id}}" ><i class="material-icons">delete</i></a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
     {{$banks->links()}}
<!---------------------------------------Add Bank ------------------------------->
<div class="modal fade" id="addbank" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/addBank') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Company Bank</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="add-transaction-container">
          <div class="col-md-12">
            <div class="row">
            <label for="bank_name">Bank Name <sup class="text-danger">*</sup></label>
            <input type="text" name="bank_name" class="form-control" style="width:100%;" required="required" placeholder="Enter Bank Name">
              @if($errors->has('bank_name'))
                <p style="color:red;">{{ $errors->first('bank_name') }}</p>
              @endif
            </div>
          </div><br>
          <div class="col-md-12">
            <div class="row">
              <label for="amount">Opening Balance <sup class="text-danger">*</sup></label>
              <input type="text" name="amount" class="form-control" style="width:100%;"  placeholder="Enter Amount">
              @if($errors->has('amount'))
                <p style="color:red;">{{ $errors->first('amount') }}</p>
              @endif
            </div>
          </div><br>
          <div class="col-md-12">
            <div class="row">
              <label for="deposit_date">Deposit Date<sup class="text-danger">*</sup></label>
              <input type="text" name="deposit_date" class="form-control datepicker" style="width:100%;" placeholder="Enter Deposit Date">
              @if($errors->has('deposit_date'))
                <p style="color:red;">{{ $errors->first('deposit_date') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success mx-auto">submit</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!--------------------------------------------------------------------------------->

<!---------------------------------------Edit Bank ------------------------------->
<div class="modal fade" id="editBank" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/addBank') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Edit Company Bank</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="editBank-container">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success mx-auto">submit</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!--------------------------------------------------------------------------------->

<!----------------------------------------MONEY TRANSFER MODAL------------------------------------------------>

<div class="modal fade" id="money-transfer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/b2bTransaction') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Money Transfer</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for="Form Bank">Form Bank<span class="text-danger">*</span></label>
                        <select name="from_bank" id="from_bank" class="form-control">
                            <option value="">--</option>
                            @if($banks->count()>0)
                                @foreach($banks as $key => $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p>Total Cash : ₹ <span id="from-cash"></span></p>
                    </div>
                    <div class="col-md-4">
                        <label for="To Bank">To Bank<span class="text-danger">*</span></label>
                        <select name="to_bank" id="to_bank" class="form-control">
                            <option value="">--</option>
                            @if($banks->count()>0)
                                @foreach($banks as $key => $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <p>Total Cash : ₹ <span id="to-cash"></span></p>
                    </div>
                    <div class="col-md-4">
                        <label for="Transfer Amount">Transfer Amount<span class="text-danger">*</span></label>
                        <input type="text" name="trans_amt" id="trans_amt" class="form-control" placeholder="Amount">
                    </div>
                </div>
            </div><br>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for="Transaction Date">Transaction Date<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker" name="transaction_date" id="date" placeholder="Enter date" autocomplete="off">
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="Remarks">Remarks<span class="text-danger">*</span></label>
                        <textarea name="remarks" id="remarks" rows="1" class="form-control" placeholder="Enter Remarks....."></textarea>
                    </div>
                </div>
            </div><br>
            <div class="col-md-12 payment_mode" style="display:none;">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="cheque_dd_number"><span id="p_type_name"></span> Number</label>
                        <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date">Cheque/DD Date</label>
                        <input type="text" class="form-control datepicker" name="date" id="date" placeholder="Enter date" autocomplete="off">
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success btn-sm mx-auto">submit</button>
        </div>
      </form>
    </div>
    </div>
</div>

<!------------------------------------------------------------------------------------------------------>

<!---------------------------------------Deposit & Deduction--------------------------------------->

    <div class="modal fade" id="cranddr" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center bg-primary text-white">
                    <h6 class="modal-title mb-0" id="addUserLabel"> Add Deposit & Deduction</h6>
                    <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills">
                        <li class="active"><a data-toggle="pill" href="#deposit" class="btn btn-primary">Deposit</a></li>
                        &nbsp;&nbsp;
                        <li><a data-toggle="pill" href="#deduction" class="btn btn-primary">Deduction</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="deposit" class="tab-pane fade in active show">
                            <h3>Deposit</h3>
                            <form action="{{ url('admin/addDepositBankTxns') }}" method="post">
                                {{ csrf_field()}}
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
                                            <input type="text" class="form-control datepicker" name="deposit_date" id="deposit_date" placeholder="dd-mm-yyyy" autocomplete="off">
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
                                            <label for="bank_id">Bank Name</label>
                                            <select name="bank_id" class="form-control" id="bank_name">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>

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
                                <div class="col-md-12 payment_mode" style="display:none;">
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label for="cheque_dd_number">Cheque/DD/NEFT/RTGS Number</label>
                                            <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="date">Cheque/DD Date</label>
                                            <input type="text" class="form-control datepicker" name="date" id="dd_date" placeholder="Enter date" autocomplete="off">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm">Add Deposit</button>
                                </div>
                            </form>
                        </div>
                        <div id="deduction" class="tab-pane fade">
                            <h3>Deduction</h3>
                            <form action="{{ url('admin/addDeductionBankTxns') }}" method="post">
                                {{ csrf_field()}}
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="amount">Amount <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="amount" id="amount" placeholder="Enter amount">
                                            @if($errors->has('amount'))
                                                <p>{{ $errors->first('amount') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="deposit_date">Deduction Date <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control datepicker" name="deposit_date" id="deduction_date" placeholder="dd-mm-yyyy" autocomplete="off">
                                            @if($errors->has('deposit_date'))
                                                <p>{{ $errors->first('deposit_date') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="bank_id">Bank Name</label>
                                            <select name="bank_id" class="form-control" id="bank_name">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="remarks" id="remarks" placeholder="Enter remarks">
                                            @if($errors->has('remarks'))
                                                <p>{{ $errors->first('remarks') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                                    @if($errors->has('name'))
                                        <p>{{ $errors->first('name') }}</p>
                                    @endif
                                </div>
                                <div class="col-md-12 payment_mode" style="display:none;">
                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label for="cheque_dd_number">Cheque/DD/NEFT/RTGS Number</label>
                                            <input type="text" class="form-control" name="cheque_dd_number" id="cheque_dd_number" placeholder="Enter cheque_dd_number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="date">Cheque/DD Date</label>
                                            <input type="text" class="form-control datepicker" name="date" id="dateeduction" placeholder="Enter date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm">Add Deduction</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!------------------------------------------------------------------------------>
<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.bankDelete')}}" method="POST">
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
          <button type="submit" class="btn btn-danger btn-xs" onclick="confirm('If money transferred from bank to bank or any deposit/deduction from bank then that record will be deleted. Are You Sure')">Delete</button>
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
            $('#p_type_name').html(($(elem).val()).toUpperCase());
            $(".payment_mode").show();
        }
    }
    $('#from_bank').on('change',function(){
        var from_bank_id = $(this).val();
        $.ajax({
            url:'{{route("admin.getFromBankCash")}}',
            type:'POST',
            data:{from_bank_id:from_bank_id,_token:'{!! csrf_token() !!}'},
            success:function(data){
                if(data <= 0){
                    $('#from-cash').html(data).css('color','red');
                }else{
                    $('#from-cash').html(data).css('color','green');
                }
            }
        });
    });
    $('#to_bank').on('change',function(){
        var to_bank_id = $(this).val();
        $.ajax({
            url:'{{route("admin.getToBankCash")}}',
            type:'POST',
            data:{to_bank_id:to_bank_id,_token:'{!! csrf_token() !!}'},
            success:function(data){
                if(data <= 0){
                    $('#to-cash').html(data).css('color','red');
                }else{
                    $('#to-cash').html(data).css('color','green');
                }
            }
        });
    });
    $('.delete_confirm').click(function(){
        var id = $(this).data('id');
        $.ajax({
            url:'{{route("admin.bank_delete_confirm")}}',
            type:'POST',
            data:{id:id,_token:'{!! csrf_token() !!}'},
            success:function(data){
                $('#delete_customer-container').html(data);
                $('#delete_customer').modal('show');

            }
        });
    });

</script>



@endsection





