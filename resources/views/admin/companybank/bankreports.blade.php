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
@if($transactions->count()>0)
    @php
        $params = [];
        if(Request::has('bank_id'))
           $params = array_merge(['bank_id' => Request::input('bank_id')],$params);
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
    @endphp
    @if(Auth::user()->login_type == 'superadmin' && Request::input('from_date') != NULL)
        <div class="col-md-3" style="margin-left:85%">
            <a href="{{route('admin.excel_bank_transactions',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
        </div>
    @elseif(Auth::user()->login_type == 'superadmin' && Request::input('from_date') == NULL)
        <div class="col-md-3" style="margin-left:85%">
            <a href="{{route('admin.excel_bank_transactions',['bank_id'=>$bank->id])}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
        </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-4">
        <label for="from_date">From Date</label>
        <input type="text" name="from_date" class="form-control datepicker" placeholder="dd-mm-YYYY">
      </div>
      <div class="col-md-4">
        <label for="to_date">To Date</label>
        <input type="text" name="to_date" class="form-control datepicker" placeholder="dd-mm-YYYY">
        <input type="hidden" name="bank_id" class="form-control" value="{{$bank->id}}">
      </div>
      <div class="col-md-4" style="padding-top:30px;">
        <button type="submit" class="btn btn-md btn-primary">search</button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
      <h6 class="text-center">{{$bank->bank_name}}</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Details</th>
            <th>Decription</th>
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Running Balance</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Cheque/DD Number</th>
            <th scope="col">Cheque/DD Date</th>
            <th scope="col">Created At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if($transactions->count()>0)
          @php $total_credit = $total_debit = $running_balance = 0; @endphp
            @foreach($transactions as $key => $transaction)
                <tr>
                    <td>{{$key+1}}.</td>
                    @if($transaction->respective_table_name != 'customer_transactions' && $transaction->respective_table_name != 'associate_transactions')
                        <td>User : N/A <br> Code : N/A <br> Name : N/A</td>
                    @elseif($transaction->respective_table_name == 'customer_transactions' || $transaction->respective_table_name == 'associate_transactions')
                        @php $user = getUserCodeName($transaction->respective_table_id,$transaction->respective_table_name); @endphp
                        @if($user)
                            <td>User : {{ ucwords($user->login_type) }} <br>
                                Code :
                                @if($user->login_type == 'customer')
                                    <a href="{{route('admin.customer_detail',encrypt($user->id))}}" class="text-primary" data-toggle="tooltip" title="View Details">{{ $user->code }}</a>
                                @elseif($user->login_type == 'associate')
                                    <a href="{{route('admin.associate_view',$user->id)}}" class="text-info" data-toggle="tooltip" title="View Details">{{ $user->code }}</a>
                                @endif
                            <br>
                            Name : {{ $user->name }}</td>
                        @else
                            <td>User : N/A <br> Code : N/A <br> Name : N/A</td>
                        @endif
                    @endif
                    @if($transaction->respective_table_name == 'company_banks')
                        <td>Bank openning balance</td>
                    @elseif($transaction->respective_table_name == 'bank_transactions')
                        <td>Bank to bank transaction <br>({{ $bank->bank_name .' - '. getBank($transaction->respective_table_id)}})</td>
                    @else
                        <td>{{ucfirst($transaction->remarks?$transaction->remarks:'N/A')}}</td>
                    @endif
                    @if($transaction->cr_dr == 'cr')
                        @php
                            $total_credit += $transaction->amount;
                        @endphp
                    @else
                        @php
                            $total_debit += $transaction->amount;
                        @endphp
                    @endif

                    @if($transaction->cr_dr == 'cr')
                        <td class="text-success">
                            ₹  {{$transaction->amount}}
                        </td>
                        <td class="text-danger">
                            ₹  0.00
                        </td>
                    @else
                        <td class="text-success">
                            ₹  0.00
                        </td>
                        <td class="text-danger">
                            ₹  {{$transaction->amount}}
                        </td>
                    @endif
                    @if($transaction->cr_dr == 'cr')
                        @php
                            $running_balance += $transaction->amount;
                        @endphp
                    @else
                        @php
                            $running_balance -= $transaction->amount;
                        @endphp
                    @endif

                    <td class="text-primary">₹ {{number_format($running_balance,2)}}</td>
                    <td>{{$transaction->transaction_date?Carbon\Carbon::parse($transaction->transaction_date)->format('j-m-Y'):'N/A'}}</td>
                    <td>{{$transaction->cheque_dd_number?$transaction->cheque_dd_number:'cash transaction'}}</td>
                    <td>{{$transaction->cheque_dd_date?Carbon\Carbon::parse($transaction->cheque_dd_date)->format('j-m-Y'):'N/A'}}</td>
                    <td>{{Carbon\Carbon::parse($transaction->created_at)->format('j-m-Y')}}</td>
                    <td>
                        @if($transaction->respective_table_name != 'customer_transactions' && $transaction->respective_table_name != 'associate_transactions' && $transaction->respective_table_name != 'company_banks')
                            @if($transaction->respective_table_name == 'bank_transactions')
                                <span data-toggle="modal" data-target="#edit-b2btxn"><a class="text-success edit-b2btxn" data-toggle="tooltip" title="Edit" data-info="{{ $transaction->bank_id }},{{ $transaction->respective_table_id }},{{ $transaction->amount }},{{ $transaction->transaction_date }},{{ $transaction->payment_type }},{{ $transaction->remarks }},{{ $transaction->cheque_dd_date }},{{ $transaction->cheque_dd_number }},{{ $transaction->id }}"><i class="material-icons">edit</i></a></span>

                                <span data-toggle="modal" data-target="#delete_confirm"><a class="text-danger delete_confirm" data-toggle="tooltip" title="Delete" data-info="{{ $transaction->id }},{{ $transaction->bank_id }},{{ $transaction->respective_table_id }},{{ $transaction->bank_transfer_id }}"><i class="material-icons">delete_outline</i></a></span>
                            @else
                                <span data-toggle="modal" data-target="#edit-directDD"><a class="text-primary edit-directDD" data-toggle="tooltip" title="Edit" data-info="{{ $transaction->amount }},{{ $transaction->transaction_date }},{{ $transaction->bank_id }},{{ $transaction->remarks }},{{ $transaction->payment_type }},{{ $transaction->cheque_dd_date }},{{ $transaction->cheque_dd_number }},{{ $transaction->id }},{{ $transaction->remarks?explode(' ', trim($transaction->remarks ))[1]:$transaction->cr_dr }}"><i class="material-icons">edit</i></a></span>

                                <span data-toggle="modal" data-target="#direct_depded"><a class="text-danger direct_depded" data-toggle="tooltip" title="Delete" data-info="{{ $transaction->id }},{{ $transaction->bank_id }}"><i class="material-icons">delete_outline</i></a></span>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
                <tfoot>
                  <tr>
                    <td colspan="6"><span class="text-success">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Pending :  ₹ {{$total_credit - $total_debit}}</span>    &nbsp;  &nbsp;   ||  &nbsp;  &nbsp;  <span class="text-primary">Total Debit :  ₹ {{$total_debit}}</span>   </td>
                  </tr>
                </tfoot>
          @else
            <tr>
              <td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
{{$transactions->links()}}

