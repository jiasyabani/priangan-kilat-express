
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Manajer') | Priangan Kilat Express</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Lora:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/priangan-admin.css') }}">

    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="adminSidebar">

        {{-- BRAND --}}
        <a class="brand" href="{{ route('manager.dashboard') }}">
            <img
                class="brand-logo"
                src="{{ asset('assets/logo-kujang-kilat.png') }}"
                alt="Logo Kujang Kilat"
            >

            <span>
                <b>PRIANGAN</b>
                <small>KILAT EXPRESS</small>
            </span>
        </a>

        <p class="sidebar-label">MENU UTAMA</p>

        {{-- NAVIGASI MANAGER --}}
        <nav class="side-nav">

            {{-- Dashboard --}}
            <a href="{{ route('manager.dashboard') }}"
               class="{{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/dashboard.png') }}" alt="">
                <span>Dashboard</span>
            </a>

            {{-- Laporan Pengiriman --}}
            <a href="{{ route('manager.reports.index') }}"
               class="{{ request()->routeIs('manager.reports.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/paket.png') }}" alt="">
                <span>Laporan Pengiriman</span>
            </a>

            {{-- Laporan Pendapatan --}}
            <a href="{{ route('manager.revenue-reports.index') }}"
               class="{{ request()->routeIs('manager.revenue-reports.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/tarif-pengiriman.png') }}" alt="">
                <span>Laporan Pendapatan</span>
            </a>

            {{-- Rekap COD --}}
            <a href="{{ route('manager.cod-reports.index') }}"
               class="{{ request()->routeIs('manager.cod-reports.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/paket.png') }}" alt="">
                <span>Rekap COD</span>
            </a>

            {{-- Pengaturan Tarif --}}
            <a href="{{ route('manager.shipping-rates.index') }}"
               class="{{ request()->routeIs('manager.shipping-rates.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/tarif-pengiriman.png') }}" alt="">
                <span>Pengaturan Tarif</span>
            </a>

            {{-- Data Pelanggan --}}
            <a href="{{ route('manager.customers.index') }}"
               class="{{ request()->routeIs('manager.customers.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/pelanggan.png') }}" alt="">
                <span>Data Pelanggan</span>
            </a>

            {{-- Data Kurir --}}
            <a href="{{ route('manager.couriers.index') }}"
               class="{{ request()->routeIs('manager.couriers.*') ? 'active' : '' }}">
                <img src="{{ asset('assets/icons/kurir.png') }}" alt="">
                <span>Data Kurir</span>
            </a>

        </nav>

        {{-- LOGOUT --}}
        <div class="sidebar-bottom">

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-button">
                    <img src="{{ asset('assets/icons/logout.png') }}" alt="">
                    <span>Logout</span>
                </button>
            </form>

        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main-area">

        {{-- TOPBAR --}}
        <header class="topbar">

            <button
                class="menu-toggle"
                id="menuToggle"
                aria-label="Buka menu"
            >
                ☰
            </button>

            <span>Sistem Manajemen Ekspedisi</span>

            {{-- PROFILE --}}
            <div class="profile">

                <span class="avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>

                <span>
                    <b>{{ Auth::user()->name }}</b>
                    <small>Manajer</small>
                </span>

            </div>

        </header>

        {{-- PAGE CONTENT --}}
        <section class="page-content">
            @yield('content')
        </section>

    </main>

    {{-- JAVASCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/priangan-admin.js') }}"></script>

    @stack('scripts')

</body>
</html>
