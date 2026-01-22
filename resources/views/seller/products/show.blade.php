<x-app-layout>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                {{ $product->name }}
                <span class="badge bg-light text-dark border ms-2 fs-6 fw-normal align-middle">
                    SKU: {{ $product->sku ?? '-' }}
                </span>
            </h3>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ url('seller/products') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
            <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-primary px-4">
                <i class="fas fa-pen me-1"></i> Edit Produk
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="far fa-images me-2 text-primary"></i>Galeri Produk</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            {{--
                                    Kita buat container dengan kelas khusus 'fixed-image-container'.
                                    Container ini tingginya akan dikunci lewat CSS di bawah.
                                --}}
                            <div
                                class="fixed-image-container bg-light rounded border d-flex align-items-center justify-content-center overflow-hidden">
                                @if ($product->photos->count() > 0)
                                    {{--
                                            Gambar di dalamnya diberi kelas 'fixed-image-content'.
                                            CSS akan memaksanya mengisi container.
                                        --}}
                                    <img id="mainPreview"
                                        src="{{ asset('storage/' . $product->photos->first()->path) }}"
                                        class="fixed-image-content" alt="Main Image">
                                @else
                                    <div class="text-muted text-center p-5">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Tidak ada gambar</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($product->photos->count() > 1)
                            <div class="col-md-12">
                                <div class="d-flex gap-2 overflow-auto pb-2">
                                    @foreach ($product->photos as $photo)
                                        {{-- Thumbnails sudah di-set fix 80x80 dan object-fit: cover --}}
                                        <div class="border rounded p-1 cursor-pointer thumbnail-item"
                                            onclick="changePreview('{{ asset('storage/' . $photo->path) }}', this)"
                                            style="width: 80px; height: 80px; flex-shrink: 0;">
                                            <img src="{{ asset('storage/' . $photo->path) }}"
                                                class="w-100 h-100 rounded" style="object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-align-left me-2 text-primary"></i>Detail &
                        Deskripsi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="small text-muted fw-bold text-uppercase">Kategori</label>
                        <p class="fs-6 text-dark fw-medium">{{ $product->category->name ?? 'Tidak Berkategori' }}</p>
                    </div>

                    <div>
                        <label class="small text-muted fw-bold text-uppercase mb-2">Deskripsi Produk</label>
                        <div class="bg-light p-3 rounded text-secondary" style="min-height: 100px;">
                            {!! nl2br(e($product->description ?? 'Tidak ada deskripsi.')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted">Harga Satuan</label>
                        <h2 class="text-primary fw-bold mb-0">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </h2>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Status</span>
                        @if ($product->stock > 0 && $product->is_active)
                            <span
                                class="badge bg-success-soft text-success px-3 py-2 rounded-pill border border-success">
                                <i class="fas fa-check-circle me-1"></i> Aktif / Tersedia
                            </span>
                        @else
                            <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill border border-danger">
                                <i class="fas fa-times-circle me-1"></i> Tidak Aktif / Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-box me-2 text-primary"></i>Inventaris</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium">Stok Saat Ini</span>
                        <span class="fs-4 fw-bold {{ $product->stock <= 5 ? 'text-danger' : 'text-dark' }}">
                            {{ $product->stock }} <small class="fs-6 fw-normal text-muted">pcs</small>
                        </span>
                    </div>

                    @php
                        $percent = min($product->stock, 100);
                        $colorClass = $product->stock > 10 ? 'bg-primary' : 'bg-danger';
                    @endphp
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar {{ $colorClass }}" role="progressbar"
                            style="width: {{ $percent }}%" aria-valuenow="{{ $product->stock }}"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    @if ($product->stock <= 5)
                        <div class="alert alert-warning d-flex align-items-center mt-3 mb-0 py-2 small">
                            <i class="fas fa-exclamation-triangle me-2"></i> Stok menipis, segera restock!
                        </div>
                    @endif
                </div>
            </div>
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom border-white pb-2" style="border-opacity: 0.2;">Performa
                        Produk</h6>
                    <div class="row text-center">
                        <div class="col-6 border-end border-white" style="border-opacity: 0.2;">
                            <div class="fs-3 fw-bold">{{ $product->rating ?? 0 }} <span class="fs-6">★</span></div>
                            <div class="small text-white-50">Rating</div>
                        </div>
                        <div class="col-6">
                            <div class="fs-3 fw-bold">{{ $product->reviews->count() ?? 0 }}</div>
                            <div class="small text-white-50">Ulasan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <div class="text-danger mb-3">
                        <i class="fas fa-trash-alt fa-4x"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Hapus Produk?</h4>
                    <p class="text-muted mb-4">
                        Apakah Anda yakin ingin menghapus <strong>"{{ $product->name }}"</strong>?<br>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light px-4 fw-bold"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function changePreview(imageUrl, element) {
            document.getElementById('mainPreview').src = imageUrl;
            document.querySelectorAll('.thumbnail-item').forEach(el => {
                el.classList.remove('border-primary');
                el.classList.add('border-white');
            });
            element.classList.remove('border-white');
            element.classList.add('border-primary');
        }
    </script>

    <style>
        /* 1. Kunci tinggi container gambar utama */
        .fixed-image-container {
            width: 100%;
            height: 400px;
            /* Anda bisa ubah angka ini sesuai kebutuhan (misal 350px atau 500px) */
            position: relative;
            background-color: #f8f9fa;
            /* Warna background abu muda */
        }

        /* 2. Paksa gambar mengisi container tersebut */
        .fixed-image-content {
            width: 100%;
            height: 100%;
            /*
               PENTING: object-fit: contain;
               Ini memastikan gambar terlihat utuh.
               Jika gambar kecil, dia membesar sampai mentok sisi container.
               Jika gambar besar, dia mengecil agar masuk ke container.
            */
            object-fit: contain;
        }

        /* Style lain */
        .bg-success-soft {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .bg-danger-soft {
            background-color: #f8d7da;
            color: #842029;
        }

        .thumbnail-item {
            transition: all 0.2s;
        }

        .thumbnail-item:hover {
            opacity: 0.8;
        }
    </style>

</x-app-layout>
