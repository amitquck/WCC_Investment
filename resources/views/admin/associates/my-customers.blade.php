@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">My Customer</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')

    @if(empcan('export_associate_direct_cust') || Auth::user()->login_type == 'associate')
    <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('associate.export_associate_direct_cust')}}" id="" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
    @endif
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Investment Amount</th>
            <th scope="col">Commission %</th>
            <th scope="col">Last Deposit Date</th>
            <th>Action</th>
            {{--  <th scope="col">Created At</th>  --}}
          </tr>
        </thead>
        <tbody>
            @if($associates->count()>0)
              @foreach($associates as $key => $user)
              <tr>

                <td class="">{{($associates->currentpage()-1)*$associates->perpage()+$key+1}}.</td>
                <td class="">{{$user->customer->code}}</td>
                <td class="">{{$user->customer->name}}</td>
                @php $date = getLastCRDate($user->customer_id); @endphp
                <td class="text-success">₹ {{ $date?$date->amount:'N/A' }}</td>
                <td class="">{{$user->commission_percent}} %</td>
                <td class="">
                  @if($date != NULL)
                  {{date('d-m-Y',strtotime($date->deposit_date))}}
                  @else
                  N/A
                  @endif
                </td>
                <td><a href="{{ route('associate.custTxn',['custId'=>$user->customer->id]) }}" class="btn text-info" title="View Transactions" data-toggle="tooltip"><i class="material-icons">visibility</i></a></td>
                {{--  <td class="">{{date('d-m-Y',strtotime($user->created_at))}}</td>  --}}

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





