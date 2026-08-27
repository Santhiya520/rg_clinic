<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('operation_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('operation_theatre_type')->nullable(); // Maternity/General/Ortho etc
            $table->date('date_of_admission');
            $table->string('hospital_ip_no');
            $table->text('provisional_diagnosis')->nullable();
            $table->text('investigations')->nullable();
            $table->string('operation_performed');
            $table->foreignId('operating_surgeon_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('assistant_surgeon_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('anaesthetist_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->foreignId('staff_reception_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->time('operation_start_time');
            $table->time('operation_end_time');
            $table->text('operation_notes')->nullable();
            $table->string('transferred_to_ward');
            $table->text('additional_information')->nullable();
            $table->foreignId('medical_officer_id')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('operation_registers');
    }
};
