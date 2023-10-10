<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSecurityChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_security_cheques', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->date('cheque_issue_date')->nullable();
            $table->date('cheque_maturity_date')->nullable();
            $table->string('cheque_bank_name')->nullable();
            $table->string('cheque_amount')->nullable();
            $table->string('scan_copy')->nullable();
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
        Schema::dropIfExists('customer_security_cheques');
    }
}
