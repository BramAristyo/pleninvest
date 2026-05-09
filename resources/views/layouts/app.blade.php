<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pleninvest — by Ricky Atmoko</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/pleninvest.css') }}">
</head>
<body>

<div id="app">
    <header class="header">
        <a href="{{ url('/dashboard') }}" class="logo">Plen<span>invest</span></a>
        <div class="header-right">
            <a href="{{ url('/sheets') }}" class="gs-pill">
                <div class="gs-dot" id="gs-dot"></div>
                <span id="gs-label">Lokal</span>
            </a>
            <button class="lock-btn" onclick="lockApp()" title="Kunci Aplikasi">🔒</button>
        </div>
    </header>

    <div class="container">
        <!-- BRAND HERO -->
        <div class="brand-hero" style="margin-bottom:20px">
            <div class="brand-hero-inner">
                <div class="brand-text">
                    <div class="brand-name">Plen<span>invest</span></div>
                    <div class="brand-by">by Ricky Atmoko</div>
                    <div class="brand-pills">
                        <div class="brand-pill">
                            <div class="brand-pill-why">Why · Mengapa</div>
                            <div class="brand-pill-name">Plenitude-invest</div>
                            <div class="brand-pill-desc">Kelimpahan bukan tentang berapa banyak yang kamu punya, tapi seberapa kamu bisa menghidupi hidup.</div>
                        </div>
                        <div class="brand-pill">
                            <div class="brand-pill-why">What · Apa</div>
                            <div class="brand-pill-name">Plan-Invest</div>
                            <div class="brand-pill-desc">Rencanakan setiap rupiah dengan tujuan — bukan impulsif, tapi penuh hikmat.</div>
                        </div>
                        <div class="brand-pill">
                            <div class="brand-pill-why">What For · Rencana Dalam Memberi</div>
                            <div class="brand-pill-name">Plan-in-Vest</div>
                            <div class="brand-pill-desc">Rencana keuangan ini bertujuan agar semakin dapat memberi kehidupan bagi seluruh ciptaan.</div>
                        </div>
                    </div>
                </div>
                <div class="brand-garden-preview">
                    <canvas id="hero-canvas"></canvas>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="tabs-wrap">
            <div class="tabs">
                <a href="{{ url('/dashboard') }}" class="tab-btn {{ Request::is('dashboard') ? 'active' : '' }}">🏡 Beranda</a>
                <a href="{{ url('/transactions') }}" class="tab-btn {{ Request::is('transactions') ? 'active' : '' }}">📝 Catat</a>
                <a href="{{ url('/diversification') }}" class="tab-btn {{ Request::is('diversification') ? 'active' : '' }}">🌿 Diversifikasi</a>
                <a href="{{ url('/reimbursement') }}" class="tab-btn {{ Request::is('reimbursement') ? 'active' : '' }}">🧾 Reimburse</a>
                <a href="{{ url('/insurance') }}" class="tab-btn {{ Request::is('insurance') ? 'active' : '' }}">🛡️ Proteksi &amp; Manfaat</a>
                <a href="{{ url('/charts') }}" class="tab-btn {{ Request::is('charts') ? 'active' : '' }}">📊 Grafik</a>
                <a href="{{ url('/health') }}" class="tab-btn {{ Request::is('health') ? 'active' : '' }}">💚 Kesehatan</a>
                <a href="{{ url('/tips') }}" class="tab-btn {{ Request::is('tips') ? 'active' : '' }}">✨ Tips</a>
                <a href="{{ url('/sheets') }}" class="tab-btn {{ Request::is('sheets') ? 'active' : '' }}">🔗 Sheets</a>
            </div>
        </div>

        @yield('content')

    </div><!-- end container -->
</div><!-- end app -->

<div class="footnote">🌳 Pleninvest by Ricky Atmoko · Data tersimpan lokal + Google Sheets (opsional) · Privasi 100% ada di tanganmu · Dibuat dengan ❤️ oleh Claude</div>

<script src="{{ asset('js/pleninvest.js') }}"></script>
</body>
</html>
