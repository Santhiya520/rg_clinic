<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->virtualAs('total_amount - paid_amount');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('test_status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('reference_no');
            $table->index('patient_id');
            $table->index('payment_status');
            $table->index('test_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_lab_tests');
    }
};
