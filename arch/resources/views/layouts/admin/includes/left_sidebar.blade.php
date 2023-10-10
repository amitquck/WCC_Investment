<?php 
 ?>
<!-- Sidebar -->
<div class="sidebar">

  <!-- Sidebar header -->
  <div class="sidebar-header">
    <a href="{{route('admin.dashboard')}}" class="logo">
      <img src="{{ asset('img/logo.png') }}" alt="Quick Infotech" id="main-logo" width="80px;" height="50px;">
      {{env('APP_NAME')}}
    </a>
    <a href="#" class="nav-link nav-icon rounded-circle ml-auto" data-toggle="sidebar">
      <i class="material-icons">close</i>
    </a>
  </div>
  <!-- /Sidebar header -->
 
  <!-- Sidebar body -->
  <div class="sidebar-body">
    <ul class="nav nav-sub" id="menu">
    @if(Auth::user()->login_type == 'superadmin')
      <li class="nav-label">DASHBOARD</li>
      <li class="nav-item {{ (Request::route()->getName() == 'admin.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('admin.dashboard')}}"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 

     
      <?php /*<li class="nav-item">
        <a class="nav-link has-icon " href="{{route('admin.employee')}}"><i class="material-icons">apartment</i>Company</a>
      </li>*/ ?> 
     @endif
      @if(empCan('view_associate') || Auth::user()->login_type == 'superadmin' )
      <li class="nav-item {{ (Request::route()->getName() == 'admin.associate') ? ' active' : '' }}">
        <a class="nav-link has-icon" href="{{route('admin.associate')}}"><i class="material-icons">person </i>Associates</a>
      </li>
      @endif
      @if(Auth::user()->login_type == 'superadmin')
      <li class="nav-item {{ (Request::route()->getName() == 'admin.employee') ? ' active' : '' }}">
        <a class="nav-link has-icon" href="{{route('admin.employee')}}"><i class="material-icons">person</i>Employee</a>
      </li>
    @endif
   @if(empCan('view_customer') || Auth::user()->login_type == 'superadmin' )
    <li class="nav-item {{ (Request::route()->getName() == 'admin.instructions') || (Request::route()->getName() == 'admin.acustomer') ? ' active' : '' }}">
        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">people</i>Customers</a>
        <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.acustomer') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.acustomer')}}">Customer</a>
        </li>
        <?php /*<li class="nav-item {{ (Request::route()->getName() == 'admin.customer_investments') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.customer_investments')}}" >Investments</a>
        </li>*/?> 
        </ul>
    </li>
  @endif



       
      @if(Auth::user()->login_type == 'superadmin' ) 
    <li class="nav-item {{ (Request::route()->getName() == 'admin.instructions') || (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">account_balance</i>Payout</a>
        <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.payout')}}">Generate Payout</a>
        </li> 
        </ul>
    </li>
  @endif
  @if(empcan('view_fund_management') || Auth::user()->login_type == 'superadmin' )
    <li class="nav-item {{ (Request::route()->getName() == 'admin.instructions') || (Request::route()->getName() == 'admin.customerWiseInvestReport') || (Request::route()->getName() == 'admin.associatewisecustomer') || (Request::route()->getName() == 'admin.customerWiseMonthlyReport') || (Request::route()->getName() == 'admin.transactions') ? ' active' : '' }}">
      <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">account_balance</i>Fund Management</a>
      <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.customerWiseInvestReport') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customerWiseInvestReport')}}">Customerwise Investment Report</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.associatewisecustomer') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associatewisecustomer')}}">Associate Wise Customer Report</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.customerWiseMonthlyReport') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customerWiseMonthlyReport')}}">Customer Wise Monthly Report</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.transactions') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.transactions')}}">All Transactions Report</a>
        </li>
        <!-- <li class="nav-item {{ (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.payout')}}" title="Customer Intrest Percent(%) Report "><i class="material-icons">account_balance_wallet</i>Customer Intrest Percent(%) Report </a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.payout')}}" title="Customer Cancel Investment Report "><i class="material-icons">account_balance_wallet</i>Customer Cancel Investment Report </a>
        </li> --> 
      </ul>
    </li>
     @endif

  @if(Auth::user()->login_type == 'superadmin' )
    <li class="nav-item {{ (Request::route()->getName() == 'admin.company_bank') ? ' active' : '' }}">
      <a class="nav-link has-icon " href="{{route('admin.company_bank')}}"><i class="material-icons">dashboard</i>Company Bank</a>
    </li>
 @endif   
     @if(empcan('view_master') || Auth::user()->login_type == 'superadmin' )
      <li class="nav-item {{ (Request::route()->getName() == 'admin.master') || (Request::route()->getName() == 'admin.country') || (Request::route()->getName() == 'admin.state') || (Request::route()->getName() == 'admin.city') || (Request::route()->getName() == 'admin.zipcode') ? ' active' : '' }}">
      <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">pie_chart</i>Master</a>
      <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.country') ? ' active' : '' }}"><a href="{{route('admin.country')}}">Country</a></li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.state') ? ' active' : '' }}"><a href="{{route('admin.state')}}">State</a></li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.city') ? ' active' : '' }}"><a href="{{route('admin.city')}}">City</a></li>
        
        <li class="nav-item {{ (Request::route()->getName() == 'admin.zipcode') ? ' active' : '' }}"><a href="{{route('admin.zipcode')}}">Zipcode</a></li>

      </ul>
    </li>
  </li>
    
     


      


      

      

      @endif
      @if(Auth::user()->login_type == 'employee')
     <li class="nav-label">DASHBOARD</li>
      <li class="nav-item {{ (Request::route()->getName() == 'customer.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('employee.dashboard')}}" title="Dashboard"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 
      <li class="nav-label">Employee</li>
        <li class="nav-item {{ (Request::route()->getName() == 'employee.employee_profile') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('employee.employee_profile')}}" title="employee"><i class="material-icons">person</i>Profile</a>
        </li> 
      </li>
      @endif
      @if(Auth::user()->login_type == 'customer')
      <li class="nav-label">DASHBOARD</li>
      <li class="nav-item {{ (Request::route()->getName() == 'customer.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('customer.dashboard')}}" title="Dashboard"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 
      <li class="nav-label">Customers</li>
        <li class="nav-item {{ (Request::route()->getName() == 'customer.customer_profile') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('customer.customer_profile')}}" title="Customer"><i class="material-icons">person</i>Profile</a>
        </li> 
        <li class="nav-item {{ (Request::route()->getName() == 'customer.customer_transaction') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{url('customer/customer-transaction-history')}}" title="Customer"><i class="material-icons">history</i>Transaction History</a>
        </li>
         <li class="nav-item {{ (Request::route()->getName() == 'customer.my_investment') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{url('customer/customer-my-investment')}}" title="Customer"><i class="material-icons">account_balance</i>My Investment</a>
        </li>
      @endif
      @if(Auth::user()->login_type == 'associate')
       <li class="nav-label">DASHBOARD</li>
      <li class="nav-item {{ (Request::route()->getName() == 'associate.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('associate.dashboard')}}" title="Dashboard"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 
      <li class="nav-label">Associates</li>
    <li class="nav-item {{ (Request::route()->getName() == 'associate.associate_profile') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('associate.associate_profile')}}" title="Customer"><i class="material-icons">person</i>Profile</a>
        </li> 
        <li class="nav-item {{ (Request::route()->getName() == 'associate.my_commission') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('associate.my_commission')}}" title="Customer"><i class="material-icons">account_balance</i>My Commission</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'associate.my_customer') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('associate.my_customer')}}" title="Customer"><i class="material-icons">people</i>Customers</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'associate.my_withdraw') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('associate.my_withdraw')}}" title="Customer"><i class="material-icons">history</i>Withdraw History</a>
        </li>
      @endif

      @if(Auth::user()->login_type == 'superadmin')
        <li class="nav-item {{ (Request::route()->getName() == 'backup') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.backup')}}"><i class="material-icons">backup</i>Backup</a>
        </li>
      @endif

      <li class="nav-item {{ (Request::route()->getName() == 'customerlogout') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('customerlogout')}}"><i class="material-icons">add_to_home_screen</i>Logout</a>
      </li>
      
    </ul>
  </div>
  <!-- /Sidebar body -->

</div>
<!-- /Sidebar -->