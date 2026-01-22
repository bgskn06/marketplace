<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" class="text-success">
            <path d="M9 16v-8l3 5l3 -5v8"></path>
            <path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284
              c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27
              a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27
              a2.225 2.225 0 0 1 -1.158 -1.948v-7.285
              c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98
              a2.33 2.33 0 0 1 2.25 0l6.75 3.98"></path>
        </svg>
        <div class="sidebar-brand-text mx-3">MARKETIFY</div>
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

        <li class="nav-item {{ request()->routeIs('seller.orders.*') ? 'active' : '' }}">
            <a class="nav-link d-flex align-items-center" href="{{ route('seller.orders') }}">
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
