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
              <input type="text" class="form-control" placeholder="Code" name="associate_code" id="associate_code" autocomplete="off">
            </th>
          </tr>
        </div>
        <div class="col-md-3">
          <tr class="column-filter" id="filter">
              <th>
                <input type="text" class="form-control" placeholder="Account No" name="associate_bank_account" id="associate_bank_account" autocomplete="off">
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
            <th scope="col">Sr No:</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Details</th>
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
                <td>{{$user->code}}</td>
                <td>{{$user->name}}</td>
                <td>
                  <i class="material-icons text-danger">phone</i> {{$user->mobile}}<br>
                  <i class="material-icons text-primary">mail</i> {{$user->email}}</td>
                <td class="text-danger">₹ {{$user->associatebalance()}}</td>
                <td> 
                  @php $created_at = Carbon\Carbon::parse($user->created_at)->format('j-M-Y'); @endphp
                    {{$created_at}}</td>
                <td>
                  <div class="btn-group btn-group-sm" role="group"> 


                  @if(empCan('add_transaction_associate')|| Auth::user()->login_type == 'superadmin')
                    <a data-id="{{$user->id}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary AddTransationForm" data-toggle="tooltip" data-placement="top" title="Add Transaction"><i class="material-icons" style="color:#fc7b03">payments</i></a>
                  @endif
                    <a href="{{route('admin.associateAllTransactions',encrypt($user->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-success" data-toggle="tooltip" title="View Transactions"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a>

                    <a href="{{route('admin.associate_view',$user->id)}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Associate"  data-id="{{$user->id}}"><i class="material-icons">visibility</i></a>
                    @if(empCan('status_associate')|| Auth::user()->login_type == 'superadmin')
                    <a href="{{url('admin/associateStatus/'.$user->id)}}"  class="btn btn-link btn-icon bigger-130 text-success @if($user->status) @else text-danger @endif" data-toggle="tooltip" title="@if($user->status) Make Inactive @else Make Active @endif">
                      <i class="material-icons">@if($user->status)lock @else lock_open @endif</i>
                      </a>
                    @endif
                    @if(empCan('edit_associate')|| Auth::user()->login_type == 'superadmin')
                      <a href="{{url('admin/associate_edit/'.$user->id)}}" class="btn btn-default btn-link btn-icon bigger-130 text-info editAssociate" data-toggle="tooltip" title="Edit"  data-id="{{$user->id}}"><i class="material-icons">edit</i></a>
                    @endif
                    @if(empCan('delete_associate')|| Auth::user()->login_type == 'superadmin')
                      <form action="{{route('admin.associateDelete',$user->id)}}" id="delete-form-{{$user->id}}" method="post" >
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                      </form>
                      <a href=""  onclick="if(confirm('Are You Sure Want To delete !')){event.preventDefault(); document.getElementById('delete-form-{{$user->id}}').submit();
                            }else{ event.preventDefault();}"
                            class="btn btn-link btn-icon bigger-130 text-danger" data-toggle="tooltip" 
                            title="Delete"><i class="material-icons">delete_outline</i></a>
                    @endif
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
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- <script>
      $('#associate_name').autocomplete({
        source:"{{route('admin.getAssociate')}}",
        minLength: 3,
        select:function(event,ui){
          $('#associate_name').val(ui.item.name);
          $('#hidden_associate_name').val(ui.item.id);

        }
      });
      
      $('#associate_bank_account').autocomplete({
        source:"{{route('admin.getAssociateAccount')}}",
        minLength: 3,
        select:function(event,ui){
          $('#associate_bank_account').val(ui.item.name);
          $('#hidden_associate_bank_account').val(ui.item.id);

        }
      });

</script> -->
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




         
