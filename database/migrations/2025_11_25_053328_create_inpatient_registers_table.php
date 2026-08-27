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
        Schema::create('inpatient_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('hospital_ip_no');
            $table->date('date_of_admission');
            $table->text('provisional_diagnosis');
            $table->text('investigations')->nullable();
            $table->text('final_diagnosis');
            $table->text('treatment');
            $table->date('date_of_discharge');
            $table->enum('result', ['Cured', 'Same condition', 'Referred', 'Expired']);
            $table->text('additional_info')->nullable();
            $table->string('medical_officer_initials');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inpatient_registers');
    }
};
