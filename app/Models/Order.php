<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'tracking_number',
        'status',
    ];

    const STATUS_CANCELLED = 0;
    const STATUS_UNPAID = 1;
    const STATUS_PAID = 2;      // Pesanan Masuk (Siap Dikemas)
    const STATUS_SHIPPED = 3;   // Sedang Dikirim
    const STATUS_COMPLETED = 4;
    const STATUS_REFUNDED = 5;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function getSellerStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Cancelled',
            1 => 'Unpaid',
            2 => 'Paid',
            3 => 'Shipped',
            4 => 'Completed',
            5 => 'Refunded',
            default => 'Unknown',
        };
    }

    public function getStatusLabelAttribute()
    {
        return $this->seller_status_label; // reuse same labels for general use
    }

    /**
     * Check whether all order items are shipped.
     */
    public function allItemsShipped(): bool
    {
        return ! $this->orderItems()->whereNull('shipped_at')->exists();
    }

    /**
     * Called after seller marks their items shipped. If all items shipped, update order status to SHIPPED.
     */
    public function updateStatusAfterItemsShipped(): self
    {
        if ($this->allItemsShipped()) {
            $this->status = self::STATUS_SHIPPED;
            $this->shipped_at = now();
            $this->save();

            // TODO: dispatch notification to buyer
        }

        return $this;
    }
}
