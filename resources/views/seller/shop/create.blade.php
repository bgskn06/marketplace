<h4>Buat Toko</h4>

<form method="POST" enctype="multipart/form-data" action="{{ route('seller.shop.store') }}">
@csrf

<input name="name" class="form-control mb-2" placeholder="Nama Toko">
<textarea name="address" class="form-control mb-2" placeholder="Alamat"></textarea>
<input type="file" name="logo" class="form-control mb-2">

<button class="btn btn-primary">Simpan</button>
</form>