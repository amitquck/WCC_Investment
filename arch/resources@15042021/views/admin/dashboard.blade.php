@extends('layouts.admin.default')

@section('content')
  @include('flash') 
	 @if(Auth::user()->login_type == 'superadmin' || Auth::user()->login_type == 'employee')
<div class="row gutters-sm col-md-12">

  <div class="col mb-3">
    <div class="card card-style-1" style="background-color: #ff9966;color:white;">
      <div class="card-body">
          <h2>{{$total_customer}}</h2>
        <div class="d-flex align-items-center font-number mb-1">
        <a href="{{route('admin.acustomer')}}" data-toggle="tooltip" title="View Customers"><h6 class="card-title text-light">Total Customer </h6></a>
           <span><i class="material-icons">person</i></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col mb-3">
    <div class="card card-style-1" style="background-color: #3973ac;color:white;">
      <div class="card-body">
          <h2>{{$total_associate}}</h2>
        <div class="d-flex align-items-center font-number mb-1">
        <a href="{{route('admin.associate')}}" data-toggle="tooltip" title="View Associate"><h6 class="card-title text-light">Total Associate </h6></a>
           <span><i class="material-icons">person</i></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col mb-3" data-toggle="modal" data-target="#basicExampleModal">
    <div class="card card-style-1" style="background-color: #b3cccc;color:white;">
      <div class="card-body">
        <h2>{{$total_deposit - $total_withdraw}}</h2> 
        <div class="d-flex align-items-center font-number mb-1">
        <h6 class="card-title">Customer Balance</h6>
        <span><i class="material-icons">₹</i></span>
        </div>
      </div>
    </div>
  </div>

<!------------>
  <div class="col mb-3" data-toggle="modal" data-target="#lastMonthBusiness">
    <div class="card card-style-1" style="background-color: #005580;color:white;">
      <div class="card-body">
        <h2>{{$last_month_deposit - $last_month_withdraw}}</h2>
        <div class="d-flex align-items-center font-number mb-1">
        <!-- <a href="{{route('admin.debitor_creditor_list',['from_date'=>date('Y-m-01',strtotime('-1 months')),'to_date'=>date('Y-m-t',strtotime('-1 months'))])}}" data-toggle="tooltip" title="View Last Month Business"></a> -->
        <h6 class="card-title text-light">Last Month Business</h6>
        <span><i class="material-icons mr-2">₹</i></span>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>


  <div class="col mb-3" data-toggle="modal" data-target="#thisMonthBusiness">
    <div class="card card-style-1" style="background-color: #3399ff;color:white;">
      <div class="card-body">
        <h2>{{$this_month_txn - $this_month_withdraw}}</h2>
        <div class="d-flex align-items-center font-number mb-1">
        <!-- <a href="{{route('admin.debitor_creditor_list',['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t')])}}" data-toggle="tooltip" title="View This Month Business"></a> -->
        <h6 class="card-title">This Month Business</h6>
          <span><i class="material-icons">₹</i></span>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Business Category</h5>
      </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr><td>1. <a href="{{url('admin/business_category?q=customer')}}">Customer Wise Total Business</a></td></tr>
            <tr><td>2. <a href="{{url('admin/business_category?q=associate')}}">Associate Wise Total Business</a></td></tr>
            <tr><td>3. <a href="{{url('admin/business_category?q=state')}}">State Wise Total Business</a></td></tr>
            <tr><td>4. <a href="{{url('admin/business_category?q=city')}}">City Wise Total Business</a></td></tr>
          </thead>
        </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="lastMonthBusiness" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Last Month Business Category</h5>
      </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr><td>1. <a href="{{route('admin.last_month_business_category',['from_date'=>date('Y-m-01',strtotime('-1 months')),'to_date'=>date('Y-m-t',strtotime('-1 months')),'q'=>'customer'])}}">Customer Wise Last Month Business</a></td></tr>

            <tr><td>2. <a href="{{route('admin.last_month_business_category',['from_date'=>date('Y-m-01',strtotime('-1 months')),'to_date'=>date('Y-m-t',strtotime('-1 months')),'q'=>'associate'])}}">Associate Wise Last Month Business</a></td></tr>

            <tr><td>3. <a href="{{route('admin.last_month_business_category',['from_date'=>date('Y-m-01',strtotime('-1 months')),'to_date'=>date('Y-m-t',strtotime('-1 months')),'q'=>'state'])}}">State Wise Last Month Business</a></td></tr>

            <tr><td>4. <a href="{{route('admin.last_month_business_category',['from_date'=>date('Y-m-01',strtotime('-1 months')),'to_date'=>date('Y-m-t',strtotime('-1 months')),'q'=>'city'])}}">City Wise Last Month Business</a></td></tr>
            
          </thead>
        </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!------------>


<!------------>


