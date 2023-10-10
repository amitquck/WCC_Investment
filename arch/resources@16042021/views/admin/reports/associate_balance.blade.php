@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate % Wise Balance List</li>
    </ol>
  </nav>

@php
    $params = [];
    if(Request::has('month'))
       $params = array_merge(['month' => Request::input('month')],$params);
    if(Request::has('year'))
       $params = array_merge(['year' => Request::input('year')],$params);
    if(Request::has('customer'))
       $params = array_merge(['customer' => Request::input('customer')],$params);
    if(Request::has('associate'))
       $params = array_merge(['associate' => Request::input('associate')],$params);
@endphp
@if(empcan('export_associate_per_wise_balance') || Auth::user()->login_type == 'superadmin')
  <div class="col-md-3" style="margin-left:85%">
    <a href="{{route('admin.excel_associate_per_balance',$params)}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
  </div>
@endif
<form action="" method="get">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-2">
        <label for="customer">Customer</label>
        <input type="text" name="customer" class="form-control" placeholder="Code Or Name">
      </div>
      <h6 style="font-size:10px;">OR</h6>
      <div class="col-md-2">
        <label for="associate">Associate</label>
        <input type="text" name="associate" class="form-control" placeholder="Code Or Name">
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
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Balance</th>
            <th scope="col">Interest %</th>
            <th scope="col">Interest</th>
            <th scope="col">Associate Brokerage</th>
            <!-- <th>Month</th>
            <th>Year</th> -->
          </tr>
        </thead>
        <tbody>
          @if($customers->count()>0)
            @foreach($customers as $key => $customer)
              <tr>
                <td>{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>
                @if($customer->login_type == 'customer')
                  <td class="text-primary"><a href="{{route('admin.customer_detail',encrypt($customer->id))}}" data-toggle="tooltip" title="View Details">{{$customer->code}}</a></td>
                  <td class="text-primary">{{$customer->name}}</td>
                  @if($month != '' && $year != '')
                  @php $from_date = date($year.'-'.$month.'-'.'01'); @endphp
                  @php $to_date = date($year.'-'.$month.'-'.'31'); @endphp
                    <td class="text-danger">
                      @if($customer->customer_monthly_balance_recalculate($month,$year) > 0)
                        <a href="{{route('admin.customer_transactions',['customer'=>$customer->code, 'from_date' => $from_date, 'to_date' => $to_date])}}" class="text-success" data-toggle="tooltip" title="View Ladger Balance">₹ {{$customer->customer_monthly_balance_recalculate($month,$year)}}</a>
                      @else
                        <a href="{{route('admin.customer_transactions',['customer'=>$customer->code, 'from_date' => $from_date, 'to_date' => $to_date])}}" class="text-danger" data-toggle="tooltip" title="View Ladger Balance">₹ {{$customer->customer_monthly_balance_recalculate($month,$year)}}</a>
                      @endif
                    </td>
                  @else
                    <td class="text-danger">
                      @if($customer->customer_current_balance() > 0)
                        <a href="{{route('admin.customer_transactions',['customer'=>$customer->code])}}" class="text-success" data-toggle="tooltip" title="View Ladger Balance">₹ {{$customer->customer_current_balance()}}</a>
                      @else
                        <a href="{{route('admin.customer_transactions',['customer'=>$customer->code])}}" class="text-danger" data-toggle="tooltip" title="View Ladger Balance">₹ {{$customer->customer_current_balance()}}</a>
                      @endif
                    </td>
                  <td class="text-primary"><a href="{{route('admin.customer_associate_commission',encrypt($customer->id))}}"data-toggle="tooltip" title="View Detail">{{$customer->customeractiveinterestpercent?$customer->customeractiveinterestpercent->interest_percent:'N/A'}} %</a></td>
                  @if($customer->customeractiveinterestpercent->interest_percent != null)
                    <td class="text-success">
                     ₹ {{(($customer->customer_current_balance()*$customer->customeractiveinterestpercent->interest_percent)/100)/12}}
                    </td>
                  @endif
                  @endif
                @else
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                @endif
                <td>
                  <table class="table table-bordered">
                    <tr>
                      <th scope="col">No Of Introducer</th>
                      <th scope="col">Associate Code</th>
                      <th scope="col">Associate Name</th>
                      <th scope="col">Commisson %</th>
                      <th scope="col">Brokerage</th>
                    </tr>
                    @php $commissions = $customer->associatecommissions->where('status',1); @endphp
                  @if($commissions->count()>0)
                    @foreach($commissions as $key => $associate)
                        <tr>
                          <td class="text-primary">{{$associate->no_of_introducer}}</td>
                          <td><a class="text-info" href="{{route('admin.associate_view',($associate->associate->id))}}" data-toggle="tooltip" title="View Details">{{$associate->associate->code}}</a></td>
                          <td class="text-info">{{$associate->associate->name}}</td>
                          <td>
                            <a href="{{route('admin.customer_associate_commission',encrypt($customer->id))}}" class="text-success" data-toggle="tooltip" title="View Commission">{{$associate->commission_percent}} %</a>
                          </td>
                          <!-- @if($month != '' && $year != '')
                            <td class="text-danger">₹ {{($customer->customer_monthly_balance_recalculate($month,$year)*$associate->commission_percent)/100}}</td>
                          @else -->
                          <td>
                            @php $associate_balance = (($customer->customer_current_balance()*$associate->commission_percent)/100)/12; @endphp
                            @if($associate_balance > 0)
                              <p class="text-success">₹ {{$associate_balance}}</p>
                            @else
                              <p class="text-danger">₹ {{$associate_balance}}</p>
                            @endif
                          </td>
                          <!-- @endif -->
                        </tr>
                    @endforeach
                  @else
                    <tr><td colspan="8"><h6 class="text-center text-danger">No Record Found</h6></td></tr>
                  @endif
                  </table>
                </td>
                <!-- @if($month != '' && $year != '')
                  <td>{{$month}}</td>
                  <td>{{$year}}</td>
                @else
                  <td>N/A</td>
                  <td>N/A</td>
                @endif -->
              </tr>
            @endforeach
          @else
            <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
@php
    $params = [];
    if(Request::has('month'))
       $params = array_merge(['month' => Request::input('month')],$params);
    if(Request::has('year'))
       $params = array_merge(['year' => Request::input('year')],$params);
    if(Request::has('customer'))
       $params = array_merge(['customer' => Request::input('customer')],$params);
    if(Request::has('associate'))
       $params = array_merge(['associate' => Request::input('associate')],$params);
@endphp
{{$customers->appends($params)->links()}}
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">
// $('.get_associate').autocomplete({
//   source:"{{route('admin.associatecustomer')}}",
//   minLength: 2,
//   select:function(event,ui){
//     $('.get_associate').val(ui.item.name);
//     $('#hidden_associate').val(ui.item.id);

//   }
// });
</script>
@endsection




         
