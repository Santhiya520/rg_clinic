<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToMedicinePurchasesTable extends Migration
{
    public function up()
    {
        Schema::table('medicine_purchases', function (Blueprint $table) {
            $table->enum('type', ['regular', 'bulk_order'])->default('regular')->after('id');
            $table->enum('status', ['draft', 'ordered', 'received', 'cancelled'])->default('draft')->after('type');
        });
    }

    public function down()
    {
        Schema::table('medicine_purchases', function (Blueprint $table) {
            $table->dropColumn(['type', 'status']);
        });
    }
}
