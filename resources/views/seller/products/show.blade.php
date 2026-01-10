<x-app-layout>
    <div class="row">
        <div class="col-md-5">
            @foreach ($product->photos as $photo)
                <img src="{{ asset('storage/' . $photo->path) }}" width="120">
            @endforeach
        </div>

        <div class="col-md-7">
            <h3>{{ $product->nama_produk }}</h3>

            <h4 class="text-primary">
                Rp {{ number_format($product->harga, 0, ',', '.') }}
            </h4>

            <p class="mt-3">
                {{ $product->deskripsi ?? '-' }}
            </p>

            <p>
                <strong>Stok:</strong> {{ $product->stok }}
            </p>

            <div class="mt-4">
                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-warning">
                    Edit
                </a>

                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                    Hapus
                </button>
            </div>
        </div>
    </div>

</x-app-layout>
