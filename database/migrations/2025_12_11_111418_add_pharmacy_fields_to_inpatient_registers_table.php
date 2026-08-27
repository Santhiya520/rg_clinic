<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPharmacyFieldsToInpatientRegistersTable extends Migration
{
    public function up()
    {
        Schema::table('inpatient_registers', function (Blueprint $table) {
            $table->decimal('overall_discount_percentage', 5, 2)->nullable()->after('user_id');
            $table->decimal('overall_discount_amount', 10, 2)->nullable()->after('overall_discount_percentage');
            $table->decimal('paid_amount', 10, 2)->nullable()->after('overall_discount_amount');
        });
    }

    public function down()
    {
        Schema::table('inpatient_registers', function (Blueprint $table) {
            $table->dropColumn([
                'overall_discount_percentage',
                'overall_discount_amount',
                'paid_amount'
            ]);
        });
    }
}
