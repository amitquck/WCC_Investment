<?php 
 ?>
<!-- Sidebar -->
<div class="sidebar">

  <!-- Sidebar header -->
  <div class="sidebar-header">
    <a href="{{route('admin.dashboard')}}" class=""><!-- logo -->
      <img src="{{ asset('img/logo.png') }}" alt="Quick Infotech" id="main-logo" width="150px;" height="50px;">
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
    <li>&nbsp;</li>
      <li class="nav-item {{ (Request::route()->getName() == 'admin.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('admin.dashboard')}}"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 

     
      <?php /*<li class="nav-item">
        <a class="nav-link has-icon " href="{{route('admin.employee')}}"><i class="material-icons">apartment</i>Company</a>
      </li>*/ ?> 
     @endif
      @if(Auth::user()->login_type == 'superadmin' )<!-- empCan('view_associate') ||  -->
      <li class="nav-item {{ (Request::route()->getName() == 'admin.associate') ? ' active' : '' }}">
        <a class="nav-link has-icon" href="{{route('admin.associate')}}"><i class="material-icons">person </i>Associates</a>
      </li>
      @endif
      @if(Auth::user()->login_type == 'superadmin')
      <li class="nav-item {{ (Request::route()->getName() == 'admin.employee') ? ' active' : '' }}">
        <a class="nav-link has-icon" href="{{route('admin.employee')}}"><i class="material-icons">person</i>Employee</a>
      </li>
    @endif
   @if(Auth::user()->login_type == 'superadmin' )<!-- empCan('view_customer') ||  -->
    <li class="nav-item {{ (Request::route()->getName() == 'admin.instructions') || (Request::route()->getName() == 'admin.acustomer') || (Request::route()->getName() == 'admin.security_cheque') || (Request::route()->getName() == 'admin.on_hold') ? ' active' : '' }}">
        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">people</i>Customers</a>
        <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.acustomer') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.acustomer')}}">Customer</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.security_cheque') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.security_cheque')}}">PDC Issued</a>
        </li>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.on_hold') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.on_hold')}}">On Hold</a>
        </li>
        <?php /*<li class="nav-item {{ (Request::route()->getName() == 'admin.customer_investments') ? ' active' : '' }}">
          <a class="nav-link has-icon" href="{{route('admin.customer_investments')}}" >Investments</a>
        </li>*/?> 
        </ul>
    </li>
  @endif

      @if(Auth::user()->login_type == 'superadmin')

      <li class="nav-item {{ (Request::route()->getName() == 'admin.deposit_in_bulkform') || (Request::route()->getName() == 'admin.edit_bulktxn') ? ' active' : '' }}">
        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">₹</i> &nbsp;&nbsp;&nbsp;Bulk Transactions</a>
        <ul>
          <li class="nav-item {{ (Request::route()->getName() == 'admin.deposit_in_bulkform') ? ' active' : '' }}">
            <a class="nav-link has-icon" href="{{route('admin.deposit_in_bulkform')}}">Add Bulk Transactions</a>
          </li>
          <li class="nav-item {{ (Request::route()->getName() == 'admin.edit_bulktxn') ? ' active' : '' }}">
            <a class="nav-link has-icon" href="{{route('admin.edit_bulktxn')}}">Edit Bulk Transactions</a>
          </li>
        </ul>
      </li>
    @endif

  <?php
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d',strtotime($start_date.'+7 day'));
    // dd($end_date);
    $count = count(DB::select('select cheque_maturity_date from customer_security_cheques where cheque_maturity_date between "'.$start_date.'" and "'.$end_date.'" and cheque_issue_date is not null and deleted_at is null'));

  ?>

    @if(Auth::user()->login_type == 'superadmin')
      <li class="nav-item {{ (Request::route()->getName() == 'admin.security_cheque_alert') ? ' active' : '' }}">
        <a class="nav-link has-icon" href="{{route('admin.security_cheque_alert')}}"><i class="material-icons">security</i>PDC Issued <sup class="badge badge-warning">{{$count}}</sup></a>
      </li>
    @endif

       
      @if(Auth::user()->login_type == 'superadmin' ) 
    <li class="nav-item {{ (Request::route()->getName() == 'admin.generated_payouts') || (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
        <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">account_balance</i>Payout</a>
        <ul>
        <li class="nav-item {{ (Request::route()->getName() == 'admin.payout') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.payout')}}">Generate Payout</a>
        </li> 
        <li class="nav-item {{ (Request::route()->getName() == 'admin.generated_payouts') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.generated_payouts')}}">Generated Payouts</a>
        </li> 
        </ul>
    </li>
  @endif
  @if(Auth::user()->login_type == 'superadmin' )<!-- empcan('view_fund_management') ||  -->
    <li class="nav-item {{ (Request::route()->getName() == 'admin.instructions') || (Request::route()->getName() == 'admin.customerWiseInvestReport') || (Request::route()->getName() == 'admin.associatewisecustomer') || (Request::route()->getName() == 'admin.customerWiseMonthlyReport')|| (Request::route()->getName() == 'admin.customermonthlyreport') || (Request::route()->getName() == 'admin.transactions') || (Request::route()->getName() == 'admin.customer_transactions') || (Request::route()->getName() == 'admin.associate_balance') || (Request::route()->getName() == 'admin.monthly_payout') || (Request::route()->getName() == 'admin.debitor_creditor_list') || (Request::route()->getName() == 'admin.associate_payment_list') || (Request::route()->getName() == 'admin.associate_ladger_balance') || (Request::route()->getName() == 'admin.associate_commission_list') ? ' active' : '' }}">
      <a class="nav-link has-icon dropdown-toggle" href="#"><i class="material-icons">account_balance</i>Fund Management</a>
      <ul>
        <!-- <li class="nav-item {{ (Request::route()->getName() == 'admin.customerWiseInvestReport') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customerWiseInvestReport')}}">Customerwise Investment Report</a>
        </li> -->

        <li class="nav-item {{ (Request::route()->getName() == 'admin.associatewisecustomer') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associatewisecustomer')}}">Associate Wise Customer Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.associate_payment_list') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associate_payment_list')}}">Associate Payment Report</a>
        </li>

        <!-- <li class="nav-item {{ (Request::route()->getName() == 'admin.customermonthlyreport') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customermonthlyreport')}}">Customer Wise Monthly Report</a>
        </li> -->
        
        <!-- <li class="nav-item {{ (Request::route()->getName() == 'admin.customerWiseMonthlyReport') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customerWiseMonthlyReport')}}">Customer Wise Monthly Payout Report</a>
        </li> --><?php /* 17/02/2021 */ ?>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.transactions') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.transactions')}}">All Transactions Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.associate_ladger_balance') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associate_ladger_balance')}}">Associate Ladger Balance Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.associate_commission_list') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associate_commission_list')}}">Associate Commission Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.customer_transactions') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.customer_transactions')}}">Customer Ladger Balance Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.monthly_payout') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.monthly_payout')}}">Monthly Payout Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.debitor_creditor_list') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.debitor_creditor_list')}}">Customer Debitor/Creditor Report</a>
        </li>

        <li class="nav-item {{ (Request::route()->getName() == 'admin.associate_balance') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.associate_balance')}}">Associate % Wise Balance</a>
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
     @if(Auth::user()->login_type == 'superadmin' )<!-- empcan('view_master') ||  -->
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
    @if(Auth::user()->login_type == 'superadmin')
      <li class="nav-item {{ (Request::route()->getName() == 'admin.activity_log') ? ' active' : '' }}">
      <a class="nav-link has-icon " href="{{route('admin.activity_log')}}"><i class="material-icons">local_activity</i>Activity Log</a>
    </li>
    @endif


      @if(Auth::user()->login_type == 'employee')
      <li class="nav-item {{ (Request::route()->getName() == 'customer.dashboard') ? ' active' : '' }}">
        <a class="nav-link has-icon " href="{{route('employee.dashboard')}}" title="Dashboard"><i class="material-icons">dashboard</i>Dashboard</a>
      </li> 
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
        <li class="nav-item {{ (Request::route()->getName() == 'entry_lock') ? ' active' : '' }}">
          <a class="nav-link has-icon " href="{{route('admin.entry_lock')}}"><i class="material-icons">lock</i>Entry Lock</a>
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