@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Company Bank List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')	
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6">
        <button class="btn btn-primary has-icon" type="button" data-toggle="modal" data-target="#addbank">Add Bank</button>
      </div>
      <div class="col-md-6">
        <a href="{{route('admin.excel_company_bank')}}" class="btn btn-success pull-right" data-toggle="tooltip" title="Excel Export"><i class="material-icons">import_export</i></a>
      </div>
    </div>
  </div>
  <div class="card card-style-1 mt-3">
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Bank Name</th>
            <th scope="col">Opening Balance</th>
            <th scope="col">Balance</th>
            <th scope="col">Created At</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($banks->count()>0)
            @foreach($banks as $key =>$bank)
              <tr>
                <td>{{($banks->currentpage()-1)*$banks->perpage()+$key+1}}.</td>
                <td>{{$bank->bank_name}}</td>
                <td class="text-success">₹ {{$bank->amount}}</td>
                <td class="text-danger">₹ {{$bank->amount+$bank->bank_current_balance}}</td>
                <td>{{Carbon\Carbon::parse($bank->created_at)->format('j-m-Y')}}</td>
                <td>
                  
                   <a href="{{url('admin/perticularBankTransaction/'.encrypt($bank->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-primary viewBankTransaction" data-toggle="tooltip" title="View Bank Transaction"  data-id="{{$bank->id}}"><i class="material-icons">visibility</i></a>
                  
                    <a href="{{url('admin/editBank/'.encrypt($bank->id))}}" class="btn btn-default btn-link btn-icon bigger-130 text-success" data-toggle="tooltip" title="Edit" data-id="{{$bank->id}}" ><i class="material-icons">edit</i></a>
                 
                </td>
              </tr>
            @endforeach
          @else
            <tr><td colspan="8"><h4 class="text-center text-danger">No Record Found</h4></td></tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
     {{$banks->links()}}    
<!---------------------------------------Add Bank ------------------------------->
<div class="modal fade" id="addbank" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/addBank') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Add Company Bank</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="add-transaction-container">
          <div class="col-md-12">
            <div class="row">
            <label for="bank_name">Bank Name <sup class="text-danger">*</sup></label>
            <input type="text" name="bank_name" class="form-control" style="width:100%;" required="required" placeholder="Enter Bank Name">
              @if($errors->has('bank_name'))
                <p style="color:red;">{{ $errors->first('bank_name') }}</p>
              @endif
            </div>
          </div><br>
          <div class="col-md-12">
            <div class="row">
              <label for="amount">Opening Balance <sup class="text-danger">*</sup></label>
              <input type="text" name="amount" class="form-control" style="width:100%;" required="required" placeholder="Enter Amount">
              @if($errors->has('amount'))
                <p style="color:red;">{{ $errors->first('amount') }}</p>
              @endif
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success mx-auto">submit</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!--------------------------------------------------------------------------------->
         
<!---------------------------------------Edit Bank ------------------------------->
<div class="modal fade" id="editBank" tabindex="-1" role="dialog" aria-labelledby="addEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form action="{{ url('admin/addBank') }}" method="POST">
      {{ csrf_field()}}
        <div class="modal-header d-flex align-items-center bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel"> Edit Company Bank</h6>
          <button class="btn btn-icon btn-sm btn-text-light rounded-circle" type="button" data-dismiss="modal">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body" id="editBank-container">
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success mx-auto">submit</button>
        </div>
      </form>
    </div>
    </div>
</div>
<!--------------------------------------------------------------------------------->
@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>




@endsection




         
