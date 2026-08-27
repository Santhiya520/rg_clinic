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
        Schema::table('patients', function (Blueprint $table) {
            // Add OTP columns
            $table->string('otp', 6)->nullable()->after('user_id');
            $table->timestamp('otp_expires_at')->nullable()->after('otp');

            // Add password reset columns
            $table->string('reset_token', 60)->nullable()->after('otp_expires_at');
            $table->timestamp('reset_token_expires_at')->nullable()->after('reset_token');

            // Add verification status
            $table->boolean('is_verified')->default(false)->after('reset_token_expires_at');

            // Add remember token for "Remember Me" functionality
            $table->rememberToken()->after('is_verified');

            // Add last login timestamp
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'otp',
                'otp_expires_at',
                'reset_token',
                'reset_token_expires_at',
                'is_verified',
                'remember_token',
                'last_login_at'
            ]);
        });
    }
};
