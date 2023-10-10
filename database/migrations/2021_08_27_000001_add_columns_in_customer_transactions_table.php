<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInCustomerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->string('payment_type_import_excel')->nullable()->after('status');
            $table->timestamp('import_excel_date')->nullable()->after('payment_type_import_excel');
            $table->timestamp('month_year_import_excel')->nullable()->after('payment_type_import_excel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_type_import_excel','import_excel_date','month_year_import_excel']);
        });
    }
}
