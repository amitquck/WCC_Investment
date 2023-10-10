@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associates List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  @if($associate_txn->count()>0)
    <div class="col-md-3" style="margin-left:85%">
      <a href="{{route('admin.excel_customer_wise_associate_comm',[encrypt($user->id),'month'=>$month,'year'=>$year])}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
  @endif
  <div class="card card-style-1 mt-3">
    <div class="text-center text-light" style="background-color:#3779bc">{{$user->name.' ('.$user->code.')'}}</div>
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Commission Amount</th>
            <th scope="col" class="text-center">No Of Introducer</th>
            <th scope="col" class="text-center">Associate Comm %</th>
            <th scope="col">Customer Mark Date</th>
          </tr>
        </thead>
        <tbody>
            @if($associate_txn->count()>0)
              @foreach($associate_txn as $key => $txn)
              <tr>
                <td>{{($associate_txn->currentpage()-1)*$associate_txn->perpage()+$key+1}}.</td>
                <td><a href="{{route('admin.customer_detail',encrypt($txn->customer_id))}}" class="text-info" data-toggle="tooltip" title="View Details">{{$txn->customer->code}}</a></td>
                <td>{{$txn->customer->name}}</td>
                @if($month != '' && $year != '')
                  <td class="text-success">{{$txn->getMonthlyCommission($txn->customer_id,$txn->associate_id,$month,$year)}}</td>
                @else
                  <td class="text-success">{{$txn->getCommission($txn->customer_id,$txn->associate_id)}}</td>
                @endif
                <td class="text-center">{{$txn->introducer_rank->no_of_introducer}}</td>
                <td class="text-center">{{$txn->introducer_rank->commission_percent}} %</td>
                <td>{{Carbon\Carbon::parse($txn->created_at)->format('j-m-Y')}}</td>
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
         
{{$associate_txn->links()}}
@endsection





         
