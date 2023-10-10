@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customers On Hold</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')


  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col" class="text-center">Sr No:</th>
            <th scope="col">Name</th>
            <th scope="col">Details</th>
            <th scope="col"class="text-center">Created At</th>
            <th>Balance</th>
            <th>Hold Remarks</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if($customers->count()>0)
          @foreach($customers as $key => $customer)
            <tr>
              <td class="text-center">{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>
              <td><a href="{{route('admin.customer_detail',encrypt($customer->id))}}" data-toggle="tooltip" title="View Detail">{{ucwords("$customer->name")}}</a><br>
                <p class="text-info">{{$customer->code}}</p>
              </td>
              <td>
                <i class="material-icons text-success">phone</i>{{$customer->mobile}}<br>
                <i class="material-icons text-warning">mail</i> {{$customer->email?$customer->email:'N/A'}}<br>
              </td>
              <td class="text-center">
                  @php $created_at = Carbon\Carbon::parse($customer->created_at)->format('j-M-Y'); @endphp
                  {{$created_at}}
              </td>
              <td class="text-danger">₹ {{$customer->customer_current_balance()}}</td>
              <td class="text-danger">{{$customer->hold_remarks}}</td>
              <td>
                <a href="{{route('admin.customerAllTransactions',encrypt($customer->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewAssociate" data-toggle="tooltip" title="View Transactions"  data-id="{{$customer->id}}"><i class="material-icons">visibility</i></a>
              </td>
              
            </tr>
          @endforeach
           @else
          <tr><td colspan="10"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
          @endif

        </tbody>
      </table>
    </div>
  </div>
       
       


{{$customers->links()}}
@endsection


@section('page_js')

@endsection