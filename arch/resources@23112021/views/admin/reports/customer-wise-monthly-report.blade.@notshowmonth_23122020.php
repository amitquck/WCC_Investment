@extends('layouts.admin.default')


@section('content')
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customer Wise Monthly Report</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
    <div class="col-md-12">
        <form action="{{ route('admin.customerWiseMonthlyReport') }}" method="get">
        <div class="row">
            <div class="col-md-3">
            <tr class="column-filter" id="filter">
                <th>
                <input type="text" class="form-control" placeholder="Search By N,M&C" name="" id="cust_invest" autocomplete="off"><input type="hidden" name="id" id="hidden_cust_invest">
                </th>
            </tr>
            </div>
            <div class="col-md-1">
            <button type="submit" class="btn btn-primary btn-sm" title="Search by name,mobile & code">
                search
            </button>
            </div>
        </div>
        </form>
    </div>
    
    <div class="div">&nbsp;&nbsp;&nbsp;&nbsp;</div>
    @foreach($monthlyReport as $monthd)
   
      <div class="panel-group">
        <div class="panel panel-primary">
          <div class="panel-heading">
          </div>
          <div class="panel-body">
            <div class="panel-heading">
                Customer Interest
            </div>
            <div class="panel panel-primary">
              <table class="table">
                <tr>
                <th>S.No</th>
                  <th>Payable Days</th>
                  <th>Total Amount</th>
                  <th>Interest</th>
                </tr>
                 @foreach($monthd as $key =>$month)
                <tr>
                <td>{{ $key+1 }}</td>
                  <td>{{$month->payable_days }}</td>
                  <td>{{$month->total_amount }}</td>
                  <td>{{($month->total_amount*$month->interest_percent)/100 .'- ('.$month->interest_percent.' %)' }}</td>
                </tr>
               @endforeach
              </table>
            </div>
            <div class="panel-heading">
                Associate Commission
            </div>
            <div class="panel panel-primary">
              <table class="table">
                <tr>
                <th>S.No</th>
                  <th>Associate Details</th>
                   <th>Payable Days</th>
                  <th>Total Amount</th>
                  <th>Commission</th>
                </tr>
                @foreach($associate_reward->where('month',$month->month) as $key => $commission)
                <tr>
                <td>{{ $key+1 }}</td>
                  <td> <a href="{{ url('admin/associate') }}">{{$commission->associate->name}} </a><br>{{$commission->associate->mobile}}<br>
                  {{$commission->associate->email}}</td>
                  <td>{{$commission->payable_days }}</td>
                  <td>{{$commission->total_amount }}</td>
                  <td>{{($commission->total_amount*$commission->commission_percent)/100 .'- ('.$commission->commission_percent.' %)' }}</td>
                </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    
    @endforeach
@endsection

@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  $('#cust_invest').autocomplete({
    source:"{{route('admin.getCustomers')}}",
    minLength: 2,
    select:function(event,ui){
      $('#cust_invest').val(ui.item.name);
      $('#hidden_cust_invest').val(ui.item.id);

    }
  });
  </script>
  @endsection

