@php
    $user = auth()->user();
@endphp

<div class="bg-dark text-white p-3 vh-100" style="width:240px">
    <h5 class="mb-4">Marketplace</h5>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="/" class="nav-link text-white">Dashboard</a>
        </li>

        @if ($user && $user->role === 'admin')
            <li class="nav-item">
                <a href="/admin/users" class="nav-link text-white">
                    Manajemen User
                </a>
            </li>
        @endif

        @if ($user && $user->role === 'seller')
            <li class="nav-item">
                <a href="/seller/products" class="nav-link text-white">
                    Produk Saya
                </a>
            </li>
        @endif

        <li class="nav-item mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-danger w-100">
                    Logout
                </button>
            </form>
        </li>

    </ul>
</div>
