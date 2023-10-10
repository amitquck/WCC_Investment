@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Associate % Wise Balance List</li>
    </ol>
  </nav>


  
<div class="col-md-3" style="margin-left:85%">
  <a href="{{route('admin.excel_associate_per_balance')}}" id="excel_customer_investments" class="btn btn-danger btn-sm"><i class="material-icons">import_export</i> Excel Export</a>
</div>
  <div class="card card-style-1 mt-3">
  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Customer Code</th>
            <th scope="col">Customer Name</th>
            <th scope="col">Balance</th>
            <th scope="col">Associate Breakage</th>
          </tr>
        </thead>
        <tbody>
          @if($customers->count()>0)
            @foreach($customers as $key => $customer)
              <tr>
                <td>{{($customers->currentpage()-1)*$customers->perpage()+$key+1}}.</td>
                <td class="text-primary"><a href="{{route('admin.customer_detail',encrypt($customer->id))}}" data-toggle="tooltip" title="View Details">{{$customer->code}}</a></td>
                <td class="text-primary">{{$customer->name}}</td>
                <td class="text-danger">₹ {{$customer->customer_current_balance()}}</td>
                <td>
                  <table class="table table-bordered">
                    <tr>
                      <th scope="col">Sr No:</th>
                      <th scope="col">Associate Code</th>
                      <th scope="col">Associate Name</th>
                      <th scope="col">Commisson %</th>
                      <th scope="col">Balance</th>
                    </tr>
                  @if($customer->associatecommissions->count()>0)
                    @foreach($customer->associatecommissions as $key => $associate)
                        <tr>
                          <td>{{$key+1}}.</td>
                          <td><a class="text-info" href="{{route('admin.associate_view',($associate->associate->id))}}" data-toggle="tooltip" title="View Details">{{$associate->associate->code}}</a></td>
                          <td class="text-info">{{$associate->associate->name}}</td>
                          <td class="text-success">{{$associate->commission_percent}} %</td>
                          <td class="text-danger">₹ {{($customer->customer_current_balance()*$associate->commission_percent)/100}}</td>
                        </tr>
                    @endforeach
                  @else
                    <tr><td colspan="8"><h6 class="text-center text-danger">No Record Found</h6></td></tr>
                  @endif
                  </table>
                </td>
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
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection




         
