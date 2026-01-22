<div>
    <style>
        .order-stat { display: flex; flex-direction: column; align-items: center; gap: 6px; cursor: pointer; transition: transform 0.2s; }
        .order-stat:hover { transform: translateY(-3px); }
        .icon-box { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        .bg-primary-soft { background: rgba(13, 110, 253, .1); color: #0d6efd; }
        .bg-info-soft { background: rgba(13, 202, 240, .1); color: #0dcaf0; }
        .bg-warning-soft { background: rgba(255, 193, 7, .1); color: #ffc107; }
        .bg-success-soft { background: rgba(25, 135, 84, .1); color: #198754; }
        .bg-danger-soft { background: rgba(220, 53, 69, .1); color: #dc3545; }
        .bg-purple-soft { background: rgba(111, 66, 193, .1); color: #6f42c1; }
        .stat-card { border: 1px solid #f1f3f5; border-radius: 10px; padding: 20px; height: 100%; background: #fff; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); display: flex; flex-direction: column; justify-content: space-between; }
        .table-custom th { font-weight: 600; color: #6c757d; border-top: none; }
    </style>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold">Dashboard Toko</h4>
            <small class="text-muted">Halo {{ Auth::user()->name }}, ini performa tokomu hari ini.</small>
        </div>
        <div>
            <span class="badge badge-light border px-3 py-2 text-muted">
                <i class="fas fa-calendar mr-2"></i> {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-left-primary">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted font-weight-bold text-uppercase">Total Pendapatan</small>
                    <div class="icon-box bg-primary-soft" style="width:35px; height:35px; font-size:18px;">
                        <x-general.icon icon="mdi:currency-usd" />
                    </div>
                </div>
                {{-- DATA DINAMIS --}}
                <h4 class="fw-bold text-dark mb-1">Rp {{ number_format($revenue, 0, ',', '.') }}</h4>
                <small class="text-success">Total order selesai</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-left-warning">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted font-weight-bold text-uppercase">Perlu Dikirim</small>
                    <div class="icon-box bg-warning-soft" style="width:35px; height:35px; font-size:18px;">
                        <x-general.icon icon="mdi:package-variant-closed" />
                    </div>
                </div>
                {{-- DATA DINAMIS --}}
                <h4 class="fw-bold text-dark mb-1">{{ $toShip }} Pesanan</h4>
                @if($toShip > 0)
                    <small class="text-danger font-weight-bold">Segera proses!</small>
                @else
                    <small class="text-muted">Semua aman</small>
                @endif
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-left-info">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted font-weight-bold text-uppercase">Produk Aktif</small>
                    <div class="icon-box bg-info-soft" style="width:35px; height:35px; font-size:18px;">
                        <x-general.icon icon="mdi:tshirt-crew-outline" />
                    </div>
                </div>
                {{-- DATA DINAMIS --}}
                <h4 class="fw-bold text-dark mb-1">{{ $totalProducts }} Item</h4>
                <small class="text-muted">Total etalase</small>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-left-success">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted font-weight-bold text-uppercase">Rating Toko</small>
                    <div class="icon-box bg-success-soft" style="width:35px; height:35px; font-size:18px;">
                        <x-general.icon icon="mdi:star" />
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ number_format($rating, 1) }} / 5.0</h4>
                <small class="text-muted">Berdasarkan ulasan</small>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-dark">Status Pesanan</h6>
                    <div class="row text-center">
                        <div class="col-xl-2 col-4 mb-3">
                            <a href="#" class="text-decoration-none text-dark">
                                <div class="order-stat">
                                    <div class="icon-box bg-primary-soft">
                                        <x-general.icon icon="mdi:cart-outline" size="24" />
                                    </div>
                                    <h5 class="fw-bold mb-0 mt-2">{{ $stats['new'] }}</h5>
                                    <small class="text-muted">Belum Bayar</small>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-xl-2 col-4 mb-3">
                            <a href="{{ route('seller.orders') }}" class="text-decoration-none text-dark">
                                <div class="order-stat">
                                    <div class="icon-box bg-warning-soft position-relative">
                                        <x-general.icon icon="mdi:package-variant" size="24" />
                                        @if($stats['paid'] > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                                {{ $stats['paid'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold mb-0 mt-2">{{ $stats['paid'] }}</h5>
                                    <small class="text-muted font-weight-bold text-warning">Perlu Dikirim</small>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-success-soft">
                                    <x-general.icon icon="mdi:truck-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">{{ $stats['shipped'] }}</h5>
                                <small class="text-muted">Sedang Dikirim</small>
                            </div>
                        </div>

                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-purple-soft">
                                    <x-general.icon icon="mdi:check-circle-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">{{ $stats['completed'] }}</h5>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>

                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-danger-soft">
                                    <x-general.icon icon="mdi:backspace-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">{{ $stats['cancelled'] }}</h5>
                                <small class="text-muted">Dibatalkan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                    <a href="{{ route('seller.orders') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="pl-4">No. Invoice</th>
                                    <th>Pembeli</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- LOOPING DATA DARI DATABASE --}}
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="pl-4 font-weight-bold text-primary">
                                            {{ $order->order_number }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width:30px;height:30px;">
                                                    <i class="fas fa-user small"></i>
                                                </div>
                                                <span>{{ $order->user->name ?? 'User' }}</span>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td>
                                            {{-- Badge Status Dinamis --}}
                                            @php
                                                $badges = [
                                                    0 => ['bg' => 'danger', 'label' => 'Batal'],
                                                    1 => ['bg' => 'secondary', 'label' => 'Unpaid'],
                                                    2 => ['bg' => 'warning', 'label' => 'Perlu Dikirim'],
                                                    3 => ['bg' => 'info', 'label' => 'Dikirim'],
                                                    4 => ['bg' => 'success', 'label' => 'Selesai'],
                                                    5 => ['bg' => 'danger', 'label' => 'Refund'],
                                                ];
                                                $statusInfo = $badges[$order->status] ?? ['bg' => 'secondary', 'label' => 'Unknown'];
                                            @endphp
                                            <span class="badge badge-{{ $statusInfo['bg'] }} px-2 py-1">
                                                {{ $statusInfo['label'] }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $order->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Belum ada transaksi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>