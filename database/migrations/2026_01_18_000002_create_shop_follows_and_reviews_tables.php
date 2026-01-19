<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('shop_follows')) {
            Schema::create('shop_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['user_id', 'shop_id']);
            });
        }

        if (!Schema::hasTable('shop_reviews')) {
            Schema::create('shop_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
                $table->unsignedTinyInteger('rating');
                $table->text('comment')->nullable();
                $table->foreignId('order_id')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'shop_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_reviews');
        Schema::dropIfExists('shop_follows');
    }
};
