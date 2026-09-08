<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_method')->default('Cash')->after('deposit_amount');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->string('payment_receipt_url')->nullable()->after('payment_reference');
            $table->text('cancellation_reason')->nullable()->after('remarks');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('student_id')->nullable()->change();
            $table->string('tenant_name')->nullable()->after('student_id');
            $table->string('tenant_phone')->nullable()->after('tenant_name');
            $table->string('tenant_email')->nullable()->after('tenant_phone');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_reference', 'payment_receipt_url', 'cancellation_reason']);
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['tenant_name', 'tenant_phone', 'tenant_email']);
        });
    }
};
