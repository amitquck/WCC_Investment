@extends('layouts.admin.default')
@section('content')
	

    <!-- Main header -->
  
    <!-- /Main header -->

    <!-- Main body -->
    <div class="main-body">

      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb breadcrumb-style2">
          <li class="breadcrumb-item"><a href="index-2.html">Home</a></li>
          <li class="breadcrumb-item"><a href="javascript:void(0)">Master</a></li>
          <li class="breadcrumb-item active" aria-current="page">State List</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="d-flex">
        <div class="list-with-gap">
          <button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addState"><i class="material-icons mr-2">add_circle_outline</i>Add State</button>
         
        </div>
       
      </div>
      @include('flash')
      <div class="card card-style-1 mt-3">
        <div class="table-responsive">
          <table class="table table-nostretch table-align-middle mb-0">
            <thead>
         
              <tr>
                <th scope="col" class="text-center">Sr No:</th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Name  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Country  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Created At</a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Updated At</a></th>
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting">Status</a></th>
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting" colspan="">Action</a></th>
              </tr>
            </thead>
            <tbody>
            @foreach($states as $key => $state)
              <tr>
                <td class="text-center">{{($states->currentpage()-1)*$states->perpage()+$key+1}}</td>
                <td>{{$state->name}}</td>
                <td>{{$state->countrydetail->name}}</td>

                <td>{{$state->created}}</td>
                <td>{{$state->updated}}</td>
                <td class="text-center h5">@if($state->status)<span class="badge badge-pill badge-success">Active</span>@else <span class="badge badge-pill badge-danger">Inactive</span> @endif </td>
               
                <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{url('Admin/stateStatus/'.$state->id)}}"  class="btn btn-link btn-icon bigger-130 text-success @if($state->status) @else text-danger @endif" data-toggle="tooltip" title="@if($state->status) Make Inactive @else Make Active @endif">
                  <i class="material-icons">@if($state->status)lock @else lock_open @endif</i>
                  </a>
                <a  class="btn btn-default  bigger-130 text-success edit" title="Edit"  data-id="{{$state->id}}"><i class="material-icons">edit</i></a>
                  <form action="{{ route('admin.stateDelete',$state->id) }}" id="delete-form-{{$state->id}}" method="post" >
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                  </form>
                  <a href="{{ route('admin.stateDelete',$state->id) }}"  
                      class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" 
                      title="Delete"><i class="material-icons">delete_outline</i></a>
                </div>
                </td>
                
              </tr>
              @endforeach
            
            </tbody>
          </table>
        </div>
      </div>
      
      {{$states->links()}}

      <!-- --------------------------------Add State------------------------------- -->
        <div class="modal fade" id="addState" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{ route('admin.store') }}" method="post">
            {{ csrf_field()}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Add State</h6>
                <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                  <i class="material-icons">close</i>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="country">Country <span class="text-danger">*</span></label>
                  <select class="form-control" name="country">
                    <option value="">Select Country</option>
                     @foreach($countries as $id=>$country)
                      <option value="{{$id}}">{{$country}}</option>
                     @endforeach
                  </select>
                  @if($errors->has('country'))
                    <p>{{ $errors->first() }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" id="Name" placeholder="Enter State">
                  @if($errors->has('name'))
                    <p>{{ $errors->first() }}</p>
                  @endif
                </div>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- ------------------------------------------------------------------------ -->
    </div>
   </div>  

<!-- Edit Satte Modal -->
   <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{route('admin.stateupdate')}}" method="post">
            {{ csrf_field()}}
            {{ method_field('POST')}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Edit State</h6>
                <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                  <i class="material-icons">close</i>
                </button>
              </div>
              <div class="modal-body" id="edit-container">
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
          </div>
      </div>
@endsection
@section('page_js')
<script>
   $(document).ready(function(){ 
     $('.edit').click(function(){
      var id = $(this).data('id');
      $.ajax({
        url:'{{route("admin.editstate")}}',
        type:'POST',
        data:{id:id,_token:'{!! csrf_token() !!}'},
        success:function(data){
            $('#edit-container').html(data);
            $('#editModal').modal('show');
          
        }
      });
    });
   }); 
</script>
@endsection
