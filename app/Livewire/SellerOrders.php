<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerOrders extends Component
{
    use WithPagination;

    public $activeTab = Order::STATUS_UNPAID;

    public $inputResi;
    public $selectedOrderId;

    public function setTab($status)
    {
        $this->activeTab = $status;
        $this->resetPage();
    }

    public function openResiModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->inputResi = '';
    }

    public function shipOrder()
    {
        $this->validate([
            'inputResi' => 'required|min:5|string',
        ]);

        $order = Order::where('id', $this->selectedOrderId)
            ->whereHas('orderItems.product.shop', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->first();

        if ($order && $order->status == Order::STATUS_PAID) {
            // get order items that belong to this seller's shop and are not shipped yet
            $sellerItems = $order->orderItems()->whereHas('product.shop', function ($q) {
                $q->where('user_id', Auth::id());
            })->whereNull('shipped_at')->get();

            if ($sellerItems->isEmpty()) {
                $this->dispatch('swal:info', [
                    'title' => 'Info',
                    'text' => 'Tidak ada item yang perlu dikirim oleh toko Anda atau sudah dikirim.'
                ]);
                return;
            }

            foreach ($sellerItems as $item) {
                $item->tracking_number = $this->inputResi;
                $item->shipped_at = now();
                $item->save();
            }

            // After marking seller's items shipped, update order status if all items shipped
            $order->updateStatusAfterItemsShipped();

            $this->dispatch('close-modal');

            $this->dispatch('swal:success', [
                'title' => 'Berhasil!',
                'text' => 'Item toko Anda berhasil ditandai sebagai dikirim.'
            ]);
        }
    }

    public function render()
    {
        $orders = Order::with(['user', 'orderItems.product.shop']) // Eager load biar cepat
            ->whereHas('orderItems.product.shop', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->where('status', $this->activeTab)
            ->latest()
            ->paginate(5);

        return view('livewire.seller-orders', [
            'orders' => $orders
        ]);
    }
}
