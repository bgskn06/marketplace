<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
    </a>

    <hr class="sidebar-divider mb-2">

    @if (Auth::user()->role === 'admin')
        <div class="sidebar-heading">Admin Area</div>
        <li class="nav-item {{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.dashboard.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="ic:round-dashboard" size="20" />
                </span>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="fluent:person-guest-16-filled" size="20" />
                </span>
                <span>Manajemen User</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('admin.categories.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="material-symbols:category-rounded" size="20" />
                </span>
                <span>Manajemen Kategori</span>
            </a>
        </li>
    @endif

    @if (Auth::user()->role === 'seller')
        <div class="sidebar-heading">Seller Area</div>

        <li class="nav-item {{ request()->routeIs('seller.dashboard.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('seller.dashboard.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="ic:round-dashboard" size="20" />
                </span>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('seller.products.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="lets-icons:order" size="20" />
                </span>
                <span>Pesanan</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('seller.chat.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('seller.chat.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="material-symbols:chat-outline" size="20" />
                </span>
                <span>Chat</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('seller.products.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('seller.products.index') }}">
                <span class="mr-2">
                    <x-general.icon icon="gridicons:product" size="20" />
                </span>
                <span>Product</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
