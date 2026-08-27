<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ip_lab_sub_tests', function (Blueprint $table) {
            $table->id();

            // Foreign key to ip_lab_tests table
            $table->foreignId('ip_lab_test_id')
                  ->constrained('ip_lab_tests')
                  ->onDelete('cascade');

            // Foreign key to lab_sub_tests table (template)
            $table->foreignId('lab_sub_test_id')
                  ->nullable()
                  ->constrained('lab_sub_tests')
                  ->onDelete('set null');

            // Test details (copied from template for data integrity)
            $table->string('test_name');
            $table->string('unit')->nullable();
            $table->string('normal_range')->nullable();

            // User entered result
            $table->string('result')->nullable();

            // Order for display
            $table->integer('order')->default(0);

            $table->timestamps();

            // Index for better performance
            $table->index('ip_lab_test_id');
            $table->index('lab_sub_test_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_lab_sub_tests');
    }
};
