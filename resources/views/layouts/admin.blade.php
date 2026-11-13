
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin') | Priangan Kilat Express</title>

    {{-- Bootstrap digunakan untuk mendukung layout responsif. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font sesuai identitas visual Priangan Kilat Express. --}}
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Lora:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Memanggil stylesheet utama Admin. --}}
    <link rel="stylesheet" href="{{ asset('css/priangan-admin.css') }}">

    @stack('styles')
</head>

<body>

    {{-- Sidebar utama untuk navigasi Admin. --}}
    <aside class="sidebar" id="adminSidebar">

        {{-- Logo dan identitas perusahaan. --}}
        <a class="brand" href="{{ route('admin.dashboard') }}">
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

        {{-- Label navigasi sidebar. --}}
        <p class="sidebar-label">MENU UTAMA</p>

        <nav class="side-nav">

            {{-- Menu Dashboard. --}}
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/icons/dashboard.png') }}" alt="">
                <span>Dashboard</span>
            </a>

            {{-- Menu Input Paket. --}}
            <a href="{{ route('admin.packages.create') }}">
                <img src="{{ asset('assets/icons/tambah-paket.png') }}" alt="">
                <span>Input Paket</span>
            </a>

            {{-- Menu Data Paket. --}}
            <a href="{{ route('admin.packages.index') }}">
                <img src="{{ asset('assets/icons/paket.png') }}" alt="">
                <span>Data Paket</span>
            </a>

            {{-- Menu Penugasan Kurir. --}}
            <a href="{{ route('admin.assignments.index') }}">
                <img src="{{ asset('assets/icons/kurir.png') }}" alt="">
                <span>Penugasan Kurir</span>
            </a>

            {{-- Menu Data Pelanggan. --}}
            <a href="{{ route('admin.customers.index') }}">
                <img src="{{ asset('assets/icons/pelanggan.png') }}" alt="">
                <span>Data Pelanggan</span>
            </a>

            {{-- Menu Data Kurir dan Akun. --}}
            <a href="{{ route('admin.couriers.index') }}">
                <img src="{{ asset('assets/icons/kurir.png') }}" alt="">
                <span>Data Kurir & Akun</span>
            </a>

            {{-- Menu Setoran COD. --}}
            <a href="{{ route('admin.cod.index') }}">
                <img src="{{ asset('assets/icons/setoran-cod.png') }}" alt="">
                <span>Setoran COD</span>
            </a>

            {{-- Menu Tarif Pengiriman. --}}
            <a href="{{ route('admin.shipping-rates.index') }}">
                <img src="{{ asset('assets/icons/tarif-pengiriman.png') }}" alt="">
                <span>Tarif Pengiriman</span>
            </a>

        </nav>

        {{-- Tombol Logout. --}}
        <div class="sidebar-bottom">
            <a href="{{ route('admin.login') }}">
                <img src="{{ asset('assets/icons/logout.png') }}" alt="">
                <span>Logout</span>
            </a>
        </div>

    </aside>

    {{-- Area utama halaman Admin. --}}
    <main class="main-area">

        {{-- Navbar bagian atas. --}}
        <header class="topbar">

            {{-- Tombol untuk membuka sidebar pada layar kecil. --}}
            <button
                class="menu-toggle"
                id="menuToggle"
                aria-label="Buka menu"
            >
                ☰
            </button>

            <span>Sistem Manajemen Ekspedisi</span>

            {{-- Informasi profil Admin. --}}
            <div class="profile">
                <span class="avatar">A</span>

                <span>
                    <b>Administrator</b>
                    <small>Admin</small>
                </span>
            </div>

        </header>

        {{-- Konten halaman ditampilkan sesuai halaman yang dibuka. --}}
        <section class="page-content">
            @yield('content')
        </section>

    </main>

    {{-- Bootstrap JavaScript untuk komponen responsif. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JavaScript interaksi sidebar Admin. --}}
    <script src="{{ asset('js/priangan-admin.js') }}"></script>

    @stack('scripts')

</body>
</html>
