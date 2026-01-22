<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total');
            }
            if (! Schema::hasColumn('orders', 'payment_code')) {
                $table->string('payment_code')->nullable()->unique()->after('payment_method');
            }
            if (! Schema::hasColumn('orders', 'payment_expires_at')) {
                $table->timestamp('payment_expires_at')->nullable()->after('payment_code');
            }
            if (! Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
            if (Schema::hasColumn('orders', 'payment_expires_at')) {
                $table->dropColumn('payment_expires_at');
            }
            if (Schema::hasColumn('orders', 'payment_code')) {
                $table->dropUnique(['payment_code']);
                $table->dropColumn('payment_code');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
