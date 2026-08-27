<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_medicine_sale_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->timestamps();

            // Indexes
            $table->index('medicine_sale_id');
            $table->index('medicine_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_sale_items');
    }
};
