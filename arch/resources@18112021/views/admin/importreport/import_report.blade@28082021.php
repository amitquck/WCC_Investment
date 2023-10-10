@extends('layouts.admin.default')
@section('content')

<nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb breadcrumb-style2">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Import Report</li>
</ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')	
  
<div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Payment Type</th>
            <th>Month-Year</th>
            <th>Import Excel Date</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
            @if($records->count()>0)
                @foreach($records as $key => $record)
                    <tr>
                        <td>{{($records->currentpage()-1)*$records->perpage()+$key+1}}.</td>
                        <td>{{$record->payment_type_import_excel}}</td>
                        <td>{{$record->month_year_import_excel?$record->month_year_import_excel:'All'}}</td>
                        <td>{{Carbon\Carbon::parse($record->import_excel_date)->format('d-m-Y')}}</td>
                        <td>
                            <a href="{{route('admin.delete-data',[encrypt($record->payment_type_import_excel),encrypt($record->month_year_import_excel?$record->month_year_import_excel:'All'),encrypt($record->import_excel_date)])}}" class="text-danger" onclick="return confirm('Are You Sure')"><i class="material-icons">delete_outline</i></a>
                        </td>
                    </tr><!--  -->
                @endforeach
            @else
                <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
					
<div class="modal fade" id="delete_associate" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{route('admin.destroyPDC')}}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Delete PDC</h6>
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
{{$records->links()}}
@endsection

@section('page_js')
<script>       
$('.delete_confirm').click(function(){
  var ids = $(this).data('id');
  var splitId = ids.split(",");
  var tableId = splitId[0];
  var custId = splitId[1];
  $.ajax({
    url:'{{route("admin.delCustomerPDC")}}',
    type:'post',
    data:{tableId:tableId,custId:custId,_token:'{!! csrf_token() !!}'},
    success:function(data){
  // alert(data);
        $('#delete_associate-container').html(data);
        $('#delete_associate').modal('show');
      
    }
  });
});
</script>
@endsection