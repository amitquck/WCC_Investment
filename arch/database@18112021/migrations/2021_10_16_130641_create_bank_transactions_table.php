<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->decimal('amount',10,2);
            $table->string('cr_dr')->comment('cr= invest, dr = widra');
            $table->string('payment_type');
            $table->date('transaction_date');
            $table->date('cheque_dd_date')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('cheque_dd_number')->nullable();
            $table->string('respective_table_id')->nullable();
            $table->string('respective_table_name')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->SoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
}
