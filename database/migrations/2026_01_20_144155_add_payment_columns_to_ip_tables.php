<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnsToIpTables extends Migration
{
    public function up()
    {
        // Add payment columns to inpatient_registers table
        Schema::table('inpatient_registers', function (Blueprint $table) {
            if (!Schema::hasColumn('inpatient_registers', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('paid_amount');
            }

            if (!Schema::hasColumn('inpatient_registers', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_type');
            }

            if (!Schema::hasColumn('inpatient_registers', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_reference');
            }

            if (!Schema::hasColumn('inpatient_registers', 'paid_status')) {
                $table->enum('paid_status', ['pending', 'partial', 'paid'])->default('pending')->after('payment_reference');
            }

            // Add pharmacy, lab, radiology total amounts
            if (!Schema::hasColumn('inpatient_registers', 'pharmacy_amount')) {
                $table->decimal('pharmacy_amount', 10, 2)->default(0)->after('overall_discount_amount');
            }

            if (!Schema::hasColumn('inpatient_registers', 'lab_total_amount')) {
                $table->decimal('lab_total_amount', 10, 2)->default(0)->after('pharmacy_amount');
            }

            if (!Schema::hasColumn('inpatient_registers', 'radiology_total_amount')) {
                $table->decimal('radiology_total_amount', 10, 2)->default(0)->after('lab_total_amount');
            }

            if (!Schema::hasColumn('inpatient_registers', 'total_discount')) {
                $table->decimal('total_discount', 10, 2)->default(0)->after('radiology_total_amount');
            }

            if (!Schema::hasColumn('inpatient_registers', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('total_discount');
            }
        });

        // Add issued_by to ip_lab_tests table if it doesn't exist
        Schema::table('ip_lab_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('ip_lab_tests', 'issued_by')) {
                $table->foreignId('issued_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }

            // Add issued_at if it doesn't exist
            if (!Schema::hasColumn('ip_lab_tests', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('issued_by');
            }
        });

        // Add issued_by to ip_radiologies table if it doesn't exist
        Schema::table('ip_radiologies', function (Blueprint $table) {
            if (!Schema::hasColumn('ip_radiologies', 'issued_by')) {
                $table->foreignId('issued_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }

            // Add issued_at if it doesn't exist
            if (!Schema::hasColumn('ip_radiologies', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('issued_by');
            }
        });

        // Add paid_amount column to ip_medicines if it doesn't exist
        Schema::table('ip_medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('ip_medicines', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('discount_amount');
            }

            // Make sure issued_by exists (it already does based on your structure)
            if (!Schema::hasColumn('ip_medicines', 'issued_by')) {
                $table->foreignId('issued_by')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }

            // Make sure issued_at exists (it already does based on your structure)
            if (!Schema::hasColumn('ip_medicines', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('issued_by');
            }
        });
    }

    public function down()
    {
        Schema::table('inpatient_registers', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'payment_reference',
                'paid_at',
                'paid_status',
                'pharmacy_amount',
                'lab_total_amount',
                'radiology_total_amount',
                'total_discount',
                'total'
            ]);
        });

        Schema::table('ip_lab_tests', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
            $table->dropColumn(['issued_by', 'issued_at']);
        });

        Schema::table('ip_radiologies', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
            $table->dropColumn(['issued_by', 'issued_at']);
        });

        Schema::table('ip_medicines', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
}
