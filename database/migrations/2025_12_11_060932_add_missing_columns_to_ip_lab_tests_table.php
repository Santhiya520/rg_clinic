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
        Schema::table('ip_lab_tests', function (Blueprint $table) {
            // Add missing columns that exist in op_lab_tests
            $table->text('result')->nullable()->after('notes');
            $table->string('result_document')->nullable()->after('result');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('result_document');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('status');
            $table->timestamp('completed_at')->nullable()->after('paid_amount');

            // Change user_id to foreign key if it's not already
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Add foreign key constraint for user_id if not exists
        Schema::table('ip_lab_tests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ip_lab_tests', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['user_id']);

            // Drop the added columns
            $table->dropColumn([
                'result',
                'result_document',
                'status',
                'paid_amount',
                'completed_at'
            ]);
        });
    }
};
