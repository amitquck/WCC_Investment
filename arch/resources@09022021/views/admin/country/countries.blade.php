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
          <li class="breadcrumb-item active" aria-current="page">Country List</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="d-flex">
        <div class="list-with-gap">
          <button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addCountry"><i class="material-icons mr-2">add_circle_outline</i>Add Country</button>
         
        </div>
       
      </div>
      @include('flash')
      <div class="card card-style-1 mt-3">
        <div class="table-responsive">
          <table class="table table-nostretch table-align-middle mb-0">
            <thead>
         
              <tr>
                <th scope="col" class="text-center">Id</th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Name  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Created At</a></th>
                <!-- <th scope="col"><a href="javascript:void(0)" class="sorting asc">Updated At</a></th> -->
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting">Status</a></th>
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting">Action</a></th>
              </tr>
            </thead>
            <tbody>
            @foreach($countries as $key => $country)
              <tr>
                <td class="text-center">{{($countries->currentpage()-1)*$countries->perpage()+$key+1}}</td>
                <td>{{$country->name}}</td>
                <td>{{$country->created}}</td>
                <!-- <td>{{$country->updated_at}}</td> -->
                <td class="text-center h5">@if($country->status)<span class="badge badge-pill badge-success">Active</span>@else <span class="badge badge-pill badge-danger">Inactive</span> @endif</td>
                <td class="text-center">
                  <div class="btn-group btn-group-sm" role="group">
                   <a href="{{ url('Admin/countrystatus/'.$country->id) }}"  class="btn btn-link btn-icon bigger-130 text-success @if($country->status) @else text-danger @endif" data-toggle="tooltip" title="@if($country->status) Make Inactive @else Make Active @endif">
                  <i class="material-icons">@if($country->status)lock @else lock_open @endif</i>
                  </a>
                    
                    <a  class="btn btn-link btn-icon bigger-130 text-success edit" title="Edit"  data-id="{{$country->id}}"><i class="material-icons">edit</i></a>
                    <form action="{{ route('admin.country_delete',$country->id) }}" id="delete-form-{{$country->id}}" method="post" style="display:none;">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    </form>
                    <a href="{{ route('admin.country_delete',$country->id) }}" 
                     class="btn btn-link btn-icon bigger-130 text-danger delete-confirm" 
                     title="Delete"><i class="material-icons">delete_outline</i></a>
                  </div>
                </td>
              </tr>
              @endforeach
            
            </tbody>
          </table>
           {{$countries->links()}}
        </div>
      </div>     
      <!-- Add user Modal -->
      <div class="modal fade" id="addCountry" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{ route('admin.country_data_store') }}" method="post">
            {{ csrf_field()}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Add Country</h6>
                <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                  <i class="material-icons">close</i>
                </button>
              </div>
              <div class="modal-body">
            
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="name" id="Name" placeholder="Enter Country ">
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
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{route('admin.country.update')}}" method="post">
            {{ csrf_field()}}
            {{ method_field('POST')}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Edit Country</h6>
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

    </div>
    <!-- /Main body -->

  </div>
  <!-- /Main -->

  

  <!-- Backdrop for expanded sidebar -->
  <div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>
@endsection
@section('page_js')
  <script type="text/javascript">
    $(document).ready(function(){
       // alert('aebv');
     $('.edit').click(function(){
      // alert('srbh');
      var id = $(this).data('id');

      $.ajax({
        url:'{{route("admin.country.edit")}}',
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