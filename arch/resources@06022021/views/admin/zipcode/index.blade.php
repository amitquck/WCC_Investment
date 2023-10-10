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
          <li class="breadcrumb-item active" aria-current="page">Zipcode List</li>
        </ol>
      </nav>
      <!-- /Breadcrumb -->

      <div class="d-flex">
        <div class="list-with-gap">
          <button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addZipcode"><i class="material-icons mr-2">add_circle_outline</i>Add Zipcode</button>
         
        </div>
       
      </div>
      @include('flash')
      <div class="card card-style-1 mt-3">
        <div class="table-responsive">
          <table class="table table-nostretch table-align-middle mb-0">
            <thead>
         
              <tr>
                <th scope="col" class="text-center">Sr No:</th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Country  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">State  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">City  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Zipcode  </a></th>
                <th scope="col"><a href="javascript:void(0)" class="sorting asc">Created At</a></th>
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting">Status</a></th>
                <th scope="col" class="text-center"><a href="javascript:void(0)" class="sorting" colspan="">Action</a></th>
              </tr>
            </thead>
            <tbody>
            @foreach($zipcodes as $key => $zipcode)
              <tr>
                <td class="text-center">{{($zipcodes->currentpage()-1)*$zipcodes->perpage()+$key+1}}</td>
                <td>@isset($zipcode->countrydetail->name){{$zipcode->countrydetail->name}} @endisset </td>
                <td> @isset($zipcode->statedetail->name){{$zipcode->statedetail->name}} @endisset</td>
                <td>@isset($zipcode->citydetail->name){{$zipcode->citydetail->name}} @endisset</td>
                <td>{{$zipcode->zipcode}}</td>
                <td>{{$zipcode->created_at}}</td>
                <td class="text-center h5">@if($zipcode->status)<span class="badge badge-pill badge-success">Active</span>@else <span class="badge badge-pill badge-danger">Inactive</span>@endif</td>
                <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{url('Admin/zipcode/zipcodestatus/'.$zipcode->id)}}"  class="btn btn-link btn-icon bigger-130 text-success @if($zipcode->status) @else text-danger @endif" data-toggle="tooltip" title="@if($zipcode->status) Make Inactive @else Make Active @endif">
                  <i class="material-icons">@if($zipcode->status)lock @else lock_open @endif</i>
                  </a>
               
                  <a  class="btn btn-default  bigger-130 text-success editzipcode" title="Edit"  data-id="{{$zipcode->id}}"><i class="material-icons">edit</i></a>

                  <form action="{{ route('admin.zipcodeDelete',$zipcode->id) }}" id="delete-form-{{$zipcode->id}}" method="post" >
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                  </form>
                  <a href="{{ route('admin.zipcodeDelete',$zipcode->id) }}"  
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
      
      {{$zipcodes->links()}}

      <!-- --------------------------------Add Zipcode------------------------------- -->
      <div class="modal fade" id="addZipcode" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{ route('admin.zipstore') }}" method="post">
            {{ csrf_field()}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Add Zipcode</h6>
                <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
                  <i class="material-icons">close</i>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="country">Country</label>
                  <select class="form-control" name="country" id="country" >
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
                  <label for="country">State</label>
                  <select class="form-control" name="state" id="state">
                    <option value="">Select State</option>
                      @foreach($states as $id=>$state)
                        <option value="{{$id}}">{{$state}}</option>
                      @endforeach
                  </select>
                  @if($errors->has('state'))
                    <p>{{ $errors->first() }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label for="City">City</label>
                  <select class="form-control" name="city" id="city">
                    <option value="">Select City</option>
                      @foreach($cities as $id=>$city)
                        <option value="{{$id}}">{{$city}}</option>
                      @endforeach 
                  </select>
                  @if($errors->has('city'))
                    <p>{{ $errors->first() }}</p>
                  @endif
                </div>
                
                <div class="form-group">
                  <label for="zipcode">Name</label>
                  <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Enter Zipcode">
                  @if($errors->has('zipcode'))
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

    <!----------------------------- Edit Zipcode Modal------------------------- -->
      <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form action="{{route('admin.zipcodeupdate')}}" method="POST">
            {{ csrf_field()}}
            {{ method_field('POST')}}
              <div class="modal-header d-flex align-items-center bg-primary text-white">
                <h6 class="modal-title mb-0" id="addUserLabel">Edit Zipcode</h6>
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
   
    <!-- ------------------------------------------------------------------ -->
   
@endsection
@section('page_js')
  <script>
   $(document).ready(function(){ 
     $('.editzipcode').click(function(){
      var id = $(this).data('id');
      $.ajax({
        url:'{{route("admin.editzipcode")}}',
        type:'POST',
        data:{id:id,_token:'{!! csrf_token() !!}'},
        success:function(data){
            $('#edit-container').html(data);
            $('#editModal').modal('show');
          
        }
      });
    });
    $('#country').change(function(){
      $('#state').html('<option>Select State </option>');
      var id = $('#country').val();
      $.ajax({
        url: "{{ route('admin.getCountryState') }}",
        type: 'POST',
        dataType:'json',
        data:{id:id,_token:'{!! csrf_token() !!}'},
        success: function(state)
        {
          // alert(state);
          $.each(state,function(key,value){  
            $('#state').append('<option value="'+key+'">'+value+'</option>');
          })
        },
        error: function(state)
        {
          alert('faild');
        }

    });
    }); 

    $('#state').change(function(){
      $('#city').html('<option>Select City </option>');
      var state_id = $('#state').val();
      $.ajax({
        url: "{{ route('admin.getStateCity') }}",
        type: 'POST',
        dataType:'json',
        data:{state_id:state_id,_token:'{!! csrf_token() !!}'},
        success: function(city)
        {
          // alert(state);
          $.each(city,function(key,value){  
            $('#city').append('<option value="'+key+'">'+value+'</option>');
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
