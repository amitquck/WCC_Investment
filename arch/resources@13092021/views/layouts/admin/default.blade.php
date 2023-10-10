<!DOCTYPE HTML>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
  <title>WCC :: Admin</title>
  <link rel="icon" href="{{ URL::asset('img/favicon.png') }}" type="image/x-icon"/>
<!-- {{env('APP_NAME')}} --> 
  

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->

  <!-- Font & Icon -->
  <link href="{{asset('dist/font/roboto.min.css')}}" rel="stylesheet">
  <link href="{{asset('dist/font/roboto-condensed.min.css')}}" rel="stylesheet">
  <link href="{{asset('plugins/material-design-icons-iconfont/material-design-icons.min.css')}}" rel="stylesheet">
   


  <link href="{{asset('plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
  
   
  
  <link href="{{asset('plugins/material-design-icons-iconfont/custom.css')}}" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- Plugins -->
  <!-- CSS plugins goes here -->
<!----------iconcss--------->
  <link href="{{asset('css/quick-icon-style.css')}}" rel="stylesheet">
  <link href="{{asset('css/common.min.css')}}" rel="stylesheet">
  <link href="{{asset('fonts/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet">
  <!----------------------->
  <!-- Main Style -->
  <link rel="stylesheet" href="{{asset('dist/css/style.min.css')}}" id="main-css">
  <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}" id="main-css">
  <link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}" id="main-css">
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker-bs3.css')}}" id="main-css">
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}" id="main-css">
  <link rel="stylesheet" href="{{asset('dist/css/sidebar-blue.min.css')}}" id="sidebar-css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
   <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
    
  <?php /*<link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"> */?>

  <style>
    .ui-autocomplete ui-front ui-menu ui-widget ui-widget-content{
      margin-left:6px important!;
    }
  </style>
  @section('admin_head')
  @show
  
</head>

<body>

@include('layouts.admin.includes.left_sidebar')

<div class="main">
  
  @include('layouts.admin.includes.header')
  <div class="main-body">
    @section('content')

    @show
  </div>
</div>

@include('layouts.admin.includes.footer')

<script>
  $( function() {
    // $( document ).tooltip();
  } );
  $('.delete-confirm').on('click', function (event) {
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
    }).then(function(value) {
        if (value) {
            window.location.href = url;
        }
    });
});
  </script>
@section('page_js')

@show

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
// $('.datepicker').datepicker({
//     startDate: '-3d',
//     dateFormat: 'dd-mm-yy'
// });
</script>
</body>
</html>
