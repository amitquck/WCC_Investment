<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateCommissionPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'associate_commission_percentages', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('customer_id')->comment('userid');
            $table->integer('associate_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            // $table->integer('customer_investment_id');
            // $table->integer('interest_amount');
            // $table->float('sum_of_commission');
            $table->float('commission_percent');
            $table->boolean('status')->comment('active_status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->SoftDeletes();

        
        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('associate_commission_percentages');
    }
}
