@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#createProductModal').modal('show');
        });
    </script>
@endif

<style>
    .product-slider {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        /* ⬅️ KUNCI */
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
</style>


<style>
    .image-box {
        width: 120px;
        height: 120px;
        border: 2px dashed #ced4da;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        border-radius: 6px;
    }

    .image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .image-box.add {
        font-size: 40px;
        color: #6c757d;
    }

    .image-box .remove {
        position: absolute;
        top: 4px;
        right: 4px;
        background: red;
        color: white;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        cursor: pointer;
    }

    .badge-cover {
        position: absolute;
        bottom: 4px;
        left: 4px;
        background: #0d6efd;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>

<x-app-layout>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Product</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createProductModal">
            Tambah Produk
        </button>
    </div>
    {{-- @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif --}}

    <div class="card shadow-sm mb-2">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $item)
                        <tr>
                            <td>
                                <div class="row gap-2">
                                    <div class="overflow-hidden d-flex justify-content-center align-items-center"
                                        style="width: 40px; height: 40px;">
                                        <img loading="lazy"
                                            src="{{ asset('storage/' . optional($item->photos->first())->path) }}"
                                            alt="{{ $item->pic_id?->nick_name }}" class="img-fluid"
                                            style="object-fit: cover; width: 100%; height: 100%;">
                                    </div>
                                    {{ $item->name }}
                                </div>
                            </td>
                            <td>{{ $item->category->name ?? ($item->category ?? '–') }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>{{ $item->is_active }}</td>
                            <td>
                                <x-general.action-button icon="iconamoon:trash-bold"
                                    onclick="deleteProduct({{ $item->id }}, '{{ $item->name }}')"
                                    variant="danger" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <div class="row">
        @forelse ($products as $product)
            <div class="col-md-2 mb-4">

                <div class="card product-card shadow-sm">

                    <div class="product-slider" data-index="0">
                        @foreach ($product->photos as $photo)
                            <img src="{{ asset('storage/' . $photo->path) }}"
                                class="slider-image {{ $loop->first ? 'active' : '' }}">
                        @endforeach

                        @if ($product->photos->count() > 1)
                            <button class="slider-btn prev">‹</button>
                            <button class="slider-btn next">›</button>
                        @endif
                    </div>
                    <a href="{{ route('seller.products.show', $product) }}" class="text-decoration-none text-dark">
                        <div class="card-body">
                            <h6 class="card-title">
                                {{ $product->name }}
                            </h6>

                            <p class="mb-1 text-primary fw-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>

                            <small class="text-muted">
                                Stok: {{ $product->stock }}
                            </small>
                        </div>
                    </a>

                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $product->id }}"
                        data-name="{{ $product->name }}">
                        <x-general.icon icon="iconamoon:trash-bold" />
                    </button>

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                Belum ada produk
            </div>
        @endforelse
    </div> --}}

    <div class="modal fade" id="createProductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" name="name" class="form-control" required>
                            <select name="category_id" class="form-control" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="col">
                                <label>Harga</label>
                                <input type="number" name="price" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Stok</label>
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label>Foto Produk</label>
                            <input type="file" id="images" name="images[]" accept="image/*" class="d-none" />

                            <div id="image-uploader" class="d-flex flex-wrap gap-2">
                                <!-- preview + add button render via JS -->
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

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
                box.className = 'image-box'

                box.innerHTML = `
                <img src="${e.target.result}">
                <button class="remove" onclick="removeImage(${index})">×</button>
                ${index === 0 ? '<span class="badge-cover">Cover</span>' : ''}
            `

                uploader.appendChild(box)
            }

            reader.readAsDataURL(file)
        })

        // Tombol +
        if (filesBuffer.length < MAX) {
            const addBox = document.createElement('div')
            addBox.className = 'image-box add'
            addBox.innerHTML = '+'
            addBox.onclick = () => input.click()
            uploader.appendChild(addBox)
        }
    }

    // Input change
    input.addEventListener('change', function(e) {
        const file = e.target.files[0]
        if (!file) return

        filesBuffer.push(file)

        const dt = new DataTransfer()
        filesBuffer.forEach(f => dt.items.add(f))
        input.files = dt.files

        render()
    })

    // Remove image
    function removeImage(index) {
        filesBuffer.splice(index, 1)

        const dt = new DataTransfer()
        filesBuffer.forEach(file => dt.items.add(file))
        input.files = dt.files

        render()
    }
</script>

<script>
    document.querySelectorAll('.product-slider').forEach(slider => {

        const images = slider.querySelectorAll('.slider-image')
        if (images.length <= 1) return

        let index = 0

        const show = i => {
            images.forEach(img => img.classList.remove('active'))
            images[i].classList.add('active')
        }

        slider.querySelector('.next')?.addEventListener('click', e => {
            e.stopPropagation()
            index = (index + 1) % images.length
            show(index)
        })

        slider.querySelector('.prev')?.addEventListener('click', e => {
            e.stopPropagation()
            index = (index - 1 + images.length) % images.length
            show(index)
        })
    })
</script>
