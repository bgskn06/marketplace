<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SellerOrders extends Component
{
    use WithPagination;

    public $activeTab = Order::STATUS_PAID;
    
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
                      ->where('seller_id', Auth::id())
                      ->first();

        if ($order && $order->status == Order::STATUS_PAID) {
            $order->update([
                'status' => Order::STATUS_SHIPPED,
                'tracking_number' => $this->inputResi
            ]);

            $this->dispatch('close-modal');
            
            $this->dispatch('swal:success', [
                'title' => 'Berhasil!', 
                'text' => 'Resi berhasil diinput. Status pesanan berubah menjadi Dikirim.'
            ]);
        }
    }

    public function render()
    {
        $orders = Order::with(['user', 'orderItems.product']) // Eager load biar cepat
            ->where('seller_id', Auth::id())
            ->where('status', $this->activeTab)
            ->latest()
            ->paginate(5);

        return view('livewire.seller-orders', [
            'orders' => $orders
        ]);
    }
}