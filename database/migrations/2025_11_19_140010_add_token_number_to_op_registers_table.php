<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('op_registers', function (Blueprint $table) {
            $table->integer('token_number')->nullable()->after('patient_id');
            $table->foreignId('medical_officer_id')->nullable()->constrained('users')->after('medical_officer');
            $table->enum('status', ['registered', 'in_progress', 'completed'])->default('registered')->after('result');
        });
    }

    public function down(): void
    {
        Schema::table('op_registers', function (Blueprint $table) {
            $table->dropColumn(['token_number', 'medical_officer_id', 'status']);
        });
    }
};
