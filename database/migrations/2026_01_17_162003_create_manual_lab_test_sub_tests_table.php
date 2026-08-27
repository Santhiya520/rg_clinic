<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manual_lab_test_sub_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_lab_test_item_id')->constrained()->onDelete('cascade');
            $table->string('test_name');
            $table->string('unit')->nullable();
            $table->string('normal_range')->nullable();
            $table->string('result')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_lab_test_sub_tests');
    }
};
