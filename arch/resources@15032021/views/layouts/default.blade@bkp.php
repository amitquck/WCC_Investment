<!DOCTYPE html>
<html>
  <head>
    <title>{{env('APP_NAME')}}</title>
  <!--   <link rel="icon" href="{{ URL::asset('img/favicon.png') }}" type="image/x-icon"/>
 -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/cmenu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/owl.carousel.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap.min.css')}}">
     <link rel="stylesheet" type="text/css" href="{{asset('assets/css/animate.css')}}">
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
  />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/fontawesome-free-5.12.1-web/css/all.css')}}">
    <!--h5-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
    <!---model-fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:wght@700&display=swap" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/img/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('assets/img/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/img/favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/img/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/img/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/img/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/img/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/img/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('assets/img/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/img/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/img/favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset('assets/img/favicon/ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
  </head>
  <body>
    @include('layouts.includes.header')

    @section('content')

    @show
    @include('layouts.includes.footer')
    <?php /*https://code.jquery.com/jquery-3.5.1.min.js*/ ?>
    <script src="{{asset('assets/js/jquery-2.2.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/menu.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/owl.carousel.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/wow.js')}}"></script>
    <script type="text/javascript">
      new WOW().init();
    </script>
          <script>
            $(document).ready(function() {
              $('#owl-carousel-slider-home').owlCarousel({
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 2,
                    nav: false
                  },
                  1000: {
                    items: 4,
                    nav: true,
                    loop: true,
                    margin: 20
                  }
                }
              })
            })
          </script>
          <script>
            $(document).ready(function() {
              $('#testimonials').owlCarousel({
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 1,
                    nav: false
                  },
                  1000: {
                    items: 2,
                    nav: false,
                    loop: true,
                    margin: 20
                  }
                }
              })
            })
          </script>
           <script>
            $(document).ready(function() {
              $('#google-review').owlCarousel({
                autoplay:true,
                autoplayTimeout:5000,
                autoplayHoverPause:true,
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                  0: {
                    items: 1,
                    nav: true
                  },
                  600: {
                    items: 2,
                    nav: false
                  },
                  1000: {
                    items: 4,
                    nav: true,
                    loop: true,
                    margin: 20
                  }
                }
              })
            })
          </script>
          <script>

            $('.burger, .overlay').click(function(){
              $('.burger').toggleClass('clicked');
              $('.overlay').toggleClass('show');
              $('nav').toggleClass('show');
              $('body').toggleClass('overflow');
            });
        </script>
         <script>
             /*Scroll to top when arrow up clicked BEGIN*/
           $(window).scroll(function() {
           var height = $(window).scrollTop();
           if (height > 100) {
           $('#back2Top').fadeIn();
           } else {
           $('#back2Top').fadeOut();
           }
           });
           $(document).ready(function() {
           $("#back2Top").click(function(event) {
           event.preventDefault();
           $("html, body").animate({ scrollTop: 0 }, "slow");
           return false;
           });
           
           });
        </script>
        <script type="text/javascript">
                $(function(){
              //$(".chevron-down").
              $("div[data-toggle=collapse]").click(function(){
                //alert($(this).children('span').hasClass('fa-chevron-up'));
                  var newClass = $(this).children('span').hasClass('fa-chevron-up')?"fa-chevron-down":"fa-chevron-up";
                //alert(newClass);
                  $(this).parents('ul:first').find('span.fa-chevron-up').removeClass("fa-chevron-up").addClass("fa-chevron-down");
                  $(this).children('span').removeClass('fa-chevron-down').addClass(newClass);
              });
          })
      </script>
  </body>
</html>