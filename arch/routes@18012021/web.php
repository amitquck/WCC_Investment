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
		Route::get('customerHoldStatus/{id}','CustomerController@hold_status');
		Route::post('customerDelete','CustomerController@destroy')->name('customerDelete');

		Route::post('customerDeleteConfirmation','CustomerController@destroy_confirmation')->name('delete_confirm');

		Route::get('Customer-Security-Cheque','CustomerController@security_cheque')->name('security_cheque');


		Route::get('customer/commission/associate/autocomplete','CustomerController@searchAssociateCommission')->name('search_associate_commission');
		Route::get('getCustomerInterest','ReportController@autocompleteAssociateCustomer')->name('associatecustomer');
		// Route::get('customer_invest_search','CustomerController@searchCustomerInvest')->name('customer_invest_search');
		Route::get('customer/customerAllTransactions/{id}','CustomerController@customer_interest_invest')->name('customerAllTransactions');


		Route::get('associatecustomer','ReportController@getPageAssociateWiseCustomer')    
        ->name('associatecus');

        Route::get('customer_investments','CustomerController@customer_investments') ->name('customer_investments');

		
		Route::get('customer-wise-monthly-report','ReportController@customerWiseMonthlyReport')->name('customerWiseMonthlyReport');
		

		Route::get('company_bank','CompanyBankController@index')->name('company_bank');
		Route::post('addBank','CompanyBankController@store')->name('addBank');
		Route::get('perticularBankTransaction/{id}','CompanyBankController@perBankReport')->name('perticularBankTransaction');
		Route::get('editBank/{id}','CompanyBankController@edit')->name('editBank');
		Route::post('updateBank/{id}','CompanyBankController@update')->name('updateBank');




		Route::get('customerWiseInvestReport','ReportController@investment')->name('customerWiseInvestReport');

		Route::get('excel_customer_investments','ReportController@excelExportCustomerInvestments')->name('excel_customer_investments');

		Route::get('Associate-Wise-Customer','ReportController@getPageAssociateWiseCustomer')->name('associatewisecustomer');

		Route::get('Excel-Associate-Wise-Customer','ReportController@excel_associatewise_customer')->name('excel_associatewise_customer');

		Route::get('search-associate-customer','ReportController@getPageAssociateWiseCustomer')->name('search_associate_customer');

		Route::get('custInvestmentDetails/{id}','ReportController@viewInvestmentDetail')->name('custInvestmentDetails');

		Route::get('searchinvestreport','ReportController@investment')->name('searchinvestreport');

		Route::get('transactions','ReportController@transactions')->name('transactions');

		Route::get('Excel-Customer-Wise-Monthly-Report','ReportController@excel_customer_wise_monthly_report')->name('excel_customer_wise_monthly_report');

		Route::get('Excel-All-Transaction-Report','ReportController@excel_all_transactions')->name('excel_all_transactions');
		


		Route::post('editInterestForm','CustomerController@editInterestForm')->name('editInterestForm');
		Route::post('editInterest','CustomerController@editInterest')->name('editInterest');
		Route::get('customerInterestDelete/{id}','CustomerController@customerinterestdestroy')->name('customerInterestDelete');

		Route::post('editAssociateWithdrawlForm','CustomerController@editAssociateWithdrawlForm')->name('editAssociateWithdrawlForm');
		Route::post('editCommission','CustomerController@editCommission')->name('editCommission');
		Route::get('associateCommissionDelete/{id}','CustomerController@associateCommissionDestroy')->name('associateCommissionDelete');


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
			Route::post('addZipcode','ZipcodeController@store')->name('a');
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


