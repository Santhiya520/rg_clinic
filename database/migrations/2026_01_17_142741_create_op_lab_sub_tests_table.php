// database/migrations/xxxx_xx_xx_create_op_lab_sub_tests_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('op_lab_sub_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('op_lab_test_id')->constrained('op_lab_tests')->onDelete('cascade');
            $table->foreignId('lab_sub_test_id')->nullable()->constrained('lab_sub_tests')->onDelete('set null');
            $table->string('test_name'); // From lab_sub_tests table
            $table->string('unit')->nullable();
            $table->string('normal_range')->nullable();
            $table->string('result')->nullable(); // User input
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('op_lab_test_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('op_lab_sub_tests');
    }
};
