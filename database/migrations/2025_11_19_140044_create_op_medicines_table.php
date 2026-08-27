<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('op_medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('op_register_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('morning')->nullable();
            $table->integer('afternoon')->nullable();
            $table->integer('evening')->nullable();
            $table->integer('no_of_days')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 8, 2);
            $table->text('instructions')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('op_medicines');
    }
};
