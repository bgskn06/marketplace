{{-- <x-app-layout>
    <style>
        /* Menggunakan style yang sudah Anda buat */
        .order-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .order-stat:hover {
            transform: translateY(-3px);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        /* Soft background colors */
        .bg-primary-soft { background: rgba(13, 110, 253, .1); color: #0d6efd; }
        .bg-info-soft { background: rgba(13, 202, 240, .1); color: #0dcaf0; }
        .bg-warning-soft { background: rgba(255, 193, 7, .1); color: #ffc107; }
        .bg-success-soft { background: rgba(25, 135, 84, .1); color: #198754; }
        .bg-danger-soft { background: rgba(220, 53, 69, .1); color: #dc3545; }
        .bg-purple-soft { background: rgba(111, 66, 193, .1); color: #6f42c1; }

        /* Stat card adjustments */
        .stat-card {
            border: 1px solid #f1f3f5;
            border-radius: 10px;
            padding: 20px;
            height: 100%;
            background: #fff;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .table-custom th {
            font-weight: 600;
            color: #6c757d;
            border-top: none;
        }
    </style>

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 font-weight-bold">Dashboard Toko</h4>
                <small class="text-muted">Selamat datang kembali, semangat jualan hari ini!</small>
            </div>
            <div>
                <span class="badge badge-light border px-3 py-2 text-muted">
                    <i class="fas fa-calendar mr-2"></i> {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card border-left-primary">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted font-weight-bold text-uppercase">Pendapatan Bulan Ini</small>
                    <div class="icon-box bg-primary-soft" style="width:35px; height:35px; font-size:18px;">
                        <x-general.icon icon="mdi:currency-usd" />
                    </div>
                </div>
                <h4 class="fw-bold text-dark mb-1">Rp 15.200.000</h4>
                <small class="text-success d-flex align-items-center">
                    <x-general.icon icon="mdi:arrow-up" size="16"/> 
                    <span class="ml-1">5.2% dari bulan lalu</span>
                </small>
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
                <h4 class="fw-bold text-dark mb-1">5 Pesanan</h4>
                <small class="text-muted">Segera proses sebelum batas waktu</small>
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
                <h4 class="fw-bold text-dark mb-1">45 Item</h4>
                <small class="text-muted">3 produk stok menipis</small>
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
                <h4 class="fw-bold text-dark mb-1">4.8 / 5.0</h4>
                <small class="text-success">Performa toko Sangat Baik</small>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-dark">Status Pesanan Real-time</h6>
                    <div class="row text-center">
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-primary-soft">
                                    <x-general.icon icon="mdi:cart-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">12</h5>
                                <small class="text-muted">Pesanan Baru</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-info-soft">
                                    <x-general.icon icon="mdi:clipboard-check-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">4</h5>
                                <small class="text-muted">Siap Kemas</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-warning-soft position-relative">
                                    <x-general.icon icon="mdi:package-variant" size="24" />
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                        5
                                    </span>
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">5</h5>
                                <small class="text-muted font-weight-bold text-warning">Perlu Dikirim</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-success-soft">
                                    <x-general.icon icon="mdi:truck-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">8</h5>
                                <small class="text-muted">Sedang Dikirim</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-purple-soft">
                                    <x-general.icon icon="mdi:check-circle-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">120</h5>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                        <div class="col-xl-2 col-4 mb-3">
                            <div class="order-stat">
                                <div class="icon-box bg-danger-soft">
                                    <x-general.icon icon="mdi:backspace-outline" size="24" />
                                </div>
                                <h5 class="fw-bold mb-0 mt-2">2</h5>
                                <small class="text-muted">Dibatalkan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan (30 Hari)</h6>
                    <div class="dropdown no-arrow">
                         <button class="btn btn-sm btn-light"><i class="fas fa-filter mr-1"></i> Filter</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="myAreaChart"></canvas>
                        <div class="text-center text-muted mt-5 pt-5">
                            (Chart akan muncul disini)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Butuh Perhatian <i class="fas fa-exclamation-circle ml-1"></i></h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center p-3">
                            <div class="icon-box bg-warning-soft mr-3" style="width:40px; height:40px; font-size:18px; flex-shrink:0;">
                                <x-general.icon icon="mdi:alert" />
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <span class="font-weight-bold small">Stok Menipis</span>
                                    <small class="text-muted">Baru saja</small>
                                </div>
                                <small class="text-muted d-block">Sepatu Nike Air (Sisa 1)</small>
                                <a href="#" class="small text-primary">Restock sekarang &rarr;</a>
                            </div>
                        </li>
                         <li class="list-group-item d-flex align-items-center p-3">
                            <div class="icon-box bg-info-soft mr-3" style="width:40px; height:40px; font-size:18px; flex-shrink:0;">
                                <x-general.icon icon="mdi:message-text" />
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <span class="font-weight-bold small">Pesan Belum Dibaca</span>
                                    <small class="text-muted">10m lalu</small>
                                </div>
                                <small class="text-muted d-block">Dari: Budi Santoso</small>
                                <a href="#" class="small text-primary">Balas &rarr;</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-white text-center small">
                    <a href="#">Lihat semua notifikasi</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Transaksi Terakhir</h6>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua Pesanan</a>
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
                                    <th class="text-right pr-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="pl-4 font-weight-bold text-primary">#INV-2023001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle mr-2" style="width:30px;height:30px;"></div>
                                            <span>Siti Aminah</span>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">Rp 150.000</td>
                                    <td><span class="badge badge-warning px-2 py-1">Perlu Dikirim</span></td>
                                    <td class="small text-muted">10 menit lalu</td>
                                    <td class="text-right pr-4">
                                        <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-4 font-weight-bold text-primary">#INV-2023002</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle mr-2" style="width:30px;height:30px;"></div>
                                            <span>Joko Anwar</span>
                                        </div>
                                    </td>
                                    <td class="font-weight-bold">Rp 75.000</td>
                                    <td><span class="badge badge-success px-2 py-1">Selesai</span></td>
                                    <td class="small text-muted">1 jam lalu</td>
                                    <td class="text-right pr-4">
                                        <button class="btn btn-sm btn-light border"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout> --}}

<x-app-layout>
    @livewire('seller-dashboard')
</x-app-layout>