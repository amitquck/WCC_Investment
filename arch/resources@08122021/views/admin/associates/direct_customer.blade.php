@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">Direct Customer</li>
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
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            {{-- <th scope="col">Investment Amount</th>
            <th scope="col">Commission %</th>
            <th scope="col">Deposit Date</th>
            <th scope="col">Created At</th> --}}
          </tr>
        </thead>
        <tbody>
            @if($assCust->count()>0)
              @foreach($assCust as $key => $user)
              <tr>

                <td class="">{{($assCust->currentpage()-1)*$assCust->perpage()+$key+1}}.</td>
                <td class=""><a href="{{route('admin.customer_detail',encrypt($user->customer->id))}}" class="text-info" data-toggle="tooltip" title="View Detail">{{$user->customer->code}}</a></td>
                <td class="">{{$user->customer->name}}</td>
                {{-- <td class="text-success">₹ {{ $user->customer->customerd?$user->customer->customerd->amount:'N/A' }}</td>
                <td class="">{{$user->commission_percent}} %</td>
                <td class="">
                  @if($user->customer->customerd != NULL)
                  {{date('d-m-Y',strtotime($user->customer->customerd->deposit_date))}}
                  @else
                  N/A
                  @endif
                </td>
                <td class="">{{date('d-m-Y',strtotime($user->created_at))}}</td> --}}

              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
    {{$assCust->links()}}


@endsection


@section('page_js')

@endsection





