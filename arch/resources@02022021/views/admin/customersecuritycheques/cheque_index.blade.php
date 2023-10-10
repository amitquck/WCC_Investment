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


  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Name</th>
            <th scope="col">Cheque Issue Date</th>
            <th scope="col">Cheque Maturity Date</th>
            <th scope="col">Cheque Bank Name</th>
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
                  <td>{{$cheque->cheque_user->name}}<br><p class="text-info">{{$cheque->cheque_user->code}}</p></td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_issue_date)->format('j M Y')}}</td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_maturity_date)->format('j M Y')}}</td>
                  <td>{{$cheque->cheque_bank_name}}</td>
                  <td>₹ {{$cheque->cheque_amount}}</td>
                  <td>
                    {{Carbon\Carbon::parse($cheque->created_at)->format('j M Y')}}
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