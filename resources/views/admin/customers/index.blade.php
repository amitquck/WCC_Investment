@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customers List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->

  @include('flash')
  @php
    $params = [];
    if(Request::has('customer_id'))
         $params = array_merge(['customer_id' => Request::input('customer_id')],$params);
    if(Request::has('customer_name'))
         $params = array_merge(['customer_name' => Request::input('customer_name')],$params);
    if(Request::has('customer_mobile'))
         $params = array_merge(['customer_mobile' => Request::input('customer_mobile')],$params);
    if(Request::has('customer_bank_account'))
         $params = array_merge(['customer_bank_account' => Request::input('customer_bank_account')],$params);
    if(Request::has('customer_deposit'))
         $params = array_merge(['customer_deposit' => Request::input('customer_deposit')],$params);
    if(Request::has('state_id'))
         $params = array_merge(['state_id' => Request::input('state_id')],$params);
    if(Request::has('city_id'))
         $params = array_merge(['city_id' => Request::input('city_id')],$params);
  @endphp

  <form action="{{route('admin.customer_search')}}" method="get">
        <div class="row">
          <div class=" col-md-1">
            @if(empcan('add_customer') || Auth::user()->login_type == 'superadmin')
              <a href="{{route('admin.addCustomer')}}" class="btn btn-primary has-icon" data-toggle="tooltip" title="Add Customer"><i class="material-icons">add</i>Add</a>
            @endif
         </div>
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Customer Code" name="customer_id" id="customer_id" autocomplete="off">
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Name" name="customer_name" id="customer_name" autocomplete="off">
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Mobile" name="customer_mobile" id="customer_mobile" autocomplete="off">
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control" placeholder="Account No" name="customer_bank_account" id="customer_bank_account" autocomplete="off">
            </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-3 offset-md-1">
            <input type="text" class="form-control" placeholder="Balance" name="customer_deposit" id="customer_deposit" autocomplete="off">
          </div>
          <div class="col-md-3">
            <select class="form-control" name="state_id" id="state_id">
              <option value="">Select State</option>
              @foreach($states as $state)
                <option value="{{$state->id}}">{{$state->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="city_id" id="city_id">
              <option value="">Select City</option>
            </select>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-primary btn-md">
              <i class="material-icons">search</i>
            </button>
          </div>
          @if(empcan('export_customer') || Auth::user()->login_type == 'superadmin')
            <div class="col-md-1">
              <a href="{{route('admin.all_customer_list_excel_export',$params)}}" class="btn btn-success btn-md" data-toggle="tooltip" title="Excel Export"><i class="material-icons">import_export</i>
              </a>
            </div>
          @endif
        </div>
  </form>

  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="text-center">Sr No:</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Interest %</th>
            <th scope="col">Contact Details</th>
            <th scope="col"class="text-center">Created At</th>
            <th>Balance</th>
            <th>Payment Status</th>
            <th scope="col" class="text-center">Action
              <span></span>
            </th>
          </tr>
        </thead>
        <tbody>
          @if($users->count()>0)
          @foreach($users as $key => $user)

            <tr>
              <td class="text-center">{{($users->currentpage()-1)*$users->perpage()+$key+1}}.</td>
              <td class="text-primary"><a @if(empcan('view_detail_customer') || Auth::user()->login_type == 'superadmin') href="{{route('admin.customer_detail',encrypt($user->id))}}" @endif data-toggle="tooltip" title="View Details">{{$user->code}}</a></td>
              <td>{{ucwords("$user->name")}}</td>
              <td><a href="{{route('admin.customer_associate_commission',encrypt($user->id))}}" class="text-primary" data-toggle="tooltip" title="Latest Interest %">{{$user->customeractiveinterestpercent->interest_percent}} %</a></td>
              <td>
                <i class="material-icons text-success">phone</i>{{$user->mobile}}<br>
                <i class="material-icons text-warning">mail</i> {{$user->email?$user->email:'N/A'}}<br>
              </td>
              <td class="text-center">
                  {{Carbon\Carbon::parse($user->created_at)->format('j-m-Y')}}
              </td>
              @if($user->customer_current_balance() > 0)
                <td><a @if(empcan('view_ladger_balance_customer') || Auth::user()->login_type == 'superadmin')  href="{{route('admin.customer_transactions',['customer'=>$user->code])}}" @endif class="text-success" data-toggle="tooltip" title="View Ladger Balance">₹ {{$user->customer_current_balance()}}</a></td>
              @else
                <td><a @if(empcan('view_ladger_balance_customer') || Auth::user()->login_type == 'superadmin')  href="{{route('admin.customer_transactions',['customer'=>$user->code])}}" @endif class="text-danger" data-toggle="tooltip" title="View Ladger Balance">₹ {{$user->customer_current_balance()}}</a></td>
              @endif
              <!-- @foreach($user->customertransactions as $txn)
              {{$txn->is_month_payout_generate}}
              @endforeach -->

              <td>
                @if(empcan('payment_status_customer') || Auth::user()->login_type == 'superadmin')
                  <a class="btn  text-success @if(!$user->lock_status) hold_remarks @else disabled @endif @if($user->hold_status)  @else text-danger @endif" data-toggle="tooltip" data-id="{{$user->id}}" title="@if($user->hold_status) Make Hold Payment @else Make Pay @endif">@if($user->hold_status)<i class="material-icons">thumb_up_alt</i> @else <i class="material-icons">thumb_down_alt</i> @endif
                  </a>
                @endif
              </td>
              <td class="table-actions">
                <div class="dropdown">
                    <a class="btn btn-link" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-ellipsis-h"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">

                    <!-- @if(empcan('view_associate_customer_investments')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{route('admin.customer_activitylog',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 viewAssociate" data-toggle="tooltip" title="Activity Log"><i class="material-icons" style="color:#e67300">local_activity</i></a>
                    @endif -->

                    @if(Auth::user()->login_type == 'superadmin')
                      <a href="{{ url('admin/EnableDisable/'.encrypt($user->id)) }}" class="btn btn-default @if(!$user->lock_status)text-success @else text-danger @endif" data-toggle="tooltip" data-placement="top" title="@if(!$user->lock_status)Make Disable @else Make Enable @endif"><i class="material-icons">@if(!$user->lock_status)done @else cancel @endif</i></a>
                    @endif

                    @if(empCan('view_marked_associate_customer') || Auth::user()->login_type == 'superadmin')
                      <a @if(!$user->lock_status) href="{{route('admin.associate_marked_in_customer',['customer_id'=>$user->id])}}" class="btn btn-default text-primary AddTransationForm" data-toggle="tooltip" data-placement="top" title="View Marked Associate" @else class="btn btn-default text-primary disabled" @endif><i class="material-icons">visibility</i></a>
                    @endif
                    <?php /*@if(empcan('no_interest_customer') || Auth::user()->login_type == 'superadmin')
                      <a class="btn btn-default btn-link btn-icon bigger-130 text-warning no_interest" data-toggle="tooltip" title="No Interest"  data-id="{{$user->id}}"><i class="material-icons">explore_off</i></a>
                    @endif*/ ?>

                    @if(empcan('view_transaction_customer') || Auth::user()->login_type == 'superadmin')
                      <a @if(!$user->lock_status) href="{{route('admin.customerAllTransactions',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-warning viewAssociate" data-toggle="tooltip" title="View Transactions"  data-id="{{$user->id}}" @else  class="btn btn-default btn-link btn-icon bigger-130 text-warning disabled" @endif><i class="material-icons">visibility</i></a>
                    @endif

                    @if(empcan('add-investment_customer')|| Auth::user()->login_type == 'superadmin')
                      <a @if($user->status && !$user->lock_status) href="{{route('admin.customer_commission',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary" data-toggle="tooltip" title="Add Investement"  data-id="{{$user->id}}" @else href="javascript:void(0)" class="btn btn-default btn-link btn-icon bigger-130 text-primary disabled" data-toggle="tooltip" title="No Add Investement"  data-id="{{$user->id}}" @endif><i class="material-icons">add</i></a>
                    @endif



                    @if(empcan('edit_commission_customer') || Auth::user()->login_type == 'superadmin')
                      <a @if(!$user->lock_status) href="{{route('admin.customer_associate_commission',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-info" data-toggle="tooltip" title="Edit Commission"  data-id="{{$user->id}}" @else href="javascript:void(0)" class="btn btn-default btn-link btn-icon bigger-130 text-info disabled" data-toggle="tooltip" title="Edit Commission"  data-id="{{$user->id}}" @endif ><i class="material-icons">edit</i></a>
                    @endif

                    @if(empcan('add_withdraw_customer') || Auth::user()->login_type == 'superadmin')
                     @php /*<a class="btn  btn-default bigger-130 text-danger @if($user->customerdetails->payment_type == 'hold' || $user->customer_current_balance() <= 0 || $user->lock_status != 0) disabled @elseif($user->hold_status) customerWithdrawForm @else disabled @endif " data-toggle="tooltip" title="Add Withdraw"  data-id="{{$user->id}}"><i class="material-icons">account_balance_wallet</i></a>*/@endphp
                        <a class="btn  btn-default bigger-130 text-danger  @if(!$user->lock_status) customerWithdrawForm @else disabled @endif " data-toggle="tooltip" title="Add Withdraw"  data-id="{{$user->id}}"><i class="material-icons">account_balance_wallet</i></a>
                    @endif

                    @if(empcan('view_detail_customer')|| Auth::user()->login_type == 'superadmin')
                     <a @if(!$user->lock_status) href="{{route('admin.customer_detail',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Customer"  data-id="{{$user->id}}" @else class="btn btn-default btn-link btn-icon bigger-130 text-primary disabled" @endif><i class="material-icons">visibility</i></a>
                    @endif
                    @if(empcan('status_customer')|| Auth::user()->login_type == 'superadmin')
                      <a @if(!$user->lock_status) href="{{url('admin/customerStatus/'.encrypt($user->id))}}" class="btn btn-link btn-icon bigger-130 text-success @if($user->status) @else text-danger @endif" @else  class="btn btn-link btn-icon bigger-130 text-success disabled @endif @if($user->status) @else text-danger @endif" data-toggle="tooltip" title="@if($user->status) Make Inactive @else Make Active @endif">
                      <i class="material-icons">@if($user->status)lock @else lock_open @endif</i>
                      </a>
                    @endif
                    @if(empcan('edit_customer')|| Auth::user()->login_type == 'superadmin')
                      <a @if(!$user->lock_status) href="{{ url('admin/customer-edit/'.encrypt($user->id)) }}" class="btn btn-default btn-link btn-icon bigger-130 text-success editCustomer" @else class="btn btn-default btn-link btn-icon bigger-130 text-success editCustomer disabled" @endif data-toggle="tooltip" title="Edit" ><i class="material-icons">edit</i></a>
                    @endif
                    @if(empcan('delete_customer')|| Auth::user()->login_type == 'superadmin')
                      <a class="btn btn-link btn-icon bigger-130 text-danger @if(!$user->lock_status) delete_confirm @else disabled  @endif" data-toggle="tooltip" title="Delete" data-id="{{$user->id}}"><i class="material-icons">delete_outline</i></a>
                    @endif
                      <?php /*<a class="btn btn-link btn-icon text-danger" data-toggle="modal" data-target="#delete_customer" data-id="{{$user->id}}"><i class="material-icons">delete_outline</i></a> */?>

                  </div>
                </div>
              </td>
            </tr>
          @endforeach
             @else
            <tr><td colspan="10"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
          @endif

        </tbody>
      </table>
    </div>
  </div>

@php
  $params = [];
    if(Request::has('customer_id'))
         $params = array_merge(['customer_id' => Request::input('customer_id')],$params);
    if(Request::has('customer_name'))
         $params = array_merge(['customer_name' => Request::input('customer_name')],$params);
    if(Request::has('customer_mobile'))
         $params = array_merge(['customer_mobile' => Request::input('customer_mobile')],$params);
    if(Request::has('customer_bank_account'))
         $params = array_merge(['customer_bank_account' => Request::input('customer_bank_account')],$params);
    if(Request::has('customer_deposit'))
         $params = array_merge(['customer_deposit' => Request::input('customer_deposit')],$params);
    if(Request::has('state_id'))
         $params = array_merge(['state_id' => Request::input('state_id')],$params);
    if(Request::has('city_id'))
         $params = array_merge(['city_id' => Request::input('city_id')],$params);
@endphp
{{$users->appends($params)->links()}}

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerDeposit')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Investment Amount</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="add-container">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    </div>
</div>


<!------------------------customer withdraw form--------------------->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerWithdraw')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Withdraw Amount</h6>
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

<!------------------------hold_remarks form--------------------->
<div class="modal fade" id="hold_remarksModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.hold_status')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Hold Remarks</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="hold_remarks-container">

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

<!------------------------No Interest--------------------->
<div class="modal fade" id="no_interest" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{route('admin.no_interest')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add No Interest Remarks</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="no_interest-container">

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

<!------------------------Delete customer --------------------->
<div class="modal fade" id="delete_customer" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.customerDelete')}}" method="POST">
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



<script>
$('.customerDepositForm').click(function(){
      var id = $(this).data('id');
      $.ajax({
        url:'{{route("admin.customerDepositForm")}}',
        type:'POST',
        data:{id:id,_token:'{!! csrf_token() !!}'},
        success:function(data){
            $('#add-container').html(data);
            $('#addModal').modal('show');

        }
      });
    });

$('.customerWithdrawForm').click(function(){

  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.customerWithdrawForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#withdraw-container').html(data);
        $('#withdrawModal').modal('show');

    }
  });
});


$('.edit_deposit').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.edit_depositForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#edit_deposit-container').html(data);
        $('#edit_depositModal').modal('show');

    }
  });
});

