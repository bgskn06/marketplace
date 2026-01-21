<div>
    <h4 class="font-weight-bold mb-4">Kelola Pesanan</h4>

    {{-- === 1. TABS NAVIGASI (Sesuai Status Teman Anda) === --}}
    <ul class="nav nav-tabs mb-4">
        {{-- Tab 2: Paid (Tugas Utama) --}}
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 2 ? 'active font-weight-bold text-primary border-top-primary' : 'text-muted' }}" href="#" wire:click.prevent="setTab(2)">
                Perlu Dikirim <i class="fas fa-box ml-1"></i>
            </a>
        </li>
        {{-- Tab 3: Shipped --}}
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 3 ? 'active font-weight-bold text-primary border-top-primary' : 'text-muted' }}" href="#" wire:click.prevent="setTab(3)">
                Dikirim <i class="fas fa-truck ml-1"></i>
            </a>
        </li>
        {{-- Tab 4: Completed --}}
        <li class="nav-item">
            <a class="nav-link {{ $activeTab == 4 ? 'active font-weight-bold text-primary border-top-primary' : 'text-muted' }}" href="#" wire:click.prevent="setTab(4)">
                Selesai
            </a>
        </li>
        {{-- Tab 0 & 5: Gagal --}}
        <li class="nav-item">
            <a class="nav-link {{ in_array($activeTab, [0, 5]) ? 'active font-weight-bold text-danger border-top-danger' : 'text-muted' }}" href="#" wire:click.prevent="setTab(0)">
                Batal / Refund
            </a>
        </li>
    </ul>

    {{-- === 2. LIST PESANAN === --}}
    <div class="row">
        @forelse($orders as $order)
        <div class="col-12 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="font-weight-bold text-primary">{{ $order->order_number }}</span>
                        <span class="mx-2 text-muted">|</span>
                        <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                    </div>
                    {{-- Badge Status --}}
                    <span class="badge {{ $order->status == 2 ? 'badge-warning' : ($order->status == 3 ? 'badge-info' : ($order->status == 4 ? 'badge-success' : 'badge-secondary')) }}">
                        {{ $order->status_label }}
                    </span>
                </div>

                <div class="card-body">
                    {{-- Info Pembeli --}}
                    <div class="mb-3">
                        <i class="fas fa-user-circle text-muted mr-1"></i>
                        <span class="font-weight-bold text-dark">{{ $order->user->name ?? 'User' }}</span>
                    </div>

                    {{-- List Barang --}}
                    @if($order->orderItems->count() > 0)
                    <div class="bg-light p-3 rounded">
                        @foreach($order->orderItems as $item)
                        @php
                        $product = $item->product;
                        $img = $product && optional($product->mainPhoto)->path ? asset('storage/'. $product->mainPhoto->path) : null;
                        $productName = $product->name ?? 'Produk tidak tersedia';
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                {{-- Gambar Kecil (jaga jika product null) --}}
                                <div class="mr-2 rounded overflow-hidden bg-white border" style="width: 40px; height: 40px;">
                                    <img src="{{ $img ?? 'https://via.placeholder.com/40' }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold small text-dark">{{ $productName }}</h6>
                                    <small class="text-muted">x{{ $item->quantity }}</small>
                                    @if($item->shipped_at)
                                    <div class="text-success small mt-1">Dikirim: {{ $item->shipped_at->format('Y-m-d') }} @if($item->tracking_number) - Resi: {{ $item->tracking_number }} @endif</div>
                                    @endif
                                </div>
                            </div>
                            <div class="font-weight-bold small">Rp {{ number_format($item->price) }}</div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-warning small">Item tidak ditemukan (Cek relasi order_items)</div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div>
                            <small class="text-muted d-block">Total Pesanan:</small>
                            <h5 class="font-weight-bold text-dark mb-0">Rp {{ number_format($order->total) }}</h5>
                        </div>

                        {{-- TOMBOL AKSI BERDASARKAN STATUS --}}
                        <div>
                            {{-- Jika PAID (2) -> Tampilkan Tombol Kirim --}}
                            @if($order->status == 2)
                            <button wire:click="openResiModal({{ $order->id }})" class="btn btn-primary btn-sm px-4" data-toggle="modal" data-target="#modalResi">
                                <i class="fas fa-shipping-fast mr-1"></i> Kirim Barang
                            </button>

                            {{-- Jika SHIPPED (3) -> Tampilkan Resi --}}
                            @elseif($order->status == 3)
                            <div class="text-right">
                                <small class="text-muted d-block">Nomor Resi:</small>
                                <span class="font-weight-bold text-success" style="font-family: monospace; font-size: 1.1em;">
                                    {{ $order->tracking_number }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-3x text-gray-300 mb-3"></i>
            <p class="text-muted">Tidak ada pesanan di status ini.</p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>

    {{-- === MODAL INPUT RESI === --}}
    <div wire:ignore.self class="modal fade" id="modalResi" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title font-weight-bold">Input Resi Pengiriman</h6>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nomor Resi / Kode Booking</label>
                        <input type="text" id="inputResi" wire:model="inputResi" class="form-control" placeholder="Contoh: JP123456789">
                        @error('inputResi') <small class="text-danger font-weight-bold">{{ $message }}</small> @enderror
                    </div>
                    <p class="small text-muted mb-0">
                        <i class="fas fa-info-circle"></i> Pastikan barang sudah diserahkan ke kurir sebelum input resi. Status akan berubah menjadi <b>Dikirim</b>.
                    </p>
                </div>
                <div class="modal-footer py-1 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="button" wire:click="shipOrder" class="btn btn-primary btn-sm">Konfirmasi Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PENDUKUNG --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        // Tutup modal otomatis setelah sukses
        Livewire.on('close-modal', () => {
            $('#modalResi').modal('hide');
        });

        Livewire.on('resetInputResi', () => {
            document.getElementById('inputResi').value = '';
        });

        // Tampilkan notifikasi Swal
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success'
                , title: data[0].title
                , text: data[0].text
                , timer: 2000
                , showConfirmButton: false
            });
        });
    });

</script>
