@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Payment List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($associate_txn)
    @php
        $params = [];
        if(Request::has('month'))
           $params = array_merge(['month' => Request::input('month')],$params);
        if(Request::has('year'))
           $params = array_merge(['year' => Request::input('year')],$params);
        if(Request::has('associate'))
           $params = array_merge(['associate' => Request::input('associate')],$params);
    @endphp
    @if(empcan('export_associate_payment') || Auth::user()->login_type == 'superadmin')
      <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_associate_payment_list',$params)}}" id="excel_associate_payment_list" class="btn btn-danger btn-md"><i class="material-icons">import_export</i> Excel Export</a>
      </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <label for="month">Month</label>
        <select class="form-control" name="month">
          <option value=""> Select Month</option>
              @for($i=01; $i<=12; $i++)
                  <option value="{{$i}}" @if(old('month') == $i) selected="selected" @endif>{{Carbon\Carbon::parse('01-'.$i.'-'.date('Y'))->format('M')}}</option>
              @endfor
        </select>
      </div>
      <div class="col-md-3">
        <label for="year">Year</label>
        <select class="form-control" name="year">
            <option value="">Select Year</option>
            @for($i=date('Y'); $i>=2019; $i--)
                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
            @endfor
        </select>
      </div>
      <p>OR</p>
      <div class="col-md-3">
        <label for="associate">Associate </label>
        <input type="text" name="associate" class="form-control" placeholder="Code & Name">
      </div>
      <div class="col-md-1 mt-2">
        <label></label>
        <button type="submit" class="btn btn-primary btn-md" title="Search by name,mobile & code">
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
            <th scope="col">Credit</th>
            <th scope="col">Debit</th>
            <th scope="col">Month</th>
            <th scope="col">Year</th>
            <!-- <th scope="col">Running Balnace</th> -->
            <!-- <th scope="col">Credit/Debit Date</th>
            <th scope="col">Created At</th> -->
          </tr>
        </thead>
        <tbody>
            @if($associate_txn->count()>0)
            @php $total_credit = $total_debit = $running_balance = 0; @endphp
              @foreach($associate_txn as $key => $txn)
                <tr>
                  <td>{{($associate_txn->currentpage()-1)*$associate_txn->perpage()+$key+1}}.</td>
                  <td><a href="" class="text-info" data-toggle="tooltip" title="View Detail">{{$txn->associate->code}}</a></td>
                  <td>{{$txn->associate->name}}</td>

                  <td>
                    @if($monthh != '' && $year != '')
                      <a class="text-success" href="{{route('admin.customer_month_wise_associate_comm',[encrypt($txn->associate->id), $monthh,$year])}}" data-toggle="tooltip" title="View Customer Commission">₹ {{$txn->monthly_total_cr($monthh,$year,$txn->associate->id)}}</a>
                    @else
                      <a class="text-success" href="{{route('admin.customer_wise_associate_comm',encrypt($txn->associate->id))}}" data-toggle="tooltip" title="View Customer Commission">₹ {{$txn->total_cr}}</a>
                    @endif
                  </td>
                  @if($monthh != '' && $year != '')
                    <td class="text-danger">₹ {{$txn->monthly_total_dr($monthh,$year,$txn->associate->id)}}</td>
                    <td>{{$monthh}}</td>
                    <td>{{$year}}</td>
                    @else
                      <td class="text-danger">₹ {{$txn->total_dr}}</td>
                      <td>N/A</td>
                      <td>N/A</td>
                    @endif
                  
                </tr>
              @endforeach
              <!-- <tr><td></td>
                <td colspan="3"><span class="text-primary">Total Credit :  ₹ {{$total_credit}}</span> &nbsp; &nbsp;  ||  &nbsp;  &nbsp;  <span class="text-danger">Total Debit :  ₹ {{$total_debit}}</span>   </td>
              </tr> -->
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
@php
  $params = [];
  if(Request::has('from_date'))
     $params = array_merge(['from_date' => Request::input('from_date')],$params);
  if(Request::has('to_date'))
     $params = array_merge(['to_date' => Request::input('to_date')],$params);
  if(Request::has('customer'))
     $params = array_merge(['customer' => Request::input('customer')],$params);
@endphp
@if($associate_txn)
{{$associate_txn->appends($params)->links()}}

@endif
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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
@endsection




         
