<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'associate_details', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('associate_id');
            $table->string('payment_type');
            $table->string('dob')->nullable();
            $table->string('sex')->nullable();
            $table->string('father_husband_wife')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('image')->nullable();
            $table->string('address_one')->nullable();
            $table->string('address_two')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('zipcode')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_age')->nullable();
            $table->string('nominee_dob')->nullable();
            $table->string('nominee_relation_with_applicable')->nullable();
            $table->string('nominee_sex')->nullable();
            $table->string('nominee_address_one')->nullable();
            $table->string('nominee_address_two')->nullable();
            $table->integer('nominee_city_id')->nullable();
            $table->integer('nominee_state_id')->nullable();
            $table->integer('nominee_country_id')->nullable();
            $table->integer('nominee_zipcode')->nullable();
             // $table->boolean('status')->nullable();
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
        Schema::dropIfExists('associate_details');
    }
}
