
<!DOCTYPE html>
<html>
  <head>
    <title>{{env('APP_NAME')}}</title>
    <link rel="icon" href="{{ URL::asset('img/favicon.png') }}" type="image/x-icon"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/customer/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/customer/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/customer/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/customer/plugins/jquery-ui-1.12.1/jquery-ui.css')}}">
   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/customer/fonts/fontawesome-free-5.12.1-web/css/all.css')}}">
    <!--form-fonts-->
  <link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@700&display=swap" rel="stylesheet">
  @section('head')
  @show
  </head>
  <body >

  @section('content')

  @show
  @include('layouts.customer.includes.footer')
  @section('js')

  
@show
  </body>