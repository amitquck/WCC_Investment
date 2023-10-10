<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\Permission;

class CreateUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        Schema::create(
            'user_permissions', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('permission_id');
            $table->integer('user_id');
            $table->string('action_name');
            $table->boolean('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->SoftDeletes();

        
        }
        );

       $permissions = [
            'dashboard' => ['slug' => 'dashboard', 'name' => 'Dashboard', 'actions' => ['view']],

            'associate' =>  ['slug' => 'associate', 'name' => 'Associate', 'actions' => ['view', 'view_detail', 'add', 'edit', 'status', 'delete', 'add_transaction', 'view_ladger_balance', 'view_marked_customer', 'view_transaction', 'export']],

            'customer' =>  ['slug' => 'customer', 'name' => 'Customer', 'actions' => ['view', 'view_detail', 'add', 'edit', 'status', 'delete', 'view_ladger_balance', 'payment_status', 'view_marked_associate', 'no_interest', 'view_transaction', 'add-investment', 'edit_commission', 'add_withdraw', 'edit_transaction', 'delete_transaction', 'export']],

            'pdc_issued' => ['slug' => 'pdc_issued', 'name' => 'PDC Issued', 'actions' => ['view', 'view_detail', 'export']],

            'on_hold' => ['slug' => 'on_hold', 'name' => 'On Hold', 'actions' => ['view', 'view_detail', 'view_transaction', 'export']],

            'asso_bulk_transaction' => ['slug' => 'asso_bulk_transaction', 'name' => 'Associate Bulk Transaction', 'actions' => ['view', 'add_asso_bulk_transaction', 'edit_asso_bulk_transaction', 'delete_asso_bulk_transaction']],

            'cust_bulk_transaction' => ['slug' => 'cust_bulk_transaction', 'name' => 'Customer Bulk Transaction', 'actions' => ['view', 'add_cust_bulk_transaction', 'edit_cust_bulk_transaction', 'delete_cust_bulk_transaction']],

            'pdc_issued_count' => ['slug' => 'pdc_issued_count', 'name' => 'PDC Issued Count', 'actions' => ['view', 'view_detail', 'export']],

            'payout' => ['slug' => 'payout', 'name' => 'Payout', 'actions' => ['view']],

            'generated_payout' => ['slug' => 'generated_payout', 'name' => 'Generated Payout', 'actions' => ['view', 'deleted']],

            'fund_management' => ['slug' => 'fund_management', 'name' => 'Fund Management', 'actions' => ['view']],

            'associate_wise_customer' => ['slug' => 'associate_wise_customer', 'name' => 'Associate Wise Customer Report', 'actions' => ['view', 'export']],

            'associate_payment' => ['slug' => 'associate_payment', 'name' => 'Associate Payment Report', 'actions' => ['view', 'export']],

            'all_transaction' => ['slug' => 'all_transaction', 'name' => 'All Transaction', 'actions' => ['view', 'export']],

            'associate_ladger_balance' => ['slug' => 'associate_ladger_balance', 'name' => 'Associate Ladger Balance', 'actions' => ['view', 'view_detail']],

            'associate_commission' => ['slug' => 'associate_commission', 'name' => 'Associate Commission', 'actions' => ['view', 'view_detail', 'export']],

            'customer_ladger_balance' => ['slug' => 'customer_ladger_balance', 'name' => 'Customer Ladger Balance', 'actions' => ['view', 'view_detail', 'export']],

            'monthly_payout' => ['slug' => 'monthly_payout', 'name' => 'Monthly Payout Report', 'actions' => ['view', 'view_detail', 'export', 'import']],

            'debitor_creditor' => ['slug' => 'debitor_creditor', 'name' => 'Debitor/Creditor Report', 'actions' => ['view', 'view_detail', 'export']],

            'associate_per_wise_balance' => ['slug' => 'associate_per_wise_balance', 'name' => 'Associate % Wise Balance', 'actions' => ['view', 'export']],

            'master' => ['slug' => 'master', 'name' => 'Master', 'actions' => ['view']],

            'company_bank' => ['slug' => 'company_bank', 'name' => 'Company Bank', 'actions' => ['view', 'add', 'edit', 'view_transaction', 'export']],

            'activity_log' => ['slug' => 'activity_log', 'name' => 'Activity Log', 'actions' => ['view', 'export']],

            'entry_lock' => ['slug' => 'entry_lock', 'name' => 'Entry Lock', 'actions' => ['view','create' , 'status']],

            'backup' => ['slug' => 'backup', 'name' => 'Backup', 'actions' => ['view']],
        ];
        foreach($permissions as $permission)
        {
            DB::table('permissions')->insert([
                'slug' => $permission['slug'],
                'name' => $permission['name'],
                'actions' => implode(',',$permission['actions']),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }


}
