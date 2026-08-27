<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('op_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('provisional_diagnosis')->nullable();
            $table->text('investigations')->nullable();
            $table->string('final_diagnosis')->nullable();
            $table->text('treatment')->nullable();
            $table->enum('result', ['cured', 'same_condition', 'referred', 'expired'])->default('same_condition');
            $table->text('additional_information')->nullable();
            $table->string('medical_officer')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('op_registers');
    }
};
