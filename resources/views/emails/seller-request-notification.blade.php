<h2>Permohonan Seller Baru</h2>
<p>Ada permohonan seller baru dari user: <strong>{{ $request->user->name ?? '-' }}</strong></p>
<ul>
    <li>Nama Toko: {{ $request->shop_name }}</li>
    <li>Alamat Toko: {{ $request->shop_address }}</li>
    <li>No HP: {{ $request->phone }}</li>
    <li>Deskripsi: {{ $request->shop_description }}</li>
</ul>
<p>Status: <strong>{{ ucfirst($request->status) }}</strong></p>
