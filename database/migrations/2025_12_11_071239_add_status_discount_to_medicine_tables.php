<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDiscountToMedicineTables extends Migration
{
    public function up()
    {
        // Add columns to op_medicines table
        Schema::table('op_medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('op_medicines', 'status')) {
                $table->enum('status', ['pending', 'issued', 'cancelled'])->default('pending');
            }
            if (!Schema::hasColumn('op_medicines', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('op_medicines', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('op_medicines', 'issued_at')) {
                $table->timestamp('issued_at')->nullable();
            }
            if (!Schema::hasColumn('op_medicines', 'issued_by')) {
                $table->string('issued_by')->nullable();
            }
        });

        // Add columns to ip_medicines table
        Schema::table('ip_medicines', function (Blueprint $table) {
            if (!Schema::hasColumn('ip_medicines', 'status')) {
                $table->enum('status', ['pending', 'issued', 'cancelled'])->default('pending');
            }
            if (!Schema::hasColumn('ip_medicines', 'discount_percentage')) {
                $table->decimal('discount_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('ip_medicines', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('ip_medicines', 'issued_at')) {
                $table->timestamp('issued_at')->nullable();
            }
            if (!Schema::hasColumn('ip_medicines', 'issued_by')) {
                $table->string('issued_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('op_medicines', function (Blueprint $table) {
            $table->dropColumn(['status', 'discount_percentage', 'discount_amount', 'issued_at', 'issued_by']);
        });

        Schema::table('ip_medicines', function (Blueprint $table) {
            $table->dropColumn(['status', 'discount_percentage', 'discount_amount', 'issued_at', 'issued_by']);
        });
    }
}
