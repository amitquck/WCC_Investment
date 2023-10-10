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
            'associate' =>  ['slug' => 'associate', 'name' => 'Associate', 'actions' => ['view', 'add', 'edit', 'status', 'delete', 'add_transaction']],
            'customer' =>  ['slug' => 'customer', 'name' => 'Customer', 'actions' => ['view', 'add', 'edit', 'status', 'delete', 'edit_commission', 'view_investment','view_associate', 'add_withdraw']],

            // 'customer_investments' =>  ['slug' => 'customer_investments', 'name' => 'Investments', 'actions' => ['view', ]],

            'payout' => ['slug' => 'payout', 'name' => 'Payout', 'actions' => ['view']],
            'fund_management' => ['slug' => 'fund_management', 'name' => 'Fund Management', 'actions' => ['view']],
            'customerwiseinvestmentreport' => ['slug' => 'customer_wise_investment_report', 'name' => 'Customer Wise Investment Report', 'actions' => ['view', 'delete']],
            'master' => ['slug' => 'master', 'name' => 'Master', 'actions' => ['view']],
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
