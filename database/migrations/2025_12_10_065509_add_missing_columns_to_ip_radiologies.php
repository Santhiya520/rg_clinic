<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToIpRadiologies extends Migration
{
    public function up()
    {
        Schema::table('ip_radiologies', function (Blueprint $table) {
            // Add missing columns that exist in op_radiologies
            $table->text('result')->nullable()->after('notes');
            $table->string('result_document')->nullable()->after('result');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('result_document');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('status');
            $table->timestamp('completed_at')->nullable()->after('paid_amount');

            // Change user_id to foreign key if it's not already
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Add foreign key constraint for user_id if not exists
        Schema::table('ip_radiologies', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('ip_radiologies', function (Blueprint $table) {
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
}
