@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate Commission List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash') 
  @if($associate_reward->count()>0)
    @php
      $params = [];
      if(Request::has('month'))
           $params = array_merge(['month' => Request::input('month')],$params);
      if(Request::has('year'))
           $params = array_merge(['year' => Request::input('year')],$params);
    @endphp
    @if(empcan('export_associate_commission') || Auth::user()->login_type == 'superadmin')
      <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_associate_commission_list',$params)}}" id="excel_associate_commission_list" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
      </div>
    @endif
  @endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-3">
        <select class="form-control" name="month">
          <option value=""> Select Month</option>
              @for($i=01; $i<=12; $i++)
                  <option value="{{$i}}" @if(old('month') == $i) selected="selected" @endif>{{Carbon\Carbon::parse('01-'.$i.'-'.date('Y'))->format('M')}}</option>
              @endfor
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-control" name="year">
            <option value="">Select Year</option>
            @for($i=date('Y'); $i>=2019; $i--)
                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
            @endfor
        </select>
      </div>
      <div class="col-md-1 mt-2">
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
            <th scope="col">Associate Code</th>
            <th scope="col">Associate Name</th>
            <th scope="col">Commission Amount</th>
            <th scope="col">Month</th>
            <th scope="col">Year</th>
            <th scope="col">Account No</th>
            <th scope="col">IFSC Code</th>
          </tr>
        </thead>
        <tbody>
          @if($associate_reward->count()>0)
            @foreach($associate_reward as $key => $reward)
               <tr>
                 <td>{{($associate_reward->currentpage()-1)*$associate_reward->perpage()+$key+1}}.</td>
                 <td class="text-info"><a href="{{route('admin.associate_view',$reward->associate->id)}}" data-toggle="tooltip" title="View Details">{{$reward->associate->code}}</td>
                 <td>{{$reward->associate->name}}</td>
                 <td class="text-success"> ₹ {{$reward->sum_monthly_commission}}</td>
                 <td>{{$reward->month}}</td>
                 <td>{{$reward->year}}</td>
                 <td>{{$reward->associate->associatedetail->account_number?$reward->associate->associatedetail->account_number:'N/A'}}</td>
                 <td>{{$reward->associate->associatedetail->ifsc_code?$reward->associate->associatedetail->ifsc_code:'N/A'}}</td>
               </tr>
            @endforeach
          @else
            <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
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
@endphp
{{$associate_reward->appends($params)->links()}}
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




         
