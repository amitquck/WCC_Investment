@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associates List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="col-md-12">
     
    <form action="{{route('admin.associate_search')}}" method="get">
      <div class="row">
        @if(empCan('add_associate')|| Auth::user()->login_type == 'superadmin')
         <a href="{{route('admin.addassociate')}}"><button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addassociate">Add Associate</button></a>
         @endif
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Code" name="associate_code" id="associate_code" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Name" name="associate_name" id="associate_name" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
            <th>
              <input type="text" class="form-control" placeholder="Mobile" name="associate_mobile" id="associate_mobile" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-2">
          <tr class="column-filter" id="filter">
              <th>
                <input type="text" class="form-control" placeholder="Account No" name="associate_bank_account" id="associate_bank_account" autocomplete="off">
              </th>
          </tr>
        </div>
        <div class="col-md-1">
          <button type="submit" class="btn btn-primary btn-md">
            <i class="material-icons">search</i>
          </button>
        </div>
        @if(empCan('export_associate') || Auth::user()->login_type == 'superadmin')
          <div class="col-md-1">
            <a href="{{route('admin.all_associate_list_excel_export')}}" class="btn btn-success btn-md" data-toggle="tooltip" title="Excel Export"><i class="material-icons">import_export</i>
              </a>
          </div>
        @endif
      </div>
    </form>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Contact Details</th>
            <th scope="col">Balance</th>
            <th scope="col">Created At</th>
            <th scope="col" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
            @if($users->count()>0)
              @foreach($users as $key => $user)
              <tr>
                <td>{{($users->currentpage()-1)*$users->perpage()+$key+1}}.</td>
                <td><a @if(empCan('view_detail_associate') || Auth::user()->login_type == 'superadmin') href="{{route('admin.associate_view',$user->id)}}" @endif class="text-info" data-toggle="tooltip" title="View Details">{{$user->code}}</a></td>
                <td>{{$user->name}}</td>
                <td>
                  <i class="material-icons text-danger">phone</i> {{$user->mobile}}<br>
                  <i class="material-icons text-primary">mail</i> {{$user->email?$user->email:'N/A'}}</td>
                <td class="text-danger">
                  @if($user->associatebalance() > 0)
                    <a href="{{route('admin.associate_ladger_balance',['associate'=>$user->code])}}" class="text-success" data-toggle="tooltip" title="View Ladger Balance">₹ {{$user->associatebalance()}}</a>
                  @else
                    <a href="{{route('admin.associate_ladger_balance',['associate'=>$user->code])}}" class="text-danger" data-toggle="tooltip" title="View Ladger Balance">₹ {{$user->associatebalance()}}</a>
                  @endif
                </td>
                <td> 
                  @php $created_at = Carbon\Carbon::parse($user->created_at)->format('j-m-Y'); @endphp
                    {{$created_at}}</td>
                <td class="table-actions text-center">
                <div class="dropdown">
                    <a class="btn btn-link" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="la la-ellipsis-h"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                    <!-- @if(empcan('view_associate_customer_investments')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{route('admin.customer_activitylog',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 viewAssociate" data-toggle="tooltip" title="Activity Log"><i class="material-icons" style="color:#e67300">local_activity</i></a>
                    @endif -->
                    @if(empCan('view_marked_customer_associate')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{route('admin.customer_marked_in_associate',['associate_id'=>$user->id])}}" class="btn btn-default text-primary AddTransationForm" data-toggle="tooltip" data-placement="top" title="View Marked Customer"><i class="material-icons">visibility</i></a>
                    @endif
                    @if(empCan('add_transaction_associate')|| Auth::user()->login_type == 'superadmin')
                      <a data-id="{{$user->id}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary @if($user->associatebalance() <= 0) disabled @else AddTransationForm @endif" data-toggle="tooltip" data-placement="top" title="Add Transaction"><i class="material-icons" style="color:#fc7b03">payments</i></a>
                    @endif
                    @if(empCan('view_transaction_associate')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{route('admin.associateAllTransactions',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-success" data-toggle="tooltip" title="View Transactions"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a>
                    @endif


                    <!-- <a href="{{route('admin.customer_wise_associate_comm',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-success" data-toggle="tooltip" title="Customer Wise Commission"  data-id="{{$user->id}}"><i class="material-icons">₹</i></a> -->


                    <!-- <a href="{{route('admin.associate_view',$user->id)}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Associate"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a> -->
                    @if(empCan('status_associate') || Auth::user()->login_type == 'superadmin')
                      <a href="{{url('admin/associateStatus/'.$user->id)}}"  class="btn btn-link btn-icon bigger-130 text-success @if($user->status) @else text-danger @endif" data-toggle="tooltip" title="@if($user->status) Make Inactive @else Make Active @endif"><i class="material-icons">@if($user->status)lock @else lock_open @endif</i>
                      </a>
                    @endif
                   <!--  @if(empCan('edit_associate')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{url('admin/associate_edit/'.$user->id)}}" class="btn btn-default btn-link btn-icon bigger-130 text-info editAssociate" data-toggle="tooltip" title="Edit"  data-id="{{$user->id}}"><i class="material-icons">edit</i></a>
                    @endif -->
                    @if(empCan('delete_associate')|| Auth::user()->login_type == 'superadmin')
                      <!-- <form action="{{route('admin.associateDelete',$user->id)}}" id="delete-form-{{$user->id}}" method="post" >
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                      </form>
                      <a href=""  onclick="if(confirm('Are You Sure Want To delete !')){event.preventDefault(); document.getElementById('delete-form-{{$user->id}}').submit();
                            }else{ event.preventDefault();}"
                            class="btn btn-link btn-icon bigger-130 text-danger" data-toggle="tooltip" 
                            title="Delete"><i class="material-icons">delete_outline</i></a> -->
                     <!--  <a class="btn btn-link btn-icon bigger-130 text-danger delete_confirm" data-toggle="tooltip" title="Delete" data-id="{{$user->id}}"><i class="material-icons">delete_outline</i></a> -->
                    @endif
                  </div>
                </div>
              </td>
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
         
{{$users->links()}}
<div class="modal fade" id="addTransationModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/associate/addTransaction') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Withdraw Amount</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="add-transaction-container">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    </div>
</div>

<!------------------------Delete associate --------------------->
<div class="modal fade" id="delete_associate" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form action="{{route('admin.associateDelete')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Delete associate</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="delete_associate-container">

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


$('.AddTransationForm').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.associateAddTransationForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#add-transaction-container').html(data);
        $('#addTransationModal').modal('show');
      
    }
  });
});

$('.delete_confirm').click(function(){
  var id = $(this).data('id');
  // alert(id);
  $.ajax({
    url:'{{route("admin.associate_delete_confirm")}}',
    type:'post',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_associate-container').html(data);
        $('#delete_associate').modal('show');
      
    }
  });
});


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




         
