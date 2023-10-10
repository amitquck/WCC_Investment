@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Report</a></li>
      <li class="breadcrumb-item active" aria-current="page">State Business Report</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  @if($users)
    @php
        $params = [];
        if(Request::has('state_id'))
           $params = array_merge(['state_id' => Request::input('state_id')],$params);
        if(Request::has('from_date'))
           $params = array_merge(['from_date' => Request::input('from_date')],$params);
        if(Request::has('to_date'))
           $params = array_merge(['to_date' => Request::input('to_date')],$params);
    @endphp
    @if(empcan('export_associate_wise_customer') || Auth::user()->login_type == 'superadmin')
      <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('admin.excel_state_business',$params)}}" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
      </div>
    @endif
  @endif
  <div class="card card-style-1 mt-3">
    <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
      @if($state)
          <h6 class="text-center">{{($state->name)}}</h6>
      @endif
    </div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Contact Details</th>
            <th scope="col">Balance</th>
            <!-- <th scope="col">Associate Commission %</th> -->
            <!-- <th scope="col">Associate Total Commission</th> -->
          </tr>
        </thead>
        <tbody>
          @if(Request::input('from_date') == null)
            @if($users->count()>0)
                @foreach($users as $key => $user)
                    @if($user->customerMainBalance() != 0)
                        <tr>
                            <td class="text-center">{{$key+1}}.</td>
                            <td class="text-primary"><a @if(empcan('view_detail_customer') || Auth::user()->login_type == 'superadmin') href="{{route('admin.customer_detail',encrypt($user->id))}}" @endif data-toggle="tooltip" title="View Details">{{$user->code}}</a></td>
                            <td>{{ucwords("$user->name")}}</td>

                            <td>
                                <i class="material-icons text-success">phone</i>{{$user->mobile}}<br>
                                <i class="material-icons text-warning">mail</i> {{$user->email?$user->email:'N/A'}}<br>
                            </td>

                            @if($user->customerMainBalance() > 0)
                                <td class="text-success">₹ {{$user->customerMainBalance()}}</td>
                            @else
                                <td class="text-danger">₹ {{$user->customerMainBalance()}}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
          @elseif(date('Y-m',strtotime(Request::input('from_date'))) == date('Y-m',strtotime('-1 month')))
            @if($users->count()>0)
                @foreach($users as $key => $user)
                    @if($user->customerLastMonthMainBalance() != 0)
                        <tr>
                            <td class="text-center">{{$key+1}}.</td>
                            <td class="text-primary"><a @if(empcan('view_detail_customer') || Auth::user()->login_type == 'superadmin') href="{{route('admin.customer_detail',encrypt($user->id))}}" @endif data-toggle="tooltip" title="View Details">{{$user->code}}</a></td>
                            <td>{{ucwords("$user->name")}}</td>

                            <td>
                                <i class="material-icons text-success">phone</i>{{$user->mobile}}<br>
                                <i class="material-icons text-warning">mail</i> {{$user->email?$user->email:'N/A'}}<br>
                            </td>

                            @if($user->customerLastMonthMainBalance() > 0)
                                <td class="text-success">₹ {{$user->customerLastMonthMainBalance()}}</td>
                            @else
                                <td class="text-danger">₹ {{$user->customerLastMonthMainBalance()}}</td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
          @elseif(date('Y-m',strtotime(Request::input('from_date'))) == date('Y-m'))
            @if($users->count()>0)
              @foreach($users as $key => $user)
                @if($user->customerThisMonthMainBalance() != 0)
                    <tr>
                        <td class="text-center">{{$key+1}}.</td>
                        <td class="text-primary"><a @if(empcan('view_detail_customer') || Auth::user()->login_type == 'superadmin') href="{{route('admin.customer_detail',encrypt($user->id))}}" @endif data-toggle="tooltip" title="View Details">{{$user->code}}</a></td>
                        <td>{{ucwords("$user->name")}}</td>

                        <td>
                            <i class="material-icons text-success">phone</i>{{$user->mobile}}<br>
                            <i class="material-icons text-warning">mail</i> {{$user->email?$user->email:'N/A'}}<br>
                        </td>

                        @if($user->customerThisMonthMainBalance() > 0)
                            <td class="text-success">₹ {{$user->customerThisMonthMainBalance()}}</td>
                        @else
                            <td class="text-danger">₹ {{$user->customerThisMonthMainBalance()}}</td>
                        @endif
                    </tr>
                @endif
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
          @endif
        </tbody>
      </table>
    </div>
  </div>
@php
  $params = [];
    if(Request::has('state_id'))
      $params = array_merge(['state_id' => Request::input('state_id')],$params);
    if(Request::has('from_date'))
       $params = array_merge(['from_date' => Request::input('from_date')],$params);
    if(Request::has('to_date'))
       $params = array_merge(['to_date' => Request::input('to_date')],$params);
@endphp
{{$users->appends($params)->links()}}
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
    source:"{{route('admin.associatecustomer')}}",
    minLength: 2,
    select:function(event,ui){
      $('#cust_invest').val(ui.item.name);
      $('#hidden_cust_invest').val(ui.item.id);

    }
  });
  </script>
  <script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();
});
</script>
  @endsection

