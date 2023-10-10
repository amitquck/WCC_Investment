<?php 
 
use Illuminate\Support\Facades\Route;

Route::get('/remove-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
    dd('cache cleared');
});
Route::get('/','WebsiteController@index')->name('home');
Route::get('dashboard','CustomerController@dashboard')->name('dashboard');
Route::get('loginCustomer','CustomerController@login')->name('loginCustomer');
Route::get('registerCustomer','CustomerController@registration')->name('registerCustomer');
Route::post('customer-login','CustomerController@customerLogin')->name('customerLogin');
Route::post('customerRegister','WebsiteController@register')->name('customerRegister');

Route::post('login,LoginController@adminlogin')->name('login');



Route::get('changePassword','CustomerController@changePassword')->name('changePassword');
Route::get('logout','CustomerController@customerLogout')->name('customerlogout');

Route::get('/',function(){
    return view('auth.login');
});
Auth::routes();
// Route For Admin
Route::group(['prefix' => 'auth','as'=>'auth.','middleware' => 'auth'],function(){
	Route::namespace('Auth')->group(function () {
		Route::get('changePassword','ResetPasswordController@changePassword')->name('changePassword');
	});
});
Route::group(['prefix' => 'admin','as'=>'admin.','middleware' => 'auth'],function(){
	 
	Route::namespace('Admin')->group(function () {

		Route::get('/backup', 'DashboardController@backup')->name('backup')->middleware('is_superadmin');

		Route::post('updatePassword','ResetPasswordController@updatePassword')->name('updatePassword');
		Route::get('dashboard','DashboardController@index')->name('dashboard');
		Route::post('customerDepositForm','CustomerController@customerDepositForm')->name('customerDepositForm');
		Route::post('customerDeposit','CustomerController@customerDeposit')->name('customerDeposit');
		
		Route::post('customerWithdrawForm','CustomerController@customerWithdrawForm')->name('customerWithdrawForm');
		Route::post('customerWithdraw','CustomerController@customerWithdraw')->name('customerWithdraw');
		
		Route::get('associate','AssociateController@index')->name('associate');
		Route::get('addassociate','AssociateController@addform')->name('addassociate');
		Route::post('associate_store','AssociateController@store')->name('associate_store');
		Route::post('associateAddTransationForm','AssociateController@associateAddTransationForm')->name('associateAddTransationForm');
		Route::post('associate/addTransaction','AssociateController@associateAddTransaction')->name('associateAddTransaction');
		Route::get('associate_view/{id}','AssociateController@view')->name('associate_view');

		Route::get('associate_edit/{id}','AssociateController@edit')->name('associate_edit');

		Route::post('associate_update/{id}','AssociateController@update')->name('associate_update');

		Route::post('associateDelete','AssociateController@destroy')->name('associateDelete');
		Route::post('associateDeleteConfirmation','AssociateController@destroy_confirmation')->name('associate_delete_confirm');

		Route::get('associateStatus/{id}','AssociateController@status');

		Route::get('getAssociate','AssociateController@autocomplete')->name('getAssociate');
		Route::get('getAssociateAccount','AssociateController@autocompleteAssAccount')->name('getAssociateAccount');
		
		Route::get('associate_search','AssociateController@index')->name('associate_search');
		Route::get('associateAllTransactions/{id}','AssociateController@associatetransaction')->name('associateAllTransactions');
		// Route::get('associate_search?id={id}','AssociateController@searchAssociate')->name('associate_search');
		

		Route::get('customers','CustomerController@index')->name('acustomer');
		Route::get('customer_search','CustomerController@index')->name('customer_search');
		Route::get('customer-detail-view/{id}','CustomerController@view')->name('customer_detail');
		Route::post('getzip','CustomerController@getZip')->name('getzip');
		Route::get('addCustomer','CustomerController@add')->name('addCustomer');
		Route::post('store','CustomerController@store')->name('cstore');

		Route::get('customer/commission/{id}', 'CustomerController@customerCommission')    
        ->name('customer_commission');

        Route::get('customer/associate/commission/{id}', 'CustomerController@customerAssociateCommission')    
        ->name('customer_associate_commission');

		Route::post('customer/addInvestment/{id}', 'CustomerController@investmentStore')->name('add_customer_associate_percent');

        Route::post('customer/commissionstore/{id}', 'CustomerController@commissionStore')
        ->name('commissionStore');
        Route::get('deleteCustomerDeposit/{id}','CustomerController@deleteCustomerDeposit')->name('deleteCustomerDeposit');

		Route::get('getCustomers','CustomerController@getCustomers')->name('getCustomers');

		Route::get('customer-edit/{id}','CustomerController@edit');
		Route::post('customerUpdate','CustomerController@update')->name('customerUpdate');
		Route::get('customerStatus/{id}','CustomerController@status');

		Route::post('customerHoldStatus','CustomerController@hold_status')->name('hold_status');

		Route::post('customer-hold-remarks','CustomerController@hold_remarksForm')->name('hold_remarksForm');


		Route::post('customerDelete','CustomerController@destroy')->name('customerDelete');
//hold_remarksForm
		Route::post('customerDeleteConfirmation','CustomerController@destroy_confirmation')->name('delete_confirm');

		Route::post('customerDeleteDepositWithdrawForm','CustomerController@delete_deposit_withdraw')->name('delete_deposit_withdraw');

		Route::post('customerDeleteDepositWithdraw','CustomerController@customer_delete_deposit_withdraw')->name('customerDeleteDepositWithdraw');

		Route::get('Customer-Security-Cheque','CustomerController@security_cheque')->name('security_cheque');

		Route::get('Customer-on-hold','CustomerController@on_hold')->name('on_hold');

		Route::get('Security-Cheque-Alert','CustomerController@security_cheque_alert')->name('security_cheque_alert');


		Route::post('Customer-deposit-edit-form','CustomerController@edit_depositForm')->name('edit_depositForm');
		Route::post('Customer-deposit-edit','CustomerController@edit_deposit')->name('edit_deposit');

		Route::post('Customer-withdraw-edit-form','CustomerController@edit_withdrawForm')->name('edit_withdrawForm');
		Route::post('Customer-withdraw-edit','CustomerController@edit_withdraw')->name('edit_withdraw');

		Route::get('customer-confirmation-payout/{month}/{year}','CustomerController@confirmationPayout')->name('confirmation_gen_payouts');

		Route::get('customer-confirm-payout/{month}/{year}','CustomerController@confirmPayout')->name('confirm_payout');


		Route::get('customer/commission/associate/autocomplete','CustomerController@searchAssociateCommission')->name('search_associate_commission');
		Route::get('getCustomerInterest','ReportController@autocompleteAssociateCustomer')->name('associatecustomer');
		// Route::get('customer_invest_search','CustomerController@searchCustomerInvest')->name('customer_invest_search');
		Route::get('customer/customerAllTransactions/{id}','CustomerController@customer_interest_invest')->name('customerAllTransactions');

        Route::get('customer_investments','CustomerController@customer_investments') ->name('customer_investments');

        Route::get('customer-bulk-investments-form','CustomerController@deposit_in_bulkform') ->name('deposit_in_bulkform');
        Route::post('customer-bulk-transactions','CustomerController@bulk_submit_transactions') ->name('bulk_submit_transactions');

        Route::get('customer-activitylog/{id}','CustomerController@customer_activitylog') ->name('customer_activitylog');

        
		


		Route::get('associatecustomer','ReportController@getPageAssociateWiseCustomer')    
        ->name('associatecus');

		Route::get('company_bank','CompanyBankController@index')->name('company_bank');
		Route::post('addBank','CompanyBankController@store')->name('addBank');
		Route::get('perticularBankTransaction/{id}','CompanyBankController@perBankReport')->name('perticularBankTransaction');
		Route::get('editBank/{id}','CompanyBankController@edit')->name('editBank');
		Route::post('updateBank/{id}','CompanyBankController@update')->name('updateBank');

		Route::get('Bank-Transactions','CompanyBankController@excel_bank_transactions')->name('excel_bank_transactions');
		
		Route::get('Associate-Wise-Customer-Data','ReportController@associateWiseCustomerData')->name('customerMonthlyReportRedirect');
		
		Route::get('customermonthlyreport','ReportController@customermonthlyreport')->name('customermonthlyreport');
		
		Route::get('customer-wise-monthly-report','ReportController@customerWiseMonthlyReport')->name('customerWiseMonthlyReport');

		Route::get('customerWiseInvestReport','ReportController@investment')->name('customerWiseInvestReport');

		Route::get('excel_customer_investments','ReportController@excelExportCustomerInvestments')->name('excel_customer_investments');

		Route::get('Associate-Wise-Customer','ReportController@getPageAssociateWiseCustomer')->name('associatewisecustomer');

		Route::get('Excel-Associate-Wise-Customer','ReportController@excel_associatewise_customer')->name('excel_associatewise_customer');

		Route::get('search-associate-customer','ReportController@getPageAssociateWiseCustomer')->name('search_associate_customer');

		Route::get('custInvestmentDetails/{id}','ReportController@viewInvestmentDetail')->name('custInvestmentDetails');

		Route::get('searchinvestreport','ReportController@investment')->name('searchinvestreport');

		Route::get('transactions','ReportController@transactions')->name('transactions');

		Route::get('Monthly-Payout-Report','ReportController@monthly_payout')->name('monthly_payout');

		Route::get('Excel-Customer-Wise-Monthly-Report','ReportController@excel_customer_wise_monthly_report')->name('excel_customer_wise_monthly_report');

		Route::get('Excel-All-Transaction-Report','ReportController@excel_all_transactions')->name('excel_all_transactions');

		Route::get('Excel-Monthly-Payout-Report','ReportController@excel_monthly_payout')->name('excel_monthly_payout');
		
		Route::get('Excel-Customer-Transaction-Report','ReportController@excel_customer_transactions')->name('excel_customer_transactions');

		Route::get('business_category','ReportController@business_category')->name('business_category');
		Route::get('Customer-Transactions','ReportController@customer_transactions')->name('customer_transactions');

		Route::get('Associate-Balance','ReportController@associate_balance')->name('associate_balance');
		Route::get('Customer-Debitor-Creditor-List','ReportController@debitor_creditor_list')->name('debitor_creditor_list');

		Route::get('Excel-Credit-Debit-Report','ReportController@excel_debitor_creditor_list')->name('excel_debitor_creditor_list');

		Route::get('Excel-Associate-Percent-Wise-Balance','ReportController@excel_associate_per_balance')->name('excel_associate_per_balance');


		Route::post('editInterestForm','CustomerController@editInterestForm')->name('editInterestForm');
		Route::post('editInterest','CustomerController@editInterest')->name('editInterest');
		Route::get('customerInterestDelete/{id}','CustomerController@customerinterestdestroy')->name('customerInterestDelete');

		Route::post('editAssociateWithdrawlForm','CustomerController@editAssociateWithdrawlForm')->name('editAssociateWithdrawlForm');
		Route::post('editCommission','CustomerController@editCommission')->name('editCommission');
		Route::post('associateCommissionDeleteForm','CustomerController@associateCommissionDestroyForm')->name('associateCommissionDeleteForm');
		Route::post('associateCommissionDelete','CustomerController@associateCommissionDestroy')->name('associateCommissionDelete');

		Route::post('update-Commission-Form','CustomerController@updateCommissionForm')->name('updateCommissionForm');

		Route::post('update-Commission','CustomerController@updateCommission')->name('updateCommission');



		Route::post('updateInterestRateForm','CustomerController@updateInterestRateForm')->name('updateInterestRateForm');
		Route::post('updateInterestRate','CustomerController@updateInterestRate')->name('updateInterestRate');

		Route::get('employee','EmployeeController@index')->name('employee');
		Route::get('privilege/{id}','EmployeeController@privilege')->name('privilege');
		Route::post('apply_privilege/{id}','EmployeeController@apply_privilege')->name('apply_privilege');


		


		Route::get('countries','CountryController@index')->name('country');
		Route::post('StoreCountry','CountryController@countrystore')->name('country_data_store');
		Route::get('countrystatus/{id}','CountryController@status');
		Route::get('country_delete/{id}','CountryController@destroy')->name('country_delete');
		Route::group(['prefix' => 'country','as'=>'country.'],function(){
			Route::post('edit','CountryController@edit')->name('edit');
			Route::post('update','CountryController@update')->name('update');
		});


		Route::get('customer/generate-payout','CustomerController@generatePayout')->name('payout');
		Route::post('customer/payout-generates','CustomerController@payoutGenerates')->name('payoutgenerate');

		Route::get('customer/generated-payouts','CustomerController@generatedPayouts')->name('generated_payouts');

		Route::get('customer/delete-generated-payouts/{month}/{year}','CustomerController@delete_gen_payouts')->name('delete_gen_payouts');



		Route::post('addState','StateController@store')->name('store');
		Route::get('states','StateController@index')->name('state');
		Route::get('stateStatus/{id}','StateController@status');
		Route::post('editstate','StateController@edit')->name('editstate');
		Route::post('stateupdate','StateController@update')->name('stateupdate');
		Route::get('stateDelete/{id}','StateController@destroy')->name('stateDelete');

		// Route Start for City
		Route::group(['prefix' => 'city'],function(){
			Route::get('cities','CityController@index')->name('city');
			Route::post('addCities','CityController@store')->name('citystore');
			Route::post('editcity','CityController@edit')->name('editcity');
			Route::post('update','CityController@update')->name('update');
			Route::post('getcountry','CityController@getcountry')->name('getcountry');
			Route::get('cityStatus/{id}','CityController@status');
			Route::get('cityDelete/{id}','CityController@destroy')->name('cityDelete');
		});

		Route::group(['prefix' => 'zipcode'],function(){
			Route::get('zipcodes','ZipcodeController@index')->name('zipcode');
			Route::post('addZipcode','ZipcodeController@store')->name('zipstore');
			Route::post('editzipcode','ZipcodeController@edit')->name('editzipcode');
			Route::post('zipcodeupdate','ZipcodeController@update')->name('zipcodeupdate');
			Route::post('getCountry','ZipcodeController@getCountry')->name('getCountry');
			Route::post('getCountryState','ZipcodeController@getCountryState')->name('getCountryState');
			Route::post('getStateCity','ZipcodeController@getStateCity')->name('getStateCity');
			Route::get('zipcodestatus/{id}','ZipcodeController@status');
			Route::get('zipcodeDelete/{id}','ZipcodeController@destroy')->name('zipcodeDelete');
		});

		Route::group(['prefix' => 'product'],function(){
			Route::get('product','ProductController@index')->name('product');
			Route::post('addProduct','ProductController@store')->name('addProduct');
			Route::post('editProduct','ProductController@edit')->name('editProduct');
			Route::post('productUpdate','ProductController@update')->name('productUpdate');
			Route::get('productStatus/{id}','ProductController@status');
			Route::delete('productDelete/{id}','ProductController@destroy')->name('productDelete');
		});
		Route::group(['prefix' => 'employee'],function(){
			Route::get('employee','EmployeeController@index')->name('employee');
			Route::get('addEmployeepage','EmployeeController@addEmployeePage')->name('addEmployeepage');
			Route::post('addEmployee','EmployeeController@store')->name('addEmployee');
			Route::get('editemployee/{id}','EmployeeController@edit')->name('editemployee');
			Route::post('employeeUpdate','EmployeeController@employeeUpdate')->name('employeeUpdate');
			Route::get('employeestatus/{id}','EmployeeController@status');
			Route::get('employeeDelete/{id}','EmployeeController@destroy')->name('employeeDelete');;
		});
		
	});

	


	Route::group(['prefix' => 'payments'],function(){
			Route::get('payments','Admin\PaymentController@index')->name('payment');

		});
	Route::get('/download', 'DownloadsController@download');
});
Route::group(['prefix' => 'associate','as'=>'associate.','middleware' => 'is_associate'],function(){
	Route::namespace('Admin')->group(function () {
		Route::get('dashboard','DashboardController@index')->name('dashboard');
		Route::get('Profile','AssociateController@profile')->name('associate_profile');
		Route::get('associate-change-password','AssociateController@changePassword');
		Route::post('change-password-s','AssociateController@changePasswordS');
		Route::get('my-Commission','AssociateController@myCommission')->name('my_commission');
		Route::get('my-Customer','AssociateController@myCustomer')->name('my_customer');
		Route::get('my-Withdraw','AssociateController@myWithdraw')->name('my_withdraw');
	});
	
});
Route::group(['prefix' => 'customer','as'=>'customer.','middleware' => 'is_customer'],function(){
	Route::namespace('Admin')->group(function () {
		Route::get('dashboard','DashboardController@index')->name('dashboard');
		Route::get('Profile','CustomerController@profile')->name('customer_profile');
		Route::get('Customer-change-password','CustomerController@changePassword');
		Route::post('change-password-s','CustomerController@changePasswordS');
		Route::get('customer-transaction-history','CustomerController@transactionHistory')->name('customer_transaction');
		Route::get('customer-my-investment','CustomerController@viewMyInvestment')->name('my_investment');
		Route::get('customer-investment-transaction/{id}','CustomerController@viewInvestmentTransaction')->name('customer_investment_transaction');

		
	});
});
Route::group(['prefix' => 'employee','as'=>'employee.','middleware' => 'is_employee'],function(){
	Route::namespace('Admin')->group(function () {
		Route::get('dashboard','DashboardController@index')->name('dashboard');
		Route::get('Profile','EmployeeController@profile')->name('employee_profile');
		Route::get('Customer-change-password','CustomerController@changePassword');
		Route::post('change-password-s','CustomerController@changePasswordS');
		Route::get('customer-transaction-history','CustomerController@transactionHistory')->name('customer_transaction');
		Route::get('customer-my-investment','CustomerController@viewMyInvestment')->name('my_investment');
		Route::get('customer-investment-transaction/{id}','CustomerController@viewInvestmentTransaction')->name('customer_investment_transaction');

		
	});
});


