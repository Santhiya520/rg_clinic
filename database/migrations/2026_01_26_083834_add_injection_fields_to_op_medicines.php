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
        Schema::table('op_medicines', function (Blueprint $table) {
            $table->boolean('sos')->default(false)->after('night');
            $table->boolean('ml')->default(false)->after('sos');
            $table->string('injection_type', 20)->nullable()->after('ml'); // IM, IV, ID, SUB-Q
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('op_medicines', function (Blueprint $table) {
            //
        });
    }
};
