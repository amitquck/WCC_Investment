
<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
  <title>{{env('APP_NAME')}} :: Admin</title>
  <link rel="icon" href="{{ URL::asset('img/favicon.png') }}" type="image/x-icon"/>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


  <!-- Font & Icon -->
  <link href="{{asset('dist/font/roboto.min.css')}}" rel="stylesheet">
  <link href="{{asset('plugins/material-design-icons-iconfont/material-design-icons.min.css')}}" rel="stylesheet">

  <!-- Plugins -->
  <!-- CSS plugins goes here -->

  <link rel="stylesheet" href="{{asset('dist/css/style.min.css')}}" id="main-css">
</head>

<body class="login-page">



  <!-- Main Scripts -->

  <!-- Plugins -->
  <!-- JS plugins goes here -->


    @yield('content')

    <!-- jQuery 2.1.4 -->
   
    
  </body>
</html>