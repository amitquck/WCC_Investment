@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">This Month Associate Business</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
    @php $url = Request::url();
        $data = explode("/",$url);
        $param = end($data);
    @endphp
    @if(empcan('export_this_month_associate_business') || Auth::user()->login_type == 'associate')
    <div class="col-md-3" style="margin-left:85%">
        <a href="{{route('associate.export_this_month_associate_business',['param'=>$param])}}" id="" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
    </div>
    @endif
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Amount</th>
            {{--  <th scope="col">Deposit Date</th>  --}}
            {{--  <th>Action</th>  --}}
            {{--  <th scope="col">Created At</th>  --}}
          </tr>
        </thead>
        <tbody>
            @if($customers->count()>0)
              @foreach($customers as $key => $user)
              <tr>
                @php $amt = getTMBalance($user->customer_id,$user->associate_id); @endphp
                @if($amt != 0)
                    <td>{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>
                    @if($user->customer_id)
                        <th>Customer</th>
                    @else
                        <th>Associate</th>
                    @endif
                    <td>{{$user->customer_id?$user->customer->code:$user->associate->code}}</td>
                    <td>{{$user->customer_id?$user->customer->name:$user->associate->name}}</td>

                    @if($amt > 0)
                        <td class="text-success">₹ {{ $amt }}</td>
                    @else
                        <td class="text-danger">₹ {{ $amt }}</td>
                    @endif
                @endif
              </tr>
              @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
    {{$customers->links()}}


@endsection


@section('page_js')

@endsection





