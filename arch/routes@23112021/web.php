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

        Route::get('Associate-Excel-Export','AssociateController@associates_excel_export')->name('all_associate_list_excel_export');

        Route::get('Associate-Marked-In-Customer','AssociateController@customer_marked_in_associate')->name('customer_marked_in_associate');



        Route::get('Customer-Wise-Associate-Commission/{id}/{month}/{year}','AssociateController@customer_month_wise_associate_comm')->name('customer_month_wise_associate_comm');

        Route::get('Customer-Wise-Associate-Commission/{id}','AssociateController@customer_wise_associate_comm')->name('customer_wise_associate_comm');

        Route::get('Excel-Customer-Wise-Associate-Commission/{id}','AssociateController@excel_customer_wise_associate_comm')->name('excel_customer_wise_associate_comm');

		Route::get('Associate-Add-Bulk-Transaction','AssociateController@deposit_in_bulkform') ->name('asso_deposit_in_bulkform');

		Route::get('Get-Associate','AssociateController@getAssociate')->name('getAssociate');
		Route::post('Associate-Addbulk-transactions','AssociateController@asso_bulk_submit_transactions')->name('asso_bulk_submit_transactions');
		Route::get('Asso-Edit-BulkTxn-form','AssociateController@asso_edit_bulktxn')->name('asso_edit_bulktxn');
		Route::post('Associate-Update-Bulk-Txn','AssociateController@asso_update_bulk_txn')->name('asso_update_bulk_txn');
		Route::post('Associate-Delete-Form','AssociateController@singleAssoDeleteBulkTxnForm')->name('singleAssoDeleteBulkTxnForm');
		Route::post('Associate-Delete-Withdraw','AssociateController@singleAssoDeleteBulkTxn')->name('singleAssoDeleteBulkTxn');
		Route::get('Associate-Delete-All-Bulk-Txn','AssociateController@asso_delete_all_bulktxn')->name('asso_delete_all_bulktxn');

		Route::post('readaddress','AssociateController@readaddress')->name('readaddress');

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

        Route::post('getAssociateCommRate', 'CustomerController@getCommissionRate')->name('associateCommRate');


		Route::post('customer/addInvestment/{id}', 'CustomerController@investmentStore')->name('add_customer_associate_percent');

        Route::post('customer/commissionstore/{id}', 'CustomerController@commissionStore')
        ->name('commissionStore');
        Route::get('deleteCustomerDeposit/{id}','CustomerController@deleteCustomerDeposit')->name('deleteCustomerDeposit');

		Route::get('getCustomers','CustomerController@getCustomers')->name('getCustomers');
		Route::get('getCustomersForBulk','CustomerController@getCustomersForBulk')->name('getCustomersForBulk');

		Route::get('customer-edit/{id}','CustomerController@edit');
		Route::post('customerUpdate','CustomerController@update')->name('customerUpdate');
		Route::get('customerStatus/{id}','CustomerController@status');

		Route::post('customerHoldStatus','CustomerController@hold_status')->name('hold_status');

		Route::post('customer-hold-remarks','CustomerController@hold_remarksForm')->name('hold_remarksForm');

		Route::get('Hold-Customer-Excel-Export','CustomerController@hold_customer_excel_export')->name('hold_customer_excel_export');

		Route::get('Customer-PDC-Issue-Excel-Export','CustomerController@customer_pdc_issue_excel_export')->name('customer_pdc_issue_excel_export');

		Route::post('customerDelete','CustomerController@destroy')->name('customerDelete');
        //hold_remarksForm
		Route::post('customerDeleteConfirmation','CustomerController@destroy_confirmation')->name('delete_confirm');

		Route::post('customerDeleteDepositWithdrawForm','CustomerController@delete_deposit_withdraw')->name('delete_deposit_withdraw');

		Route::post('customerDeleteDepositWithdraw','CustomerController@customer_delete_deposit_withdraw')->name('customerDeleteDepositWithdraw');

		Route::post('Customer-Delete-Invest-Withdraw-Form','CustomerController@deleteOneInBulkTxnForm')->name('deleteOneInBulkTxnForm');

		Route::post('Customer-Delete-Invest-Withdraw','CustomerController@deleteOneInBulkTxn')->name('deleteOneInBulkTxn');

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

		Route::get('getCustomerTransaction','ReportController@autocompleteCustomerTransaction')->name('customertransaction');

		// Route::get('customer_invest_search','CustomerController@searchCustomerInvest')->name('customer_invest_search');
		Route::get('customer/customerAllTransactions/{id}','CustomerController@customer_interest_invest')->name('customerAllTransactions');

        Route::get('customer_investments','CustomerController@customer_investments') ->name('customer_investments');

        Route::get('customer-bulk-investments-form','CustomerController@deposit_in_bulkform') ->name('deposit_in_bulkform');
        Route::post('customer-bulk-transactions','CustomerController@bulk_submit_transactions')->name('bulk_submit_transactions');

        Route::get('Edit-Bulk-Txn-form','CustomerController@edit_bulktxn')->name('edit_bulktxn');

        Route::post('Update-Bulk-Txn','CustomerController@update_bulk_txn')->name('update_bulk_txn');

        Route::get('Delete-All-Bulk-Txn','CustomerController@delete_all_bulktxn')->name('delete_all_bulktxn');

        Route::get('customer-activitylog/{id}','CustomerController@customer_activitylog')->name('customer_activitylog');

        Route::get('Customer-Excel-Export','CustomerController@customers_excel_export')->name('all_customer_list_excel_export');

        Route::get('Activity-Log','CustomerController@activity_log')->name('activity_log');
        Route::get('Excel-Activity-Logs','ReportController@excel_activity_logs')->name('excel_activity_logs');


        Route::post('No-Interest-Form','CustomerController@no_interest_form')->name('no_interest_form');
        Route::post('No-Interest','CustomerController@no_interest')->name('no_interest');
		Route::get('Customer-Marked-In-Associate','CustomerController@associate_marked_in_customer')->name('associate_marked_in_customer');
		Route::get('EnableDisable/{customerId}','CustomerController@cust_lock_unlock')->name('cust_enable_disable');


		Route::post('delCustomerPDC','CustomerController@delCustomerPDC')->name('delCustomerPDC');
		Route::post('destroyPDC','CustomerController@destroyPDC')->name('destroyPDC');



		Route::get('associatecustomer','ReportController@getPageAssociateWiseCustomer')
        ->name('associatecus');

		Route::get('company_bank','CompanyBankController@index')->name('company_bank');
		Route::post('addBank','CompanyBankController@store')->name('addBank');
		Route::get('perticularBankTransaction/{id}','CompanyBankController@perBankReport')->name('perticularBankTransaction');
		Route::get('editBank/{id}','CompanyBankController@edit')->name('editBank');
		Route::post('updateBank/{id}','CompanyBankController@update')->name('updateBank');

		Route::get('Bank-Transactions','CompanyBankController@excel_bank_transactions')->name('excel_bank_transactions');
		Route::get('Company-Bank','CompanyBankController@excel_company_bank')->name('excel_company_bank');
		Route::post('getFromBankCash','CompanyBankController@getFromBankCash')->name('getFromBankCash');
		Route::post('getToBankCash','CompanyBankController@getToBankCash')->name('getToBankCash');
		Route::post('b2bTransaction','CompanyBankController@b2bTransaction')->name('b2bTransaction');
		Route::post('edit_b2bTransaction','CompanyBankController@edit_b2bTransaction')->name('edit_b2bTransaction');
		Route::post('addDeductionBankTxns','CompanyBankController@addDeductionBankTxns')->name('addDeductionBankTxns');
		Route::post('editDeductionBankTxns','CompanyBankController@editDeductionBankTxns')->name('editDeductionBankTxns');
		Route::post('addDepositBankTxns','CompanyBankController@addDepositBankTxns')->name('addDepositBankTxns');
		Route::post('editDepositBankTxns','CompanyBankController@editDepositBankTxns')->name('editDepositBankTxns');
        Route::post('bank_delete_confirm','CompanyBankController@bank_delete_confirm')->name('bank_delete_confirm');
        Route::post('bankDelete','CompanyBankController@bankDelete')->name('bankDelete');
        Route::post('b2bt_delete_confirm','CompanyBankController@b2bt_delete_confirm')->name('b2bt_delete_confirm');
        Route::post('deleteBankRecords','CompanyBankController@deleteBankRecords')->name('deleteBankRecords');
        Route::post('directdepded_delete_confirm','CompanyBankController@directdepded_delete_confirm')->name('directdepded_delete_confirm');
        Route::post('deleteDepositDeductionRecords','CompanyBankController@deleteDepositDeductionRecords')->name('deleteDepositDeductionRecords');

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


		Route::get('Associate-Business-Report','ReportController@associate_business_report')->name('associate_business_report');

		Route::get('Excel-Associate-Business-Report','ReportController@excel_associate_business')->name('excel_associate_business');

		Route::get('State-Business-Report','ReportController@state_business')->name('state_business');
		Route::get('Excel-State-Business-Report','ReportController@excel_state_business')->name('excel_state_business');

		Route::get('Excel-City-Business-Report','ReportController@excel_city_business')->name('excel_city_business');

		Route::get('City-Business-Report','ReportController@city_business')->name('city_business');

		Route::get('Monthly-Payout-Report','ReportController@monthly_payout')->name('monthly_payout');

		Route::get('Excel-Customer-Wise-Monthly-Report','ReportController@excel_customer_wise_monthly_report')->name('excel_customer_wise_monthly_report');

		Route::get('Excel-All-Transaction-Report','ReportController@excel_all_transactions')->name('excel_all_transactions');

		Route::get('Excel-Monthly-Payout-Report','ReportController@excel_monthly_payout')->name('excel_monthly_payout');

		Route::get('Excel-Customer-Transaction-Report','ReportController@excel_customer_transactions')->name('excel_customer_transactions');

		Route::get('Excel-Associate-Payment-Report','ReportController@excel_associate_payment_list')->name('excel_associate_payment_list');

		Route::get('business_category','ReportController@business_category')->name('business_category');
		Route::get('Customer-Transactions','ReportController@customer_transactions')->name('customer_transactions');


		Route::get('this_month_business_category','ReportController@this_month_business_category')->name('this_month_business_category');

		Route::get('last_month_business_category','ReportController@last_month_business_category')->name('last_month_business_category');



		Route::get('Associate-Balance','ReportController@associate_balance')->name('associate_balance');
		Route::get('Customer-Debitor-Creditor-List','ReportController@debitor_creditor_list')->name('debitor_creditor_list');

		Route::get('Excel-Credit-Debit-Report','ReportController@excel_debitor_creditor_list')->name('excel_debitor_creditor_list');

		Route::get('Excel-Associate-Percent-Wise-Balance','ReportController@excel_associate_per_balance')->name('excel_associate_per_balance');

		Route::get('Associate-Payment-List','ReportController@associate_payment_list')->name('associate_payment_list');

		Route::get('Associate-Ladger-Balance','ReportController@associate_ladger_balance')->name('associate_ladger_balance');
		Route::get('List-Associate-Ladger-Balance','ReportController@list_associate_ladger')->name('list_associate_ladger');

		Route::get('Excel-Associate-Ladger-Balance','ReportController@excel_associate_ladger')->name('excel_associate_ladger');

		Route::get('Associate-Ladger-Customer','ReportController@associate_ladger_customer')->name('associate_ladger_customer');


		Route::get('Associate-Commission-List','ReportController@associate_commission_list')->name('associate_commission_list');

		Route::get('Excel-Associate-Commission-List','ReportController@excel_associate_commission_list')->name('excel_associate_commission_list');
		Route::get('Business-Category-List','ReportController@excel_business_category')->name('excel_business_category');

		Route::get('This-Month-Business-Category-List','ReportController@excel_this_month_business_category')->name('excel_this_month_business_category');

		Route::get('Last-Month-Business-Category-List','ReportController@excel_last_month_business_category')->name('excel_last_month_business_category');

		Route::post('Import-Excel-Transactions','ReportController@import_excel_transactions')->name('import_excel_transactions');

		Route::get('import_excel_for_user','ReportController@importReport')->name('userForImportExcel');


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

		Route::post('temp_interest','CustomerController@temp_interest')->name('temp_interest');

		Route::get('employee','EmployeeController@index')->name('employee');
		Route::get('privilege/{id}','EmployeeController@privilege')->name('privilege');
		Route::post('apply_privilege/{id}','EmployeeController@apply_privilege')->name('apply_privilege');

		Route::get('Employee-Excel-Export','EmployeeController@employee_excel_export')->name('all_employee_list_excel_export');


		Route::get('Entry-Lock','EntrylockController@index')->name('entry_lock');
		Route::post('Store-Entry-Lock','EntrylockController@store')->name('store_entrylocks');
		Route::get('entrylockStatus/{id}','EntrylockController@entrylockStatus');


		Route::get('import_export_report','ReportController@importReport')->name('import_export_report');
		Route::post('delete-import-excel','ReportController@delReport')->name('delete-import-excel');



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

		Route::get('Excel-Before-Confirmation-Payout','ReportController@excel_before_confirmation_payout')->name('excel_before_confirmation_payout');

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
		Route::get('perCustLadgerBalance','CustomerController@perCustomerLadgerBalance')->name('perCustLadgerBalance');


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