$('.hold_remarks').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.hold_remarksForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#hold_remarks-container').html(data);
        $('#hold_remarksModal').modal('show');

    }
  });
});

$('.no_interest').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.no_interest_form")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
      $('#no_interest-container').html(data);
      $('#no_interest').modal('show');
    }
  });
});

$('.delete_confirm').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.delete_confirm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_customer-container').html(data);
        $('#delete_customer').modal('show');

    }
  });
});

function payment(elem){
  if($(elem).val()=='cash'){
    $(".payment_mode").hide();
  }
  else if($(elem).val()== ''){
    $(".payment_mode").hide();
  }
  else{
    $(".payment_mode").show();
  }
}

$(document).ready(function(){
  $('#state_id').change(function(){
     $('#city_id').html('<option>Select City </option>');
     var state_id = $('#state_id').val();
     $.ajax({
       url: "{{ route('admin.getStateCity') }}",
       type: 'POST',
       dataType:'json',
       data:{state_id:state_id,_token:'{!! csrf_token() !!}'},
       success: function(city)
       {
         // alert(state);
         $.each(city,function(key,value){
           $('#city_id').append('<option value="'+key+'">'+value+'</option>');
         })
       },
       error: function(state)
       {
         alert('faild');
       }

   });
   });
});
</script>
@endsection
