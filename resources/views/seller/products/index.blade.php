<x-app-layout>
    {{-- Custom Style untuk Halaman Ini --}}
    <style>
        /* --- Style Bawaan Anda (Slider & Uploader) --- */
        .product-slider {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            overflow: hidden;
        }

        .slider-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity .3s ease;
        }

        .slider-image.active {
            opacity: 1;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, .5);
            border: none;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }

        .slider-btn.prev {
            left: 8px;
        }

        .slider-btn.next {
            right: 8px;
        }

        .image-box {
            width: 120px;
            height: 120px;
            border: 2px dashed #dee2e6;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .image-box:hover {
            border-color: #0d6efd;
            background-color: #e9ecef;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .image-box.add {
            font-size: 32px;
            color: #adb5bd;
        }

        .image-box .remove {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border: 2px solid white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .badge-cover {
            position: absolute;
            bottom: 6px;
            left: 6px;
            background: rgba(13, 110, 253, 0.9);
            color: white;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        /* --- Style Tambahan untuk Tabel Cantik --- */
        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            overflow: hidden;
        }

        .table thead th {
            border-top: none;
            border-bottom: 2px solid #f1f5f9;
            background-color: #f8f9fa;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            font-weight: 700;
            padding: 1rem;
        }

        .table tbody td {
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover {
            background-color: #fcfcfc;
        }

        .product-img-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .product-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .price-text {
            font-family: 'Consolas', 'Monaco', monospace;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Modal refinement */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
            background-color: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: #343a40;
        }

        .form-control,
        .form-select {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Daftar Produk</h4>
            <p class="text-muted small mb-0">Kelola katalog produk toko Anda di sini.</p>
        </div>
        <button class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-bold" data-toggle="modal"
            data-target="#createProductModal">
            <i class="fas fa-plus mr-2"></i> Tambah Produk
        </button>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Produk Info</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('seller.products.show', $item->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div class="d-flex align-items-center">
                                            <div class="product-img-wrapper mr-3">
                                                <img loading="lazy"
                                                    src="{{ asset('storage/' . optional($item->photos->first())->path) }}"
                                                    alt="{{ $item->name }}"
                                                    onerror="this.src='https://via.placeholder.com/56?text=No+Img'">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark text-truncate"
                                                    style="max-width: 200px;">
                                                    {{ $item->name }}
                                                </h6>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    SKU: {{ $item->sku ?? '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $item->category->name }}
                                    </span>
                                </td>
                                <td>
                                    <span class="price-text text-primary">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->stock <= 5)
                                        <span class="text-danger fw-bold">{{ $item->stock }}</span>
                                    @else
                                        <span class="text-dark">{{ $item->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill"
                                            style="background-color: #d1e7dd;">
                                            <i class="fas fa-check-circle small mr-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-soft text-secondary px-3 py-2 rounded-pill"
                                            style="background-color: #e2e3e5;">
                                            <i class="fas fa-eye-slash small mr-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        {{-- Tombol Edit (Dummy UI untuk kerapian) --}}
                                        <button class="btn btn-sm btn-outline-info rounded-circle" 
                                                style="width: 32px; height: 32px; padding: 0;"
                                                onclick="editProduct({{ json_encode($item) }})">
                                            <i class="fas fa-pen" style="font-size: 12px;"></i>
                                        </button>

                                        {{-- Action Button User --}}
                                        <div onclick="deleteProduct({{ $item->id }}, '{{ $item->name }}')">
                                            <button class="btn btn-sm btn-outline-danger rounded-circle"
                                                style="width: 32px; height: 32px; padding: 0;">
                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle p-3 mb-3">
                                            <i class="fas fa-box-open fa-2x text-muted"></i>
                                        </div>
                                        <h6 class="text-muted fw-bold">Belum ada produk</h6>
                                        <p class="text-muted small mb-0">Silakan tambahkan produk pertama Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 py-3">
            {{-- Pagination Links (Jika ada) --}}
            {{-- {{ $products->links() }} --}}
        </div>
    </div>

    <form id="delete-form" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data"
                class="w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="modal-title fw-bold text-dark">Tambah Produk Baru</h5>
                            <p class="mb-0 small text-muted">Isi informasi produk dengan lengkap</p>
                        </div>
                        <button type="button" class="close btn btn-link text-decoration-none text-muted"
                            style="font-size: 1.5rem;" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Contoh: Sepatu Sneaker Putih" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU (Opsional)</label>
                                <input type="text" name="sku" class="form-control"
                                    placeholder="Kode Unik Produk">
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="price" class="form-control" placeholder="0"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi Produk</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan detail produk..."></textarea>
                        </div>

                        <div>
                            <label class="form-label d-block">Foto Produk (Maks 5)</label>
                            <div class="p-3 bg-light rounded border border-dashed">
                                <input type="file" id="images" name="images[]" accept="image/*"
                                    class="d-none" multiple />
                                <div id="image-uploader" class="d-flex flex-wrap gap-3 align-items-center">
                                </div>
                                <small class="text-muted mt-2 d-block fst-italic">*Klik kotak tambah untuk upload.
                                    Gambar pertama akan menjadi cover.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4 fw-bold" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Produk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

{{-- SCRIPT TETAP SAMA (COPY PASTE DARI KODE ANDA) --}}
<script>
    function deleteProduct(id, name) {
        console.log(id, name);
        console.log("tombol delete di klik");

        Swal.fire({
            title: 'Hapus produk?',
            text: `Produk ${name} akan dihapus permanen`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form')
                form.action = `/seller/products/${id}`
                form.submit()
            }
        })
    }
</script>

<script>
    const input = document.getElementById('images')
    const uploader = document.getElementById('image-uploader')

    let filesBuffer = []
    const MAX = 5

    render()

    function render() {
        uploader.innerHTML = ''

        filesBuffer.forEach((file, index) => {
            const reader = new FileReader()

            reader.onload = e => {
                const box = document.createElement('div')
                box.className = 'image-box shadow-sm' // Ditambah shadow dikit

                box.innerHTML = `
                <img src="${e.target.result}">
                <button type="button" class="remove shadow-sm" onclick="removeImage(${index})">×</button>
                ${index === 0 ? '<span class="badge-cover shadow-sm">Cover</span>' : ''}
            `

                uploader.appendChild(box)
            }

            reader.readAsDataURL(file)
        })

        // Tombol +
        if (filesBuffer.length < MAX) {
            const addBox = document.createElement('div')
            addBox.className = 'image-box add shadow-sm'
            addBox.innerHTML = '<i class="fas fa-camera"></i>' // Pakai icon camera
            addBox.onclick = () => input.click()
            uploader.appendChild(addBox)
        }
    }

    // Input change
    input.addEventListener('change', function(e) {
        // Handle multiple files selection
        const newFiles = Array.from(e.target.files);

        newFiles.forEach(file => {
            if (filesBuffer.length < MAX) {
                filesBuffer.push(file);
            }
        });

        const dt = new DataTransfer()
        filesBuffer.forEach(f => dt.items.add(f))
        input.files = dt.files

        render()
    })

    // Remove image
    window.removeImage = function(index) { // Make global accessible
        filesBuffer.splice(index, 1)

        const dt = new DataTransfer()
        filesBuffer.forEach(file => dt.items.add(file))
        input.files = dt.files

        render()
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Pindahkan modal ke luar
        $('#createProductModal').appendTo("body");

        // 2. Jika ada error dari server
        @if ($errors->any())
            $('#createProductModal').modal('show');
        @endif
    });
</script>
