@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Ladger Balance</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($associate)
    <!-- @php
        $params = [];
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
        if(Request::has('associate'))
           $params = array_merge(['associate' => Request::input('associate')],$params);
    @endphp -->
    <!-- <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_associate_ladger',$params)}}" id="excel_associate_ladger" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div> -->
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <!-- <div class="col-md-3">
        <label for="from_date">From Date </label>
        <input type="text" name="from_date" class="form-control datepicker" placeholder="From Date">
      </div>
      <div class="col-md-3">
        <label for="to_date">To Date </label>
        <input type="text" name="to_date" class="form-control datepicker" placeholder="To Date">
      </div>
     <p>OR</p> -->
      <div class="col-md-3">
        <label for="associate">Associate</label>
        <input type="text" name="associate" class="form-control" placeholder="Code Or Name" id="asso_invest"><input type="hidden" name="id" id="hidden_asso_invest">
      </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
            search
        </button>
      </div>
    </div>
  </div>
</form>
  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th>Code</th>
            <th>Name</th>
            <th scope="col">Balnace</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($associate)
            @php $count = 0; @endphp
              <tr>
                <td>{{$count+1}}. </td>
                <td>{{$associate->code}}</td>
                <td>{{$associate->name}}</td>
                <td>
                  @if($associate->associatebalance() > 0)
                    <p><a @if(empcan('view_detail_associate_ladger_balance') || Auth::user()->login_type == 'superadmin') href="{{route('admin.associate_ladger_customer',['associate_id'=>$associate->id])}}" @endif class="text-success" data-toggle="tooltip" title="View Detail">₹ {{$associate->associatebalance()}}</a></p>
                  @else
                    <p><a href="" class="text-danger" data-toggle="tooltip" title="View Detail">₹ {{$associate->associatebalance()}}</a></p>
                  @endif
                </td>
                <td>{{Carbon\Carbon::parse($associate->created_at)->format('j-m-Y')}}</td>
              </tr>
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
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

<script type="text/javascript">
$('.datepicker').datepicker({
    startDate: '-3d',
    dateFormat : "dd-mm-yy"
});
// $.fn.datepicker.defaults.format = "dd/mm/yyyy";associatebalance
</script>
<!-- <script>
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
</script> -->
<script type="text/javascript">
  $('#asso_invest').autocomplete({
    source:"{{route('admin.associatecustomer')}}",
    minLength: 2,
    select:function(event,ui){
      $('#asso_invest').val(ui.item.name);
      $('#hidden_asso_invest').val(ui.item.id);
    }
  });

</script>
@endsection




         
