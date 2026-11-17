
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Admin | Priangan Kilat Express</title>

    {{-- Bootstrap Icons untuk ikon pada form login. --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    {{-- Font sesuai identitas visual Priangan Kilat Express. --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- Memanggil stylesheet utama halaman Admin. --}}
    <link rel="stylesheet" href="{{ asset('css/priangan-admin.css') }}">
</head>

<body class="login-body">

    {{-- Panel identitas merek di sisi kiri halaman login. --}}
    <section class="login-brand">

        {{-- Logo resmi Kujang Kilat dari folder public/assets. --}}
        <img
            class="login-logo"
            src="{{ asset('assets/logo-kujang-kilat.png') }}"
            alt="Logo Kujang Kilat Priangan Kilat Express"
        >

        {{-- Nama perusahaan ekspedisi. --}}
        <h1>Priangan Kilat Express</h1>

        {{-- Slogan perusahaan. --}}
        <p>Hantarkan dengan cepat, layani dengan sepenuh hati.</p>

    </section>

    {{-- Panel formulir login Admin. --}}
    <main class="login-panel">

        
<form
    class="login-card"
    id="loginForm"
    action="{{ route('login.process') }}"
    method="POST"
>
    @csrf

    {{-- Judul dan deskripsi formulir login --}}
    <small class="eyebrow">PORTAL ADMINISTRATOR</small>

    <h2>Selamat Datang</h2>

    <p>Masuk untuk mengelola operasional pengiriman.</p>

    {{-- Input email Admin --}}
    <label for="email">Email</label>

    <input
        id="email"
        name="email"
        type="email"
        value="{{ old('email') }}"
        placeholder="Masukkan email"
        required
    >

    {{-- Input password Admin --}}
    <label for="password">Password</label>

    <div class="password-wrap">
        <input
            id="password"
            name="password"
            type="password"
            placeholder="Masukkan password"
            required
        >

        <button
            type="button"
            id="togglePassword"
            aria-label="Tampilkan password"
        >
            <i class="bi bi-eye"></i>
        </button>
    </div>

    {{-- Pesan kesalahan login dari Laravel --}}
    @if ($errors->any())
        <div class="error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Tombol masuk --}}
    <button class="primary-button" type="submit">
        Masuk
        <i class="bi bi-arrow-right"></i>
    </button>
</form>
    </main>

    {{-- JavaScript untuk fungsi interaksi halaman login. --}}
    <script src="{{ asset('js/login.js') }}"></script>

</body>
</html>
    