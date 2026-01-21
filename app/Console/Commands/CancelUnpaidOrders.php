<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Cancel unpaid bank transfer orders that passed their payment expiry (24h)';

    public function handle(): int
    {
        $now = now();
        $orders = Order::where('status', Order::STATUS_UNPAID)
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<=', $now)
            ->get();

        foreach ($orders as $order) {
            $order->status = Order::STATUS_CANCELLED;
            $order->save();
            \Illuminate\Support\Facades\Log::info("Order {$order->id} cancelled due to payment expiry (payment_code: {$order->payment_code})");

            // Optionally notify user (left as log to avoid referencing missing classes)
            \Illuminate\Support\Facades\Log::info("User {$order->user_id} notified (placeholder) about cancellation of order {$order->id}");
        }

        $this->info('Cancelled ' . $orders->count() . ' unpaid orders.');

        return 0;
    }
}
