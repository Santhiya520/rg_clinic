<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicine_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->date('purchase_date');
            $table->string('supplier_name');
            $table->string('supplier_phone')->nullable();
            $table->text('supplier_address')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['paid', 'partial', 'due'])->default('due');
            $table->text('notes')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('medicine_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('batch_number'); // Required
            $table->date('expiry_date'); // Required
            $table->integer('quantity');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_purchase_items');
        Schema::dropIfExists('medicine_purchases');
    }
};
