<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'associate_transactions', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('associate_id');
            $table->integer('customer_id')->nullable();
            // $table->integer('customer_investment_id');
            $table->decimal('amount',10,2);
            $table->string('cr_dr');
            $table->string('payment_type');
            $table->string('transaction_type');
            $table->date('deposit_date');
            $table->date('cheque_dd_date')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('cheque_dd_number')->nullable();
            $table->string('respective_table_id')->nullable();
            $table->string('respective_table_name')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('associate_transactions');
    }
}
