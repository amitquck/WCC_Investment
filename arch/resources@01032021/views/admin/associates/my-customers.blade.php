@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">My Commission</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	

  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr class="text-center">
            <th scope="col">Sr No:</th>
            <th scope="col">Customer</th>
            <th scope="col">Investment Amount</th>
            <th scope="col">Commission %</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th>
          </tr>
        </thead>
        <tbody>
            @if($associates->count()>0)
              @foreach($associates as $key => $user)
              <tr>
             
                <td class="text-center">{{($associates->currentpage()-1)*$associates->perpage()+$key+1}}.</td>
                <td class="text-center">{{$user->customer->name}}</td>
                <td class="text-center">{{ $user->customer->customerd->amount }}</td>
                <td class="text-center">{{$user->commission_percent}}</td>
                <td class="text-center">{{date('d-m-Y',strtotime($user->customer->customerd->deposit_date))}}</td>
                <td class="text-center">{{date('d-m-Y',strtotime($user->created_at))}}</td>
                  
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
    {{$associates->links()}}     


@endsection


@section('page_js')

@endsection




         
