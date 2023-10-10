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
          @if($users->count()>0)
            @foreach($users as $user)
              @foreach($user->customersecuritycheque as $key => $cheque)
                <tr>
                  <td>{{$key+1}}.</td>
                  <td>{{$user->name}}<br><p class="text-info">{{$user->code}}</p></td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_issue_date)->format('j M Y')}}</td>
                  <td>{{Carbon\Carbon::parse($cheque->cheque_maturity_date)->format('j M Y')}}</td>
                  <td>{{$cheque->cheque_bank_name}}</td>
                  <td>₹ {{$cheque->cheque_amount}}</td>
                  <td>
                    {{Carbon\Carbon::parse($cheque->created_at)->format('j M Y')}}
                  </td>
                </tr>
              @endforeach
            @endforeach
          @else

          @endif
        </tbody>
      </table>
    </div>
  </div>
       
       



@endsection


@section('page_js')

@endsection