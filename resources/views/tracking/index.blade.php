
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Resi | Priangan Kilat Express</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brown: #493326;
            --dark: #332319;
            --orange: #d98a35;
            --cream: #f8f3eb;
            --paper: #fffdfa;
            --muted: #887d73;
            --line: #eee5da;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--cream);
            color: var(--brown);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .navbar {
            background: var(--dark);
            color: white;
            padding: 17px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 43px;
            height: 43px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            background: var(--orange);
            color: var(--dark);
            font-size: 23px;
            font-weight: 800;
        }
.brand-logo {
    width: 48px;
    height: 48px;
    object-fit: contain;
    display: block;
    border-radius: 10px;
    background: white;
    padding: 3px;
    flex-shrink: 0;
}
        .brand-name b,
        .brand-name small {
            display: block;
        }

        .brand-name b {
            font-size: 14px;
            letter-spacing: 1px;
        }

        .brand-name small {
            margin-top: 3px;
            color: #e8b879;
            font-size: 9px;
            letter-spacing: 2px;
        }

        .nav-link {
            font-size: 12px;
            color: #e9dfd5;
        }

        .hero {
            background: var(--dark);
            color: white;
            text-align: center;
            padding: 58px 20px 105px;
        }

        .eyebrow {
            color: #e8b879;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .hero h1 {
            margin: 13px 0 10px;
            font: 400 38px 'Bree Serif', serif;
        }

        .hero p {
            margin: 0;
            color: #d9c8b7;
            font-size: 13px;
        }

        .container {
            width: min(760px, calc(100% - 32px));
            margin: -58px auto 55px;
            position: relative;
        }

        .search-card,
        .result-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 15px;
            box-shadow: 0 12px 35px rgba(51, 35, 25, .07);
        }

        .search-card {
            padding: 25px;
            margin-bottom: 20px;
        }

        .search-card label {
            display: block;
            margin-bottom: 11px;
            font-size: 12px;
            font-weight: 800;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            min-width: 0;
            height: 48px;
            padding: 0 15px;
            border: 1px solid var(--line);
            border-radius: 8px;
            outline: none;
            font: 12px 'Plus Jakarta Sans', sans-serif;
            color: var(--brown);
        }

        .search-form input:focus {
            border-color: var(--orange);
        }

        .search-form button {
            border: 0;
            border-radius: 8px;
            padding: 0 22px;
            background: var(--orange);
            color: var(--dark);
            font: 700 12px 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #c77a28;
        }

        .error-box {
            margin-top: 13px;
            padding: 12px 14px;
            border-radius: 8px;
            background: #fff0ed;
            color: #a43e32;
            font-size: 11px;
        }

        .result-card {
            padding: 25px;
        }

        .result-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--line);
        }

        .result-heading small {
            color: var(--muted);
            font-size: 10px;
        }

        .result-heading h2 {
            margin: 7px 0 0;
            font-size: 19px;
            letter-spacing: .5px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            background: #fff0d9;
            color: #a96825;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .package-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            padding: 20px 0;
            border-bottom: 1px solid var(--line);
        }

        .info-item small {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 10px;
        }

        .info-item strong {
            font-size: 12px;
        }

        .section-title {
            margin: 24px 0 20px;
            font-size: 14px;
        }

        .timeline {
            position: relative;
            padding-left: 27px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            top: 7px;
            bottom: 10px;
            left: 7px;
            width: 2px;
            background: var(--line);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 23px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            left: -27px;
            top: 3px;
            width: 16px;
            height: 16px;
            border: 4px solid #f5dfc5;
            border-radius: 50%;
            background: var(--orange);
        }

        .timeline-item h4 {
            margin: 0 0 5px;
            font-size: 12px;
        }

        .timeline-item time {
            color: var(--muted);
            font-size: 10px;
        }

        .timeline-item p {
            margin: 7px 0 0;
            color: #76695f;
            font-size: 11px;
            line-height: 1.6;
        }

        .empty-state {
            padding: 24px 10px;
            text-align: center;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            margin: 0 0 7px;
            font: 400 21px 'Bree Serif', serif;
        }

        .empty-state p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .footer {
            padding: 22px;
            text-align: center;
            color: var(--muted);
            font-size: 10px;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 14px 18px;
            }

            .hero {
                padding: 45px 18px 95px;
            }

            .hero h1 {
                font-size: 30px;
            }

            .search-card,
            .result-card {
                padding: 19px;
            }

            .search-form {
                flex-direction: column;
            }

            .search-form button {
                height: 46px;
            }

            .result-heading {
                flex-direction: column;
            }

            .package-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="/" class="brand">
           <img
    src="{{ asset('assets/logo-kujang-kilat.png') }}"
    alt="Logo Kujang Kilat"
    class="brand-logo"
>
            <div class="brand-name">
                <b>PRIANGAN KILAT</b>
                <small>EXPRESS</small>
            </div>
        </a>

        <a href="/" class="nav-link">Beranda</a>
    </nav>

    <section class="hero">
        <div class="eyebrow">LAYANAN PELACAKAN PAKET</div>
        <h1>Lacak Kirimanmu</h1>
        <p>Masukkan nomor resi untuk mengetahui posisi terkini paketmu.</p>
    </section>

    <main class="container">

        <section class="search-card">
            <form action="{{ route('tracking.index') }}" method="GET">
                <label for="resi">Nomor Resi Pengiriman</label>

                <div class="search-form">
                    <input
                        type="text"
                        id="resi"
                        name="resi"
                        value="{{ $resi }}"
                        placeholder="Contoh: PKE-261119-0001"
                        maxlength="50"
                        required
                    >

                    <button type="submit">Lacak Paket</button>
                </div>
            </form>

            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first('resi') }}
                </div>
            @endif
        </section>

        @if ($tidakDitemukan)
            <section class="result-card">
                <div class="empty-state">
                    <div class="empty-icon">!</div>
                    <h3>Resi Tidak Ditemukan</h3>
                    <p>
                        Nomor resi <strong>{{ $resi }}</strong> belum terdaftar.
                        Periksa kembali nomor resi yang kamu masukkan.
                    </p>
                </div>
            </section>

        @elseif ($paket)

            <section class="result-card">

                <div class="result-heading">
                    <div>
                        <small>NOMOR RESI</small>
                        <h2>{{ $paket['no_resi'] }}</h2>
                    </div>

                    <span class="status-badge">
                        {{ $paket['status'] }}
                    </span>
                </div>

                <div class="package-info">
                    <div class="info-item">
                        <small>Nomor Telepon Penerima</small>
                        <strong>{{ $paket['no_telepon'] }}</strong>
                    </div>

                    <div class="info-item">
                        <small>Tanggal Paket Diterima</small>
                        <strong>
                            {{ $paket['tanggal_masuk']?->format('d M Y, H:i') ?? '-' }}
                        </strong>
                    </div>
                </div>

                <h3 class="section-title">Riwayat Perjalanan Paket</h3>

                <div class="timeline">
                    @forelse ($paket['riwayat'] as $riwayat)
                        <div class="timeline-item">
                            <span class="timeline-dot"></span>

                            <h4>{{ $riwayat->status_baru }}</h4>

                            <time>
                                {{ $riwayat->waktu_perubahan?->format('d M Y, H:i') ?? '-' }}
                            </time>

                            @if ($riwayat->catatan)
                                <p>{{ $riwayat->catatan }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="timeline-item">
                            <span class="timeline-dot"></span>
                            <h4>{{ $paket['status'] }}</h4>
                            <p>Belum terdapat catatan riwayat perjalanan paket.</p>
                        </div>
                    @endforelse
                </div>

            </section>
        @endif

    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} Priangan Kilat Express. Layanan pengiriman terpercaya.
    </footer>

</body>
</html>
