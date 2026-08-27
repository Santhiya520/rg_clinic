<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_lab_test_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_lab_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_test_id')->constrained('lab_tests')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->text('result')->nullable();
            $table->text('notes')->nullable();
            $table->string('result_document')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('manual_lab_test_id');
            $table->index('lab_test_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_lab_test_items');
    }
};
