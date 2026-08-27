<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('op_radiologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('op_register_id')->constrained()->onDelete('cascade');
            $table->foreignId('radiology_test_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->text('notes')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('op_radiologies');
    }
};
