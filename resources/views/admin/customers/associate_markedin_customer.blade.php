@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page"> Customer's Associate List</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')
  @php
    $params = [];
    if(Request::has('customer_id'))
       $params = array_merge(['customer_id' => Request::input('customer_id')],$params);
  @endphp
  <div class="card card-style-1 mt-3">
  <div style="background-color:#2b579a;color:#fff;padding-top:5px;">
    <h6 class="text-center">{{ucwords($customer->name).' ('.$customer->code.')'}}</h6>
  </div>  
    <div class="table-responsive">
      <table class="table table-nostretch table-align-middle mb-0">
        <thead>
          <tr>
            <th scope="col">Sr No:</th>
            <th scope="col">Associate Code</th>
            <th scope="col">Associate Name</th>
            <th scope="col">Commission % Duration</th>
          </tr>
        </thead>
        <tbody>
            @if($marked_associates->count()>0)
                @foreach($marked_associates as $key => $associate)
                  <tr>
                    <td>{{($marked_associates->currentpage()-1)*$marked_associates->perpage()+$key+1}}.</td>
                    <td><a href="{{route('admin.associate_view',$associate->associate->id)}}" class="text-info" data-toggle="tooltip" title="View Details">{{$associate->associate->code}}</a></td>
                    <td>{{$associate->associate->name}}</td>
                    <td>
                    <table class="table table-bordered">
                      <tr>
                        <th>Sr No</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Interest %</th>
                      </tr>
                      @php 
                        $interests = $associate->associateinterest($associate->associate->id,$customer->id); @endphp
                      @if($interests)
                        @foreach($interests as $keys => $interest)
                          <tr>
                            <td>{{$keys+1}}.</td>
                            <td>{{Carbon\Carbon::parse($interest->start_date)->format('j-m-Y')}}</td>
                            <td>{{$interest->end_date?Carbon\Carbon::parse($interest->end_date)->format('j-m-Y'):'N/A'}}</td>
                            <td>{{$interest->commission_percent}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </table>
                  </tr>
                @endforeach
            @else
              <tr><td colspan="8"><h3 class="text-center text-danger">No Record Found</h3></td></tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
  @php
    $params = [];
    if(Request::has('customer_id'))
       $params = array_merge(['customer_id' => Request::input('customer_id')],$params);
  @endphp
  {{$marked_associates->appends($params)->links()}}

@endsection


@section('page_js')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!-- <script>
$('.AddTransationForm').click(function(){
  var id = $(this).data('id');
  $.ajax({
    url:'{{route("admin.associateAddTransationForm")}}',
    type:'POST',
    data:{id:id,_token:'{!! csrf_token() !!}'},
    success:function(data){
        $('#add-transaction-container').html(data);
        $('#addTransationModal').modal('show');
      
    }
  });
});
function payment(elem)
    {
      // alert('sgd');      
      if($(elem).val()=='cash')
      {
        $(".payment_mode").hide();
      }
      else if($(elem).val()== '')
      {
        $(".payment_mode").hide();
      }
      else
      {
      $(".payment_mode").show();
      }
    }
</script> -->
@endsection




         
