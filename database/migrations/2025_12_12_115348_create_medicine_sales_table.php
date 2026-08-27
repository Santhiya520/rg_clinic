<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_medicine_sales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['customer', 'radiology-use', 'lab-use', 'other'])->default('customer');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('department')->nullable();
            $table->date('sale_date');
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('total_discount', 10, 2)->default(0);
            $table->decimal('total_tax', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'due', 'internal'])->default('due');
            $table->enum('payment_method', ['cash', 'card', 'upi', 'cheque', 'internal'])->default('cash');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('invoice_number');
            $table->index('sale_date');
            $table->index('type');
            $table->index('payment_status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_sales');
    }
};
