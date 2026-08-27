<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('op_lab_tests', function (Blueprint $table) {
            $table->text('result')->nullable()->after('notes');
            $table->string('result_document')->nullable()->after('result');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('result_document');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('status');
            $table->timestamp('completed_at')->nullable()->after('paid_amount');
        });
    }

    public function down()
    {
        Schema::table('op_lab_tests', function (Blueprint $table) {
            $table->dropColumn(['result', 'result_document', 'status', 'paid_amount', 'completed_at']);
        });
    }
};
