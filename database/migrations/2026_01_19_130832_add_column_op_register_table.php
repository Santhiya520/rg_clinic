<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOpRegisterTable extends Migration    // Changed class name
{
    public function up()
    {
        // Add payment type to op_registers
        Schema::table('op_registers', function (Blueprint $table) {
            if (!Schema::hasColumn('op_registers', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('paid_amount');
                $table->string('payment_reference')->nullable()->after('payment_type');
                $table->timestamp('paid_at')->nullable()->after('payment_reference');
            }

            // Add amount columns for lab and radiology (to store total amounts)
            if (!Schema::hasColumn('op_registers', 'lab_total_amount')) {
                $table->decimal('lab_total_amount', 10, 2)->default(0)->after('pharmacy_amount');
            }

            if (!Schema::hasColumn('op_registers', 'radiology_total_amount')) {
                $table->decimal('radiology_total_amount', 10, 2)->default(0)->after('lab_total_amount');
            }
        });

        // Add amount column to lab tests (to track individual test payment)
        Schema::table('op_lab_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('op_lab_tests', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0);
            }
        });

        // Add amount column to radiologies (to track individual test payment)
        Schema::table('op_radiologies', function (Blueprint $table) {
            if (!Schema::hasColumn('op_radiologies', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('op_registers', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'payment_reference', 'paid_at']);
            $table->dropColumn(['lab_total_amount', 'radiology_total_amount']);
        });

        Schema::table('op_lab_tests', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });

        Schema::table('op_radiologies', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
}
