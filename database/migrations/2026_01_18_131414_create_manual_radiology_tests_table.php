<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manual_radiology_tests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('patient_id')->constrained('patients');
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('test_status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('manual_radiology_test_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_radiology_test_id')->constrained('manual_radiology_tests')->onDelete('cascade');
            $table->foreignId('radiology_test_id')->constrained('radiology_tests');
            $table->decimal('price', 10, 2);
            $table->text('result')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->string('result_document')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_radiology_test_items');
        Schema::dropIfExists('manual_radiology_tests');
    }
};