<div class="modal fade" id="edit-b2btxn" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/edit_b2bTransaction') }}" method="POST">
      {{ csrf_field()}}
        <input type="hidden" name="table_id" id="table_id">
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
                        <input type="text" class="form-control datepicker" name="transaction_date" id="trans_date" placeholder="Enter date" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <label for="name">Payment Type<span class="text-danger">*</span></label>
                            <select name="payment_type" class="form-control" onChange="payment(this)" id="payment_type">
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

<!------------------------Delete customer --------------------->
    <div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('admin.deleteBankRecords')}}" method="POST">
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
<!---------------------------------------Deposit & Deduction--------------------------------------->

    <div class="modal fade" id="edit-directDD" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
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
                            <form action="{{ url('admin/editDepositBankTxns') }}" method="post">
                                {{ csrf_field()}}
                                <input type="hidden" name="table_id" id="direct_table_id">
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
                                            <select name="bank_id" class="form-control" id="deps-bank_name">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="bank_id" id="dep-bank_name">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="remarks" id="dep-remarks" placeholder="Enter remarks">
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
                                    <select name="payment_type" class="form-control" onChange="payment(this)" id="dep-payment_type">
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
                                            <input type="text" class="form-control" name="cheque_dd_number" id="dep-cheque_dd_number" placeholder="Enter cheque_dd_number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="date">Cheque/DD Date</label>
                                            <input type="text" class="form-control datepicker" name="date" id="dep-cheque_dd_date" placeholder="Enter date" autocomplete="off">

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
                            <form action="{{ url('admin/editDeductionBankTxns') }}" method="post">
                                {{ csrf_field()}}
                                <input type="hidden" name="table_id" id="direct_table_id">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="amount">Amount <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="amount" id="ded-amount" placeholder="Enter amount">
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
                                            <select name="bank_id" class="form-control" id="deds-bank_name">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="bank_id" id="ded-bank_name">

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="remarks">Remarks<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="remarks" id="ded-remarks" placeholder="Enter remarks">
                                            @if($errors->has('remarks'))
                                                <p>{{ $errors->first('remarks') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">Payment Type<span class="text-danger">*</span></label>
                                    <select name="payment_type" class="form-control" onChange="payment(this)" id="ded-paymentType">
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
                                            <input type="text" class="form-control" name="cheque_dd_number" id="ded-cheque_dd_number" placeholder="Enter cheque_dd_number">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="date">Cheque/DD Date</label>
                                            <input type="text" class="form-control datepicker" name="date" id="ded-dateeduction" placeholder="Enter date" autocomplete="off">
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

<!------------------------Delete DEDDEP --------------------->
    <div class="modal fade" id="delete_deddep" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{route('admin.deleteDepositDeductionRecords')}}" method="POST">
                    {{ csrf_field()}}
                    <div class="modal-header d-flex align-items-center bg-primary text-white">
                        <h6 class="modal-title mb-0" id="addUserLabel"> Delete Transaction</h6>
                        <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body" id="delete_deddep-container">

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

        $('.edit-b2btxn').on('click', function(){
            var data = $(this).data('info');
            var info = data.split(',');
            $('#from_bank').val(info[0]);
            $('#to_bank').val(info[1]);
            $('#trans_amt').val(info[2]);
            $('#trans_date').val(info[3]);
            $('#payment_type').val(info[4]);
            $('#remarks').val(info[5]);
            if(info[4]=='cash')
            {
                $(".payment_mode").hide();
            }
            else if(info[4]== '')
            {
                $(".payment_mode").hide();
            }
            else
            {
                $(".payment_mode").show();
                $('#cheque_dd_date').val(info[6]);
                $('#cheque_dd_number').val(info[7]);
            }
            $('#table_id').val(info[8]);
            $('#from_bank').attr("disabled","disabled");
            $('#to_bank').attr("disabled","disabled");
            $('#edit-b2btxn').trigger();
        });

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
            var ids = $(this).data('info');
            var id = ids.split(',');
            var tableId = id[0];
            var bankId = id[1];
            var respTableId = id[2];
            var bankTranId = id[3];
            $.ajax({
                url:'{{route("admin.b2bt_delete_confirm")}}',
                type:'POST',
                data:{tableId:tableId,bankId:bankId,respTableId:respTableId,bankTranId:bankTranId,_token:'{!! csrf_token() !!}'},
                success:function(data){
                    $('#delete_customer-container').html(data);
                    $('#delete_customer').modal('show');
                }
            });
        });

        $('.edit-directDD').on('click', function(){
            var data = $(this).data('info');
            var info = data.split(',');
            $('#direct_table_id').val(info[7]);
                //alert(info);
            if(info[8] == 'deposit' || info[8] == 'cr'){
                $('#deposit').addClass('active show');
                $('#deduction').removeClass('active show');
                $('#amount').val(info[0]);
                $('#deposit_date').val(info[1]);
                $('#dep-bank_name').val(info[2]);
                $('#deps-bank_name').val(info[2]);
                $('#dep-remarks').val(info[3]);
                $('#dep-payment_type').val(info[4]);
                if(info[4]=='cash')
                {
                    $(".payment_mode").hide();
                }
                else if(info[4]== '')
                {
                    $(".payment_mode").hide();
                }
                else
                {
                    $(".payment_mode").show();
                    $('#dep-cheque_dd_date').val(info[5]);
                    $('#dep-cheque_dd_number').val(info[6]);
                }
                $('#deps-bank_name').attr("disabled","disabled");
            }else{
                $('#deposit').removeClass('active show');
                $('#deduction').addClass('active show');
                $('#ded-amount').val(info[0]);
                $('#deduction_date').val(info[1]);
                $('#ded-bank_name').val(info[2]);
                $('#deds-bank_name').val(info[2]);
                $('#ded-remarks').val(info[3]);
                $('#ded-paymentType').val(info[4]);
                if(info[4]=='cash')
                {
                    $(".payment_mode").hide();
                }
                else if(info[4]== '')
                {
                    $(".payment_mode").hide();
                }
                else
                {
                    $(".payment_mode").show();
                    $('#ded-dateeduction').val(info[5]);
                    $('#ded-cheque_dd_number').val(info[6]);
                }
                $('#deds-bank_name').attr("disabled","disabled");
            }
            $('#edit-b2btxn').trigger();
        });

        $('.direct_depded').click(function(){
            var ids = $(this).data('info');
            var id = ids.split(',');
            var tableId = id[0];
            var bankId = id[1];
            $.ajax({
                url:'{{route("admin.directdepded_delete_confirm")}}',
                type:'POST',
                data:{tableId:tableId,bankId:bankId,_token:'{!! csrf_token() !!}'},
                success:function(data){
                    $('#delete_deddep-container').html(data);
                    $('#delete_deddep').modal('show');
                }
            });
        });

    </script>




@endsection





