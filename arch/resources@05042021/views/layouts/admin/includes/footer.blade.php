<?php /*<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.3.8
  </div>
  <strong>Copyright &copy; 2020-2021 <a href="http://www.quickinfotech.co.in/">Quick Infotech</a>.</strong> All rights
  reserved.
</footer>*/ ?> 

<!-- Control Sidebar -->

<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>

<!-- Main Scripts -->


<script src="{{asset('dist/js/script.min.js')}}"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Plugins -->
<script src="{{asset('plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<?php /*
<script src="{{asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>*/ ?>
<script src="{{asset('plugins/daterangepicker/moment.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
<?php /*<script src="{{asset('plugins/daterangepicker/daterangepicker.min.js')}}"></script>*/ ?>
<!-- <script src="{{asset('js/getzip.js')}}"></script> -->
<!-- Plugins -->
<!-- <script src="{{asset('plugins/flatpickr/flatpickr.min.js')}}"></script> -->
<script src="{{asset('plugins/flatpickr/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('dist/js/app.js')}}"></script>
 
  <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->


<script>
  $(() => {

    function run_sparkline() {
      $('.sparkline-data').text('').sparkline('html', {
        width: '100%',
        height: 20,
        lineColor: gray,
        fillColor: false,
        tagValuesAttribute: 'data-value'
      })
    }
    // Run sparkline when the document view (window) has been resized
    App.resize(() => {
      run_sparkline()
    })()
    
    // Run sparkline when the sidebar updated (toggle collapse)
    document.addEventListener('sidebar:update', () => {
      run_sparkline()
    })

  })
</script>

