<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('order_number');
            $table->text('shipping_address')->nullable()->after('recipient_name');
            $table->string('shipping_method')->nullable()->after('shipping_address');
            $table->decimal('shipping_price', 12, 2)->default(0)->after('shipping_method');
            $table->text('note')->nullable()->after('shipping_price');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['recipient_name', 'shipping_address', 'shipping_method', 'shipping_price', 'note']);
        });
    }
};
