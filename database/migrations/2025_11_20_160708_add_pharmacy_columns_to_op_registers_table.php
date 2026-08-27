<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Create migration: php artisan make:migration add_pharmacy_columns_to_op_registers_table

    public function up()
    {
        Schema::table('op_registers', function (Blueprint $table) {
            $table->decimal('doctor_fees', 10, 2)->nullable();
            $table->decimal('pharmacy_amount', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->text('paid_status')->nullable();
            $table->timestamp('pharmacy_issued_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('op_registers', function (Blueprint $table) {
            $table->dropColumn(['doctor_fees', 'pharmacy_amount', 'total', 'paid_status', 'pharmacy_issued_at']);
        });
    }
};
