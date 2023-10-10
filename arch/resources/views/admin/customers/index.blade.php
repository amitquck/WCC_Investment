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

  <div class="col-md-12">
    <form action="{{route('admin.customer_search')}}" method="get">
      <div class="row">
        @if(empcan('add_customer') || Auth::user()->login_type == 'superadmin')
         <a href="{{route('admin.addCustomer')}}" class="btn btn-primary has-icon" data-toggle="tooltip" title="Add Customer">Add</a>
         @endif
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Customer Id" name="customer_id" id="customer_id" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Name" name="customer_name" id="customer_name" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Mobile" name="customer_mobile" id="customer_mobile" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
              <th>
                <input type="text" class="form-control" placeholder="Account No" name="customer_bank_account" id="customer_bank_account" autocomplete="off">
              </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Investment" name="customer_deposit" id="customer_deposit" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-primary btn-sm">
            <i class="material-icons">search</i>
          </button>
        </div>
      </div>
    </form>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="text-center">Sr No:</th>
            <th scope="col">Name</th>
            <th scope="col">Details</th>
            <th scope="col"class="text-center">Created At</th>
            <th>Balance</th>
            <th>Payment Status</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($users->count()>0)
          @foreach($users as $key => $user)
            <tr>
              <td class="text-center">{{($users->currentpage()-1)*$users->perpage()+$key+1}}.</td>
              <td>{{ucwords("$user->name")}}</td>
              <td>
                <i class="material-icons text-success">phone</i>{{$user->mobile}}<br>
                <i class="material-icons text-warning">mail</i> {{$user->email}}<br>
              </td>
              <td class="text-center">
                  @php $created_at = Carbon\Carbon::parse($user->created_at)->format('j-M-Y'); @endphp
                  {{$created_at}}
              </td>
              <td class="text-danger">₹ {{$user->customer_current_balance()}}</td>
             
              <td><a href="{{url('admin/customerHoldStatus/'.encrypt($user->id))}}"  class="btn btn-sm  text-success @if($user->hold_status) @else text-danger @endif" data-toggle="tooltip" title="@if($user->hold_status) Make Hold Payment @else Make Pay @endif">@if($user->hold_status)Pay @else Hold Payment @endif
              </a></td>
              <td class="text-center">
                 <?php /* <a   class="btn  btn-default bigger-130 text-primary customerDepositForm" data-toggle="tooltip" title="Add Invest"  data-id="{{$user->id}}"><i class="material-icons">account_balance_wallet</i></a>*/ ?>
                
                 @if(empcan('view_associate_customer_investments')|| Auth::user()->login_type == 'superadmin')
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{route('admin.customerAllTransactions',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Transactions"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a>
                 @endif
                 @if(empcan('add_investment_customer')|| Auth::user()->login_type == 'superadmin')
                <a href="{{route('admin.customer_commission',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="Add Investement"  data-id="{{$user->id}}"><i class="material-icons">add</i></a>
                 @endif
                 @if(empcan('edit_commission')|| Auth::user()->login_type == 'superadmin')
                <a href="{{route('admin.customer_associate_commission',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-info" data-toggle="tooltip" title="Edit Commission"  data-id="{{$user->id}}"><i class="material-icons">edit</i></a>
                @endif

                @if(empcan('add_withdraw_customer_investments')|| Auth::user()->login_type == 'superadmin')
                  <a class="btn  btn-default bigger-130 text-danger @if($user->hold_status)customerWithdrawForm @else disabled @endif " data-toggle="tooltip" title="Add Withdraw"  data-id="{{$user->id}}"><i class="material-icons">account_balance_wallet</i></a>   
                @endif   
                  
                @if(empcan('add_customer')|| Auth::user()->login_type == 'superadmin')
                 <a href="{{route('admin.customer_detail',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Customer"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a>
                @endif
              @if(empcan('status_customer')|| Auth::user()->login_type == 'superadmin')
                <a href="{{url('admin/customerStatus/'.encrypt($user->id))}}"  class="btn btn-link btn-icon bigger-130 text-success @if($user->status) @else text-danger @endif" data-toggle="tooltip" title="@if($user->status) Make Inactive @else Make Active @endif">
                    <i class="material-icons">@if($user->status)lock @else lock_open @endif</i>
                    </a>
               @endif
                @if(empcan('edit_customer')|| Auth::user()->login_type == 'superadmin')    
                  <a href="{{ url('admin/customer-edit/'.encrypt($user->id)) }}" class="btn btn-default btn-link btn-icon bigger-130 text-success editCustomer" data-toggle="tooltip" title="Edit" ><i class="material-icons">edit</i></a>
                @endif
                  @if(empcan('delete_customer')|| Auth::user()->login_type == 'superadmin')
                    <form action="{{route('admin.customerDelete',$user->id)}}" id="delete-form-{{$user->id}}" method="post" >
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                    <a href="{{route('admin.customerDelete',encrypt($user->id))}}"
                        class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" data-toggle="tooltip" 
                        title="Delete"><i class="material-icons">delete_outline</i></a>
                  @endif
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
       
       
{{$users->links()}}

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
@endsection


@section('page_js')
<script src="https://unpkg.com/@popperjs/core@2"></script>
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
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
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
</script>
@endsection