<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateRewardTempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'associate_reward_temps', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('associate_id');
            $table->integer('customer_id');
            // $table->integer('customer_investment_id');
            $table->decimal('amount',10,2);
            $table->string('month');
            $table->string('year');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('payable_days');
            $table->decimal('total_amount',10,2);
            $table->float('commission_percent');
            $table->string('reward_type');
            $table->date('posting_date');
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
        Schema::dropIfExists('associate_reward_temps');
    }
}
