@extends('layouts.admin.default')
@section('content')
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
      <li class="breadcrumb-item active" aria-current="page">Customers Security Cheques</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  @if(empcan('export_pdc_issued') || Auth::user()->login_type == 'superadmin')
    <div class="col-md-12">
      <a href="{{route('admin.customer_pdc_issue_excel_export')}}" class="btn btn-danger pull-right"> Excel Export</a>
    </div><br><br>
  @endif
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Cheque Issue Date</th>
            <th scope="col">Cheque Maturity Date</th>
            <th scope="col">Cheque Bank Name</th>
            <th scope="col">Cheque Number</th>
            <th scope="col">Cheque Amount</th>
            <th scope="col"class="text-center">Created At</th>
            <!-- <th scope="col" class="text-center">Action</th> -->
          </tr>
        </thead>
        <tbody>
          @if($security_cheque->count()>0)
            @foreach($security_cheque as $key => $cheque)
              @if($cheque->cheque_issue_date)
                <tr>
                  <td>{{$key+1}}.</td>
                  <td class="text-info"><a @if(empcan('view_detail_pdc_issued') || Auth::user()->login_type == 'superadmin')  href="{{route('admin.customer_detail',encrypt($cheque->cheque_user->id))}}" @endif data-toggle="tooltip" title="View Details">{{$cheque->cheque_user->code}}</a></td>
                  <td>{{$cheque->cheque_user->name}}</td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_issue_date)->format('j-m-Y')}}</td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_maturity_date)->format('j-m-Y')}}</td>
                  <td>{{$cheque->cheque_bank_name?$cheque->cheque_bank_name:'N/A'}}</td>
                  <td>{{$cheque->cheque_number?$cheque->cheque_number:'N/A'}}</td>
                  <td class="text-success">₹ {{$cheque->cheque_amount?$cheque->cheque_amount:'N/A'}}</td>
                  <td>
                    {{Carbon\Carbon::parse($cheque->created_at)->format('j-m-Y')}}
                  </td>
                </tr>
              @endif
            @endforeach
          @else
            <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
       
       


{{$security_cheque->links()}}
@endsection


@section('page_js')

@endsection