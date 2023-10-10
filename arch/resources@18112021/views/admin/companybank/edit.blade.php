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

<!---------------------------------------Edit Bank ------------------------------->
<div class="modal-content">
      <form action="{{ url('admin/updateBank/'.encrypt($bank->id)) }}" method="POST">
      {{ csrf_field()}}
        <div class=" bg-primary text-white">
          <h6 class="modal-title mb-0" id="addUserLabel">&nbsp; Edit Company Bank</h6>
        </div>
        <div class="modal-body" id="editBank-container">

          <div class="col-md-12">
            <div class="row">
            <label for="bank_name">Bank Name <sup class="text-danger">*</sup></label>
            <input type="text" name="bank_name" class="form-control" value="{{$bank->bank_name}}" style="width:100%;" required="required" placeholder="Enter Bank Name">
              @if($errors->has('bank_name'))
                <p style="color:red;">{{ $errors->first('bank_name') }}</p>
              @endif
            </div>
          </div><br>
          <div class="col-md-12">
            <div class="row">
              <label for="amount">Opening Balance <sup class="text-danger">*</sup></label>
              <input type="text" name="amount" class="form-control" value="{{$bank->amount}}" style="width:100%;" required="required" placeholder="Enter Amount" readonly="true">
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





