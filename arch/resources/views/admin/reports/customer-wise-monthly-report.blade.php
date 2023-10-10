@extends('layouts.admin.default')


@section('content')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

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
    @if($monthlyReport)
      @php
          $params = [];
          if(Request::has('id'))
             $params = array_merge(['id' => Request::input('id')],$params);
      @endphp
      <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_customer_wise_monthly_report',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
      </div>
    @endif
    <div class="col-md-12">
      <form action="{{ route('admin.customerWiseMonthlyReport') }}" method="get">
        <div class="row">
          <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Search" name="" id="cust_invest" autocomplete="off" data-toggle="tooltip" title="Search By Customer Name"><input type="hidden" name="id" id="hidden_cust_invest">
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
    @foreach($monthlyReport as $key => $monthd)
      
      <div class="mt-3">
        <div class="card card-primary">
              <div class="card-header">
                  {{Carbon\Carbon::parse($key)->format('M-Y')}}
              </div>
          <div class="card-body">
            <div class="card-title">
                <strong>Customer Interest - </strong><span> ₹ {{($monthd['interest_amount'])}}</span>
            </div>
            <div class="card-title">
                <strong>Associate Commissions</strong>
            </div>
            <div class="card card-primary">
              <table class="table">
                <tr>
                <th>S.No</th>
                  <th>Associate Details</th>
                  <th>Commission</th>
                </tr>
                <tbody>
                  @foreach($monthd['commissions'] as $key => $commission)
                  <tr>
                    <td>{{$key+1}}.</td>
                    <td> <a href="{{ url('admin/associate_view/'.$commission->associate->id) }}">{{$commission->associate->name}} </a><br>{{$commission->associate->mobile}}<br>
                    {{$commission->associate->email}}
                    </td>
                    <td> ₹ {{$commission->customer_sum_monthly_commission}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endforeach
@endsection

@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
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