<!-- Modal -->
<div class="modal fade" id="thisMonthBusiness" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-md">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">This Month Business Category</h5>
      </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr><td>1. <a href="{{route('admin.this_month_business_category',['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'q'=>'customer'])}}">Customer Wise This Month Business</a></td></tr>
            <tr><td>2. <a href="{{route('admin.this_month_business_category',['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'q'=>'associate'])}}">Associate Wise This Month Business</a></td></tr>
            <tr><td>3. <a href="{{route('admin.this_month_business_category',['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'q'=>'state'])}}">State Wise This Month Business</a></td></tr>
            <tr><td>4. <a href="{{route('admin.this_month_business_category',['from_date'=>date('Y-m-01'),'to_date'=>date('Y-m-t'),'q'=>'city'])}}">City Wise This Month Business</a></td></tr>
          </thead>
        </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="col mb-12">
  <div class="card card-style-1 h-100">
    <div class="card-header py-1">
      <i class="material-icons mr-2">show_chart</i>
      <h6>Monthly Reports</h6>
      
    </div>
    <div class="card-body" style="height: 350px">
      <canvas id="barChart2"></canvas>
    </div>
  </div>
</div><br>



<div class="row  col-md-12">
  <div class="col-md-6">
    <div class="card card-style-1">
      <div class="card-body table-responsive">
        <h6 class="card-title">Top 5 Associates</h6><hr>
        <table class="table">
        	<thead>
        		<tr>
        			<th>Associate</th>
        			<th>Amount</th>
        			<th>Month</th>
              <th>Year</th>
        		</tr>
        	</thead>
        	<tbody>
        		@foreach($top_associates as $topassociate)
            <tr>
              <td>{{$topassociate->associate->name}}
                <p class="text-danger" style="font-size: 12px;"><strong>CODE -</strong><a href="{{route('admin.associate_view',$topassociate->associate->id)}}" class="text-primary" data-toggle="tooltip" title="View Details">{{$topassociate->associate->code}}</a></p>
              </td>
              <td>₹ {{$topassociate->top_invest}}</td>
              <td>{{Carbon\Carbon::parse($topassociate->created_date)->format('m')}}</td>
              <td>{{Carbon\Carbon::parse($topassociate->created_date)->format('Y')}}</td>
            </tr>
        		@endforeach
        	</tbody>
        </table>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card card-style-1">
      <div class="card-body table-responsive">
        <h6 class="card-title">Top 5 Investers</h6><hr>
          <table class="table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Amount</th>
                <th>Member Since</th>
              </tr>
            </thead>
            <tbody>
              @foreach($top_invest as $invest)
                <tr>
                  <td>{{$invest->customers->name}}
                    <p class="text-danger" style="font-size: 12px;"><strong>CODE -</strong><a href="{{route('admin.customer_detail',encrypt($invest->customers->id))}}" data-toggle="tooltip" title="View Details">{{$invest->customers->code}}</a></p>
                  </td>
                  <td>₹ {{$invest->top_customers}}</td>
                  <td>
                     @php 
                      $created_at = Carbon\Carbon::parse($invest->customers->created_at)->format('j-m-Y');
                     @endphp
                  {{$created_at}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
</div>
@endif
 @if(Auth::user()->login_type == 'associate')
<div class="row col-md-12">
  <div class="col mb-4">
    <div class="card card-style-1" style="background-color: #3399ff;color:white;">
      <div class="card-body">
        <h6 class="card-title"> Balance</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">₹</i>
          <h3 class="mb-0 mr-2">{{Auth::user()->associatebalance()}}</h3>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
  
  <div class="col mb-4">
    <div class="card card-style-1"  style="background-color: #c229ff;color:white;">
      <div class="card-body">
        <h6 class="card-title"> Total Commission</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">₹</i>
          <h3 class="mb-0 mr-2">{{Auth::user()->associateTotalCommission()}}</h3>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
  <div class="col mb-4">
    <div class="card card-style-1"  style="background-color: #ffccac;color:white;">
      <div class="card-body">
        <h6 class="card-title"> Total Customers</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">people</i>
          <h3 class="mb-0 mr-2">{{$total_associate_customer}}</h3>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card card-style-1 h-100">
    <div class="card-header py-1">
      <i class="material-icons mr-2">show_chart</i>
      <h6>Associate's  Reports</h6>
      <button type="button" data-action="fullscreen" class="btn btn-sm btn-text-secondary btn-icon rounded-circle shadow-none ml-auto">
        <i class="material-icons">fullscreen</i>
      </button>
    </div>
    <div class="card-body" style="height: 350px">
      <canvas id="barChart4"></canvas>
    </div>
  </div>
</div>
 @endif
 @if(Auth::user()->login_type == 'customer')
<div class="row col-md-12">

  <div class="col mb-3">
    <div class="card card-style-1" style="background-color: red;color:white;">
      <div class="card-body">
        <h6 class="card-title">Total Balance</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">₹</i>
          <h3 class="mb-0 mr-2">{{Auth::user()->customer_current_balance()}}</h3>
        </div>
      	<div class="sparkline-data" ></div>
      </div>
    </div>
  </div>

  <div class="col mb-3">
    <div class="card card-style-1" style="background-color: purple;color:white;">
      <div class="card-body">
        <h6 class="card-title">Total Deposit</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">₹</i>
          <h3 class="mb-0 mr-2">{{Auth::user()->TotalDeposit()}}</h3>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>

  <div class="col mb-3">
    <div class="card card-style-1" style="background-color: #3399ff;color:white;">
      <div class="card-body">
        <h6 class="card-title">Total Interest</h6>
        <div class="d-flex align-items-center font-number mb-1">
          <i class="material-icons mr-2">₹</i>
          <h3 class="mb-0 mr-2">{{Auth::user()->TotalInterest()}}</h3>
        </div>
        <div class="sparkline-data" data-value=""></div>
      </div>
    </div>
  </div>
</div>
<div class="col-md-12">
  <div class="card card-style-1 h-100">
    <div class="card-header py-1">
      <i class="material-icons mr-2">show_chart</i>
      <h6>Customer's Monthly Reports</h6>
      <button type="button" data-action="fullscreen" class="btn btn-sm btn-text-secondary btn-icon rounded-circle shadow-none ml-auto">
        <i class="material-icons">fullscreen</i>
      </button>
    </div>
    <div class="card-body" style="height: 350px">
      <canvas id="barChart3"></canvas>
    </div>
  </div>
</div>
 @endif
@endsection 
@section('page_js')
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
 
<script type="text/javascript">

  //$(function() {

  //$('[data-toggle="modal"]').hover(function() {
    //var modalId = $(this).data('target');
    //$(modalId).modal('show');

  //});

//});
  
    $(() => {
      // Global Chart configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // line border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height

        // Monthly Sales
        new Chart('barChart2', {
        type: 'bar',
        data: {
          labels: ["{!! implode('","',$data['months']) !!}"],
          datasets: [
            {
              label: 'Investments',
              backgroundColor: Chart.helpers.color(green).alpha(0.3).rgbString(),
              borderColor: green,
              data: [{!! implode(',',$data['deposit']) !!}]
            },
            // {
            //   label: 'Interest',
            //   backgroundColor: Chart.helpers.color(yellow).alpha(0.3).rgbString(),
            //   borderColor: yellow,
            //   data: [{!! implode(',',$data['interest']) !!}]
            // },
            {
              label: 'Withdrawl',
              backgroundColor: Chart.helpers.color(red).alpha(0.3).rgbString(),
              borderColor: red,
              data: [{!! implode(',',$data['withdraw']) !!}]
            },
            // {
            //   label: 'Commission',
            //   backgroundColor: Chart.helpers.color(pink).alpha(0.3).rgbString(),
            //   borderColor: pink,
            //   data: [{!! implode(',',$data['commission']) !!}]
            // }
          ]
        },
        options: {
          tooltips: {
            mode: 'index',
            intersect: false
          },
          scales: {
            xAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      })


        // Today Sales
      
    })
</script>	
@if(Auth::user()->login_type == 'customer')
<script>
   $(() => {
      // Global Chart configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // line border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height

        // Monthly Sales
        new Chart('barChart3', {
        type: 'bar',
        data: {
          labels: ["{!! implode('","',$data['months']) !!}"],
          datasets: [
            {
              label: 'Investments',
              backgroundColor: Chart.helpers.color(cyan).alpha(0.3).rgbString(),
              borderColor: cyan,
              data: [{!! implode(',',$customerdata['deposit']) !!}]
            },
            {
              label: 'Interest',
              backgroundColor: Chart.helpers.color(blue).alpha(0.3).rgbString(),
              borderColor: yellow,
              data: [{!! implode(',',$customerdata['interest']) !!}]
            },
             {
              label: 'Withdraw',
              backgroundColor: Chart.helpers.color(yellow).alpha(0.3).rgbString(),
              borderColor: yellow,
              data: [{!! implode(',',$customerdata['withdraw']) !!}]
            },
          ]
        },
        options: {
          tooltips: {
            mode: 'index',
            intersect: false
          },
          scales: {
            xAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      })


        // Today Sales
      
    })
</script>	
@endif
@if(Auth::user()->login_type == 'associate')
<script>
   $(() => {
      // Global Chart configuration
        Chart.defaults.global.elements.rectangle.borderWidth = 1 // bar border width
        Chart.defaults.global.elements.line.borderWidth = 1 // line border width
        Chart.defaults.global.maintainAspectRatio = false // disable the maintain aspect ratio in options then it uses the available height

        // Monthly Sales
        new Chart('barChart4', {
        type: 'bar',
        data: {
          labels: ["{!! implode('","',$data['months']) !!}"],
          datasets: [
            {
              label: 'Commission',
              backgroundColor: Chart.helpers.color(cyan).alpha(0.3).rgbString(),
              borderColor: cyan,
              data: [{!! implode(',',$associatedata['commission']) !!}]
            },
            {
              label: 'Withdraw',
              backgroundColor: Chart.helpers.color(orange).alpha(0.3).rgbString(),
              borderColor: orange,
              data: [{!! implode(',',$associatedata['withdraw']) !!}]
            }
          ]
        },
        options: {
          tooltips: {
            mode: 'index',
            intersect: false
          },
          scales: {
            xAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      })
    })
</script>	
@endif
@endsection 