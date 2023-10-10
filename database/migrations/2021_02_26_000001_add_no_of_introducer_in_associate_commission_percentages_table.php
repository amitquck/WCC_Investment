<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoOfIntroducerInAssociateCommissionPercentagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('associate_commission_percentages', function (Blueprint $table) {
            $table->integer('no_of_introducer')->after('commission_percent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('associate_commission_percentages', function (Blueprint $table) {
            $table->dropColumn(['no_of_introducer']);
        });
    }
}
