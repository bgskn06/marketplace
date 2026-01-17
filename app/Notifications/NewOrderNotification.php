<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $items; // items for this seller

    public function __construct($order, $items = [])
    {
        $this->order = $order;
        $this->items = $items;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Anda menerima pesanan baru: ' . ($this->order->order_number ?? ''))
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Anda mendapat pesanan baru. Berikut ringkasan pesanan Anda:')
            ->line('Order: ' . ($this->order->order_number ?? ''));

        $sellerTotal = 0;
        foreach ($this->items as $it) {
            $p = $it->product;
            $name = $p->name ?? 'Produk';
            $price = $p->price ?? $p->harga ?? 0;
            $lineTotal = $price * $it->quantity;
            $sellerTotal += $lineTotal;
            $mail->line("- {$name} x {$it->quantity} — Rp" . number_format($lineTotal, 0, ',', '.'));
        }

        $mail->line('Subtotal Anda untuk item ini: Rp' . number_format($sellerTotal, 0, ',', '.'));
        if (property_exists($this->order, 'shipping_price') && $this->order->shipping_price) {
            $mail->line('Ongkos kirim: Rp' . number_format($this->order->shipping_price, 0, ',', '.'));
        }

        $mail->action('Lihat Pesanan', url('/seller/orders'))
            ->line('Silakan cek rincian pesanan pada dashboard Anda. Terima kasih!');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id ?? null,
            'order_number' => $this->order->order_number ?? null,
            'items_count' => count($this->items),
        ];
    }
}
