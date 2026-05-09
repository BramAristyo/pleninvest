@extends('layouts.app')

@section('content')
<!-- MAIN APP -->
<div id="app">

<header class="header">
  <div class="logo" onclick="window.scrollTo(0,0)">Plen<span>invest</span></div>
  <div class="header-right">
    <div class="gs-pill" onclick="switchTab('sheets',document.querySelector('[data-tab=sheets]'))">
      <div class="gs-dot" id="gs-dot"></div>
      <span id="gs-label">Lokal</span>
    </div>
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
      <button class="tab-btn active" data-tab="beranda" onclick="switchTab('beranda',this)">🏡 Beranda</button>
      <button class="tab-btn" data-tab="catat" onclick="switchTab('catat',this)">📝 Catat</button>
      <button class="tab-btn" data-tab="divers" onclick="switchTab('divers',this)">🌿 Diversifikasi</button>
      <button class="tab-btn" data-tab="reimburse" onclick="switchTab('reimburse',this)">🧾 Reimburse</button>
      <button class="tab-btn" data-tab="asuransi" onclick="switchTab('asuransi',this)">🛡️ Proteksi &amp; Manfaat</button>
      <button class="tab-btn" data-tab="grafik" onclick="switchTab('grafik',this)">📊 Grafik</button>
      <button class="tab-btn" data-tab="kesehatan" onclick="switchTab('kesehatan',this)">💚 Kesehatan</button>
      <button class="tab-btn" data-tab="tips" onclick="switchTab('tips',this)">✨ Tips</button>
      <button class="tab-btn" data-tab="sheets" onclick="switchTab('sheets',this)">🔗 Sheets</button>
    </div>
  </div>


  <!-- ===== BERANDA ===== -->
  <div class="section active" id="tab-beranda">
    <div id="garden-wrap">
      <canvas id="garden-canvas"></canvas>
      <div class="garden-overlay">
        <div class="garden-score-badge">
          <div class="garden-score-num" id="g-score">—</div>
          <div class="garden-score-lbl">Skor Keuangan</div>
        </div>
        <div class="garden-level-badge" id="g-level">Mulai catat dulu 🌱</div>
      </div>
      <div class="garden-msg" id="garden-msg"></div>
    </div>

    <div class="g4">
      <div class="stat-pill"><div class="stat-label">Pemasukan Bulan Ini</div><div class="stat-val inc" id="b-inc">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Pengeluaran</div><div class="stat-val exp" id="b-exp">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Surplus</div><div class="stat-val bal" id="b-bal">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Net Worth</div><div class="stat-val gold-c" id="b-nw">Rp 0</div></div>
    </div>

    <div class="g2">
      <div class="stat-pill"><div class="stat-label">Portofolio Diversifikasi</div><div class="stat-val bal" id="b-port">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Reimburse Pending</div><div class="stat-val" style="color:var(--lake-deep)" id="b-rmb">Rp 0</div></div>
    </div>

    <div class="card">
      <div class="card-title">⚖️ Net Worth & Level Keuangan</div>
      <div id="level-display" style="padding:14px;background:var(--sage-pale);border-radius:var(--radius-sm)">
        <div style="font-size:11px;color:var(--medium);margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px">Level Keuangan</div>
        <div id="level-title" style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;color:var(--forest-dark)">Isi data untuk melihat level</div>
        <div id="level-desc" style="font-size:12px;color:var(--medium);margin-top:4px"></div>
        <div id="level-equiv" style="font-size:11px;color:var(--forest);font-style:italic;margin-top:6px"></div>
      </div>
    </div>
  </div>

  <!-- ===== CATAT ===== -->
  <div class="section" id="tab-catat">
    <div class="month-nav">
      <button class="mnav-btn" onclick="changeMonth(-1)">‹</button>
      <span class="mnav-label" id="month-label"></span>
      <button class="mnav-btn" onclick="changeMonth(1)">›</button>
    </div>

    <div class="g3">
      <div class="stat-pill"><div class="stat-label">Pemasukan</div><div class="stat-val inc" id="c-inc">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Pengeluaran</div><div class="stat-val exp" id="c-exp">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Sisa</div><div class="stat-val bal" id="c-bal">Rp 0</div></div>
    </div>

    <div class="card">
      <div class="card-title">➕ Tambah Transaksi</div>
      <div class="g2">
        <div class="field"><label>Tanggal</label><input type="date" id="txn-date" min="2000-10-01"></div>
        <div class="field"><label>Jenis</label>
          <select id="txn-type" onchange="updateCategories()">
            <option value="income">📈 Pemasukan</option>
            <option value="expense">📉 Pengeluaran</option>
          </select>
        </div>
      </div>
      <div class="g2">
        <div class="field"><label>Kategori</label>
          <select id="txn-cat"></select>
        </div>
        <div class="field"><label>Keterangan</label><input type="text" id="txn-note" placeholder="Contoh: Gaji Mei..."></div>
      </div>
      <div class="g2">
        <div class="field"><label>Nominal (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="txn-amount" placeholder="0" min="0"></div></div>
        <div class="field"><label>Tunjangan / Asuransi?</label>
          <select id="txn-benefit">
            <option value="">— Tidak ada —</option>
            <option value="tunjangan">Tunjangan</option>
            <option value="klaim_asuransi">Klaim Asuransi</option>
          </select>
        </div>
      </div>
      <div class="field" id="benefit-note-wrap" style="display:none"><label>Keterangan Tunjangan/Asuransi</label><input type="text" id="txn-benefit-note" placeholder="Contoh: Tunjangan transportasi, klaim BPJS..."></div>
      <button class="btn btn-forest" onclick="addTransaction()">➕ Tambah Transaksi</button>
    </div>

    <div class="card" style="border:1.5px dashed var(--sage-light)">
      <div class="collapse-toggle" onclick="toggleHistorical()" style="display:flex;align-items:center;justify-content:space-between;cursor:pointer;padding-bottom:0;border-bottom:none">
        <div class="card-title" style="margin-bottom:0">📅 Input Data Historis (Okt 2000 – kini)</div>
        <span id="hist-arrow" style="font-size:12px;color:var(--medium);transition:transform .3s">▼</span>
      </div>
      <div id="hist-body" style="display:none;margin-top:14px">
        <div style="background:var(--lake-pale);border-radius:8px;padding:9px 13px;margin-bottom:12px;font-size:12px;color:var(--lake-deep)">Masukkan transaksi lama sekaligus. Pilih bulan &amp; tahun, isi transaksi, lalu simpan semua.</div>
        <div class="g2">
          <div class="field"><label>Bulan</label>
            <select id="hist-month">
              <option value="01">Januari</option><option value="02">Februari</option><option value="03">Maret</option>
              <option value="04">April</option><option value="05">Mei</option><option value="06">Juni</option>
              <option value="07">Juli</option><option value="08">Agustus</option><option value="09">September</option>
              <option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
            </select>
          </div>
          <div class="field"><label>Tahun</label><select id="hist-year"></select></div>
        </div>
        <div id="hist-rows"></div>
        <div style="display:flex;gap:8px;margin-top:8px">
          <button class="btn btn-outline btn-sm" onclick="addHistRow()">+ Baris</button>
          <button class="btn btn-forest btn-sm" onclick="saveHistorical()">Simpan Semua</button>
        </div>
        <div id="hist-msg" style="margin-top:8px"></div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">📋 Riwayat Transaksi Bulan Ini</div>
      <div class="txn-hdr"><span>Tgl</span><span>Kategori · Ket.</span><span>Benefit</span><span style="text-align:right">Nominal</span><span></span></div>
      <div class="txn-list" id="txn-list"></div>
    </div>

    <div class="card">
      <div class="card-title">⚖️ Net Worth Kuartalan</div>
      <div class="card-sub">Update setiap 3 bulan. Ini gambaran keuanganmu yang sesungguhnya.</div>
      <div class="g2">
        <div class="field"><label>Tabungan</label><div class="pre"><span>Rp</span><input type="number" id="nw-sav" placeholder="0" oninput="calcAll()"></div></div>
        <div class="field"><label>Dana Darurat</label><div class="pre"><span>Rp</span><input type="number" id="nw-em" placeholder="0" oninput="calcAll()"></div></div>
        <div class="field"><label>Investasi/Reksadana</label><div class="pre"><span>Rp</span><input type="number" id="nw-inv" placeholder="0" oninput="calcAll()"></div></div>
        <div class="field"><label>Aset Lain</label><div class="pre"><span>Rp</span><input type="number" id="nw-oth" placeholder="0" oninput="calcAll()"></div></div>
        <div class="field"><label>Total Utang</label><div class="pre"><span>Rp</span><input type="number" id="nw-dbt" placeholder="0" oninput="calcAll()"></div></div>
      </div>
      <div style="background:var(--sage-pale);border-radius:var(--radius-sm);padding:14px;display:flex;justify-content:space-between;align-items:center">
        <div>
          <div style="font-size:10px;color:var(--medium);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Net Worth</div>
          <div id="nw-val" style="font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:var(--charcoal)">Rp 0</div>
        </div>
        <div id="nw-lvl" style="font-size:12px;font-weight:500;color:var(--forest)">—</div>
      </div>
    </div>
  </div>

  <!-- ===== DIVERSIFIKASI ===== -->
  <div class="section" id="tab-divers">
    <div class="price-update-bar">
      <span>📡 Harga diperbarui: <span id="last-update">—</span></span>
      <button class="refresh-btn" onclick="fetchAllPrices()">⟳ Perbarui Harga</button>
    </div>

    <div class="g4">
      <div class="stat-pill"><div class="stat-label">Total Portofolio</div><div class="stat-val bal" id="d-total">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Modal</div><div class="stat-val" style="color:var(--medium)" id="d-modal">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Untung/Rugi</div><div class="stat-val" id="d-pl">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Return (%)</div><div class="stat-val" id="d-ret">0%</div></div>
    </div>

    <div class="card">
      <div class="card-title">🌿 Portofolio Aset</div>
      <div id="asset-list"></div>
      <button class="btn btn-outline btn-sm" style="margin-top:12px;width:auto" onclick="toggleAddAsset()">＋ Tambah Aset</button>
    </div>

    <div class="card" id="add-asset-card" style="display:none">
      <div class="card-title">➕ Tambah Aset Baru</div>
      <div class="g2">
        <div class="field"><label>Jenis Aset</label>
          <select id="a-type">
            <option value="cash">💵 Cash / Tunai</option>
            <option value="deposito">🏦 Deposito</option>
            <option value="emas">🥇 Emas</option>
            <option value="vallas">💱 Valas</option>
            <option value="saham">📈 Saham</option>
            <option value="lainnya">📦 Lainnya</option>
          </select>
        </div>
        <div class="field"><label>Nama / Label</label><input type="text" id="a-name" placeholder="Contoh: BCA, ANTM, USD..."></div>
      </div>
      <div class="g2">
        <div class="field"><label>Tanggal Beli</label><input type="date" id="a-date" min="2000-10-01"></div>
        <div class="field"><label>Harga Beli (per unit / total)</label><div class="pre"><span>Rp</span><input type="number" id="a-buy-price" placeholder="0"></div></div>
      </div>
      <div class="g2">
        <div class="field"><label>Jumlah / Lot / Gram</label><input type="number" id="a-qty" placeholder="1" min="0.001" step="0.001"></div>
        <div class="field"><label>Ticker / Kode (opsional)</label><input type="text" id="a-ticker" placeholder="Contoh: BBCA.JK, XAU, USD"></div>
      </div>
      <div class="field"><label>Keterangan</label><input type="text" id="a-note" placeholder="Catatan tambahan..."></div>
      <div class="g2">
        <button class="btn btn-sage" onclick="addAsset()">Simpan Aset</button>
        <button class="btn btn-outline" onclick="toggleAddAsset()">Batal</button>
      </div>
    </div>

    <div class="ai-panel" id="ai-panel">
      <div class="ai-title">🤖 Rekomendasi Claude AI</div>
      <div class="ai-content" id="ai-content">
        <div style="font-size:12px;color:rgba(255,255,255,.6);margin-bottom:12px">Dapatkan saran diversifikasi terbaik berdasarkan portofoliomu saat ini.</div>
        <button onclick="getAIReco()" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:9px 18px;border-radius:100px;font-family:'Inter',sans-serif;font-size:12px;font-weight:500;cursor:pointer;">✨ Minta Saran AI</button>
      </div>
    </div>
  </div>

  <!-- ===== REIMBURSE ===== -->
  <div class="section" id="tab-reimburse">
    <div class="g3">
      <div class="stat-pill"><div class="stat-label">Total Reimburse (Bulan Ini)</div><div class="stat-val" style="color:var(--lake-deep)" id="r-total">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Pending</div><div class="stat-val" style="color:var(--warn)" id="r-pending">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Sudah Diterima</div><div class="stat-val" style="color:var(--good)" id="r-received">Rp 0</div></div>
    </div>

    <div class="card">
      <div class="card-title">🧾 Tambah Klaim Reimburse</div>
      <div class="card-sub">Reimburse tidak menambah pengeluaran pribadi — ini dicatat terpisah.</div>
      <div class="g2">
        <div class="field"><label>Tanggal</label><input type="date" id="r-date" min="2000-10-01"></div>
        <div class="field"><label>Kategori</label>
          <select id="r-cat">
            <option value="bensin">⛽ Bensin</option>
            <option value="buku">📚 Buku</option>
            <option value="seminar">🎓 Seminar</option>
            <option value="listrik">💡 Listrik</option>
            <option value="air">💧 Air</option>
            <option value="listrik_air">💡💧 Listrik & Air (gabung)</option>
            <option value="ipl">🏘️ IPL</option>
            <option value="parkir">🅿️ Parkir</option>
            <option value="kesehatan">🏥 Kesehatan</option>
            <option value="lainnya">📦 Lainnya</option>
          </select>
        </div>
      </div>
      <div class="g2">
        <div class="field"><label>Nominal (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="r-amount" placeholder="0"></div></div>
        <div class="field"><label>Keterangan</label><input type="text" id="r-note" placeholder="Contoh: BBM ke kampus..."></div>
      </div>
      <div class="field"><label>Status</label>
        <select id="r-status">
          <option value="pending">⏳ Pending</option>
          <option value="approved">✅ Disetujui</option>
          <option value="received">💰 Sudah Diterima</option>
        </select>
      </div>
      <button class="btn btn-lake" onclick="addReimburse()">Tambah Reimburse</button>
    </div>

    <div class="card">
      <div class="card-title">📋 Riwayat Reimburse</div>
      <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap">
        <select id="r-filter-cat" onchange="renderReimburse()" style="padding:7px 11px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:'Inter',sans-serif;font-size:12px;background:var(--cream);outline:none">
          <option value="">Semua Kategori</option>
          <option value="bensin">Bensin</option>
          <option value="buku">Buku</option>
          <option value="seminar">Seminar</option>
          <option value="listrik">Listrik</option>
          <option value="air">Air</option>
          <option value="listrik_air">Listrik & Air</option>
          <option value="ipl">IPL</option>
          <option value="parkir">Parkir</option>
          <option value="kesehatan">Kesehatan</option>
          <option value="lainnya">Lainnya</option>
        </select>
        <select id="r-filter-status" onchange="renderReimburse()" style="padding:7px 11px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:'Inter',sans-serif;font-size:12px;background:var(--cream);outline:none">
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="approved">Disetujui</option>
          <option value="received">Diterima</option>
        </select>
      </div>
      <div class="rmb-hdr"><span>Tgl</span><span>Kategori</span><span>Keterangan</span><span style="text-align:right">Nominal</span><span>Status</span><span></span></div>
      <div class="txn-list" id="rmb-list"></div>
    </div>
  </div>

  <!-- ===== ASURANSI ===== -->
  <div class="section" id="tab-asuransi">
    <div class="g2">
      <div class="stat-pill"><div class="stat-label">Total Premi / Bulan</div><div class="stat-val" style="color:var(--lake-deep)" id="ins-total">Rp 0</div></div>
      <div class="stat-pill"><div class="stat-label">Total Tunjangan / Bulan</div><div class="stat-val inc" id="tnj-total">Rp 0</div></div>
    </div>

    <div class="card">
      <div class="card-title">🛡️ Proteksi — Tambah Asuransi</div>
      <div class="g2">
        <div class="field"><label>Nama Asuransi</label><input type="text" id="ins-name" placeholder="Contoh: BPJS Kesehatan, Prudential..."></div>
        <div class="field"><label>Jenis</label>
          <select id="ins-type">
            <option value="jiwa">Jiwa</option>
            <option value="kesehatan">Kesehatan</option>
            <option value="kendaraan">Kendaraan</option>
            <option value="properti">Properti</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
      </div>
      <div class="g2">
        <div class="field"><label>Premi / Bulan (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="ins-premium" placeholder="0"></div></div>
        <div class="field"><label>Jatuh Tempo</label><input type="date" id="ins-due" min="2000-10-01"></div>
      </div>
      <div class="field"><label>Coverage / Keterangan</label><input type="text" id="ins-coverage" placeholder="Contoh: Rawat inap hingga 1M..."></div>
      <button class="btn btn-lake" style="margin-bottom:8px" onclick="addInsurance()">Tambah Asuransi</button>
    </div>

    <div class="card">
      <div class="card-title">💼 Manfaat — Tambah Tunjangan</div>
      <div class="g2">
        <div class="field"><label>Nama Tunjangan</label><input type="text" id="tnj-name" placeholder="Contoh: Tunjangan transportasi..."></div>
        <div class="field"><label>Kategori</label>
          <select id="tnj-type">
            <option value="transportasi">Transportasi</option>
            <option value="makan">Makan</option>
            <option value="perumahan">Perumahan / Pastori</option>
            <option value="pendidikan">Pendidikan</option>
            <option value="kesehatan">Kesehatan</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
      </div>
      <div class="g2">
        <div class="field"><label>Nominal / Bulan (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="tnj-amount" placeholder="0"></div></div>
        <div class="field"><label>Keterangan</label><input type="text" id="tnj-note" placeholder="Catatan..."></div>
      </div>
      <button class="btn btn-sage" onclick="addTunjangan()">Tambah Tunjangan</button>
    </div>

    <div class="card">
      <div class="card-title">🛡️ Daftar Proteksi (Asuransi)</div>
      <div id="ins-list"><div class="empty-state"><div class="ei">🛡️</div>Belum ada asuransi tercatat</div></div>
    </div>

    <div class="card">
      <div class="card-title">💼 Daftar Manfaat (Tunjangan)</div>
      <div id="tnj-list"><div class="empty-state"><div class="ei">💼</div>Belum ada tunjangan tercatat</div></div>
    </div>

  <!-- CICILAN EMAS - inside asuransi tab -->
  <div class="card" style="border:1.5px solid #D4A843" id="cicilan-emas-card">
    <div class="card-title">&#127885; Cicilan Emas</div>
    <div class="card-sub">Beli emas bertahap dengan cicilan. Tracking gram terbeli, progress bayar, dan nilai vs modal.</div>
    <button class="btn btn-outline btn-sm" style="width:auto;margin-bottom:12px" onclick="toggleCicilanForm()">+ Tambah Cicilan Emas</button>
    <div id="cicilan-form" style="display:none;padding-top:12px;border-top:1px solid var(--border)">
      <div class="g2">
        <div class="field"><label>Nama / Label</label><input type="text" id="ce-name" placeholder="Contoh: Emas Antam 2gr"></div>
        <div class="field"><label>Tanggal Mulai</label><input type="date" id="ce-start" min="2000-10-01"></div>
      </div>
      <div class="g2">
        <div class="field"><label>Target Berat (gram)</label><input type="number" id="ce-gram" placeholder="2" min="0.1" step="0.1"></div>
        <div class="field"><label>Harga Beli /gram saat itu (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="ce-price-gram" placeholder="0"></div></div>
      </div>
      <div class="g2">
        <div class="field"><label>Uang Muka / DP (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="ce-dp" placeholder="0"></div></div>
        <div class="field"><label>Cicilan per Bulan (Rp)</label><div class="pre"><span>Rp</span><input type="number" id="ce-monthly" placeholder="0"></div></div>
      </div>
      <div class="g2">
        <div class="field"><label>Durasi (bulan)</label><input type="number" id="ce-duration" placeholder="12" min="1"></div>
        <div class="field"><label>Sudah Dibayar (bulan ke-)</label><input type="number" id="ce-paid-input" placeholder="0" min="0"></div>
      </div>
      <div class="field"><label>Keterangan</label><input type="text" id="ce-note" placeholder="Catatan..."></div>
      <div class="g2">
        <button class="btn btn-forest" onclick="addCicilanEmas()">Simpan</button>
        <button class="btn btn-outline" onclick="toggleCicilanForm()">Batal</button>
      </div>
    </div>
    <div id="cicilan-list"></div>
  </div>

  </div>

  <!-- ===== GRAFIK ===== -->
  <div class="section" id="tab-grafik">
    <div class="chart-grid">
      <div class="chart-card">
        <div class="chart-title">🍩 Komposisi Pengeluaran</div>
        <div class="donut-wrap"><canvas id="ch-expense-donut"></canvas><div class="donut-center"><div class="donut-cv" id="dnut-exp-total">—</div><div class="donut-cl">Pengeluaran</div></div></div>
        <div class="legend-row" id="dnut-exp-legend"></div>
      </div>
      <div class="chart-card">
        <div class="chart-title">🍩 Alokasi Diversifikasi</div>
        <div class="donut-wrap"><canvas id="ch-asset-donut"></canvas><div class="donut-center"><div class="donut-cv" id="dnut-asset-total">—</div><div class="donut-cl">Portofolio</div></div></div>
        <div class="legend-row" id="dnut-asset-legend"></div>
      </div>
    </div>
    <div class="card">
      <div class="chart-title">📊 Tren 6 Bulan Terakhir</div>
      <canvas id="ch-trend" height="100"></canvas>
    </div>
    <div class="chart-grid">
      <div class="chart-card">
        <div class="chart-title">📈 Arus Kas Harian</div>
        <canvas id="ch-daily" height="140"></canvas>
      </div>
      <div class="chart-card">
        <div class="chart-title">🌿 Reimburse per Kategori</div>
        <canvas id="ch-rmb" height="140"></canvas>
      </div>
    </div>
  </div>

  <!-- ===== KESEHATAN ===== -->
  <div class="section" id="tab-kesehatan">
    <div class="card">
      <div class="card-title">📊 Input Data Keuangan Bulanan</div>
      <div class="g2">
        <div class="field"><label>Total Pemasukan / Bulan</label><div class="pre"><span>Rp</span><input type="number" id="h-inc" placeholder="0" oninput="calcHealth()"></div></div>
        <div class="field"><label>Total Pengeluaran / Bulan</label><div class="pre"><span>Rp</span><input type="number" id="h-exp" placeholder="0" oninput="calcHealth()"></div></div>
        <div class="field"><label>Tabungan / Bulan</label><div class="pre"><span>Rp</span><input type="number" id="h-sav" placeholder="0" oninput="calcHealth()"></div></div>
        <div class="field"><label>Cicilan Utang / Bulan</label><div class="pre"><span>Rp</span><input type="number" id="h-dbt" placeholder="0" oninput="calcHealth()"></div></div>
        <div class="field"><label>Dana Darurat (total)</label><div class="pre"><span>Rp</span><input type="number" id="h-em" placeholder="0" oninput="calcHealth()"></div></div>
        <div class="field"><label>Persepuluhan / Persembahan</label><div class="pre"><span>Rp</span><input type="number" id="h-tth" placeholder="0" oninput="calcHealth()"></div></div>
      </div>
    </div>

    <div class="score-card">
      <div class="score-lbl">Skor Kesehatan Finansial</div>
      <div class="score-num" id="score-num">—</div>
      <div id="score-badge" class="level-badge" style="background:var(--sage-pale);color:var(--forest)">Isi data dulu 🌱</div>
      <div class="score-grade" id="score-grade"></div>
      <div class="score-equiv" id="score-equiv"></div>
    </div>

    <div class="chart-grid">
      <div class="chart-card">
        <div class="chart-title">🕸 Radar Kesehatan</div>
        <canvas id="ch-radar" height="200"></canvas>
      </div>
      <div class="chart-card">
        <div class="chart-title">📊 Skor Per Metrik</div>
        <canvas id="ch-health-bar" height="200"></canvas>
      </div>
    </div>

    <div class="card">
      <div class="card-title">🔍 Detail 5 Metrik Utama</div>
      <div class="metric-row">
        <div><div class="metric-name">Rasio Tabungan</div><div class="metric-sub">Target: ≥ 20% pemasukan · Pakar: Ramit Sethi</div><div class="prog-bar"><div class="prog-fill fill-g" id="pb-sav" style="width:0%"></div></div></div>
        <div style="text-align:right"><div class="metric-val" id="mv-sav">—</div><div id="mp-sav"></div></div>
      </div>
      <div class="metric-row">
        <div><div class="metric-name">Rasio Utang</div><div class="metric-sub">Target: &lt;30% pemasukan · Consumer Financial Protection Bureau</div><div class="prog-bar"><div class="prog-fill fill-g" id="pb-dbt" style="width:0%"></div></div></div>
        <div style="text-align:right"><div class="metric-val" id="mv-dbt">—</div><div id="mp-dbt"></div></div>
      </div>
      <div class="metric-row">
        <div><div class="metric-name">Dana Darurat</div><div class="metric-sub">Target: 6–9 bulan pengeluaran · Vanguard Research</div><div class="prog-bar"><div class="prog-fill fill-g" id="pb-em" style="width:0%"></div></div></div>
        <div style="text-align:right"><div class="metric-val" id="mv-em">—</div><div id="mp-em"></div></div>
      </div>
      <div class="metric-row">
        <div><div class="metric-name">Rasio Hidup</div><div class="metric-sub">Target: &lt;80% pemasukan · Harvard Joint Center</div><div class="prog-bar"><div class="prog-fill fill-w" id="pb-lv" style="width:0%"></div></div></div>
        <div style="text-align:right"><div class="metric-val" id="mv-lv">—</div><div id="mp-lv"></div></div>
      </div>
      <div class="metric-row">
        <div><div class="metric-name">Persepuluhan</div><div class="metric-sub">Komitmen imanmu — tercatat, bukan dinilai</div></div>
        <div style="text-align:right"><div class="metric-val" id="mv-tth">—</div><span class="pill pill-neu">📖 Iman</span></div>
      </div>
    </div>
  </div>

  <!-- ===== TIPS ===== -->
  <div class="section" id="tab-tips">
    <div class="card" style="background:linear-gradient(135deg,var(--forest-dark),var(--forest));border:none">
      <div style="color:#fff">
        <div style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;margin-bottom:6px">🌳 Filosofi Pleninvest</div>
        <div style="font-size:13px;color:rgba(255,255,255,.8);font-weight:300;line-height:1.7">Seperti pohon yang bertumbuh perlahan tapi pasti — keuangan yang sehat bukan dibangun dalam semalam, tapi dirawat setiap hari dengan konsistensi, kejujuran, dan hikmat. Sebagai calon pendeta, ketenangan finansialmu adalah bagian dari panggilanmu.</div>
      </div>
    </div>
    <div id="tips-container"></div>
  </div>

  <!-- ===== SHEETS ===== -->
  <div class="section" id="tab-sheets">
    <div class="card" style="background:linear-gradient(135deg,var(--forest-dark),var(--forest-mid));border:none;color:#fff">
      <div style="font-family:'Syne',sans-serif;font-size:15px;font-weight:700;margin-bottom:6px">🔗 Sinkronisasi Real-Time ke Google Sheets</div>
      <div style="font-size:12px;color:rgba(255,255,255,.8);font-weight:300;line-height:1.7">Data tersimpan di Google Sheets milikmu — hanya kamu yang punya akses. Menggunakan Google Apps Script resmi. Tidak ada server pihak ketiga. <strong>Bahkan Pleninvest tidak bisa buka datamu tanpa izinmu.</strong></div>
    </div>
    <div class="card">
      <div class="card-title">⚙️ Status & Koneksi</div>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
        <div class="gs-dot" id="gs-dot2" style="width:12px;height:12px"></div>
        <span style="font-size:13px;font-weight:500" id="gs-status-text">Belum terhubung</span>
      </div>
      <div class="gs-input-row">
        <input type="text" id="gs-url" placeholder="Paste URL Google Apps Script di sini...">
        <button class="gs-connect-btn" onclick="connectSheets()">Hubungkan</button>
      </div>
      <div id="gs-msg"></div>
      <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap">
        <button class="btn btn-sage btn-sm" onclick="syncAll()">⬆ Sync Semua Data</button>
        <button class="btn btn-outline btn-sm" onclick="disconnectSheets()">Putus Koneksi</button>
      </div>
    </div>
    <div class="card">
      <div class="card-title">📋 Panduan Pemasangan (Sekali Seumur Hidup)</div>
      <div class="setup-step"><div class="step-num">1</div><div><div class="step-title">Buat Google Sheet Baru</div><div class="step-body">Buka <strong>sheets.google.com</strong> → klik "+ Blank" → beri nama <em>"Pleninvest - Ricky Atmoko"</em>.</div></div></div>
      <div class="setup-step"><div class="step-num">2</div><div><div class="step-title">Buka Apps Script</div><div class="step-body">Di Google Sheets: klik <strong>Extensions → Apps Script</strong>. Hapus semua kode yang ada, paste kode berikut:</div>
      <div class="code-block">const TABS = {
  transaksi: 'Transaksi',
  reimburse: 'Reimburse',
  aset: 'Diversifikasi',
  asuransi: 'Asuransi',
  tunjangan: 'Tunjangan'
};

function getOrCreateSheet(name, headers) {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  let s = ss.getSheetByName(name);
  if (!s) {
    s = ss.insertSheet(name);
    s.appendRow(headers);
    s.getRange(1,1,1,headers.length).setFontWeight('bold');
    s.setFrozenRows(1);
  }
  return s;
}

function doPost(e) {
  try {
    const d = JSON.parse(e.postData.contents);
    
    if (d.action === 'sync_all') {
      const txnS = getOrCreateSheet(TABS.transaksi,
        ['ID','Tanggal','Jenis','Kategori','Keterangan','Nominal','Benefit','Sync']);
      const rmbS = getOrCreateSheet(TABS.reimburse,
        ['ID','Tanggal','Kategori','Keterangan','Nominal','Status','Sync']);
      const asetS = getOrCreateSheet(TABS.aset,
        ['ID','Jenis','Nama','Tgl Beli','Harga Beli','Qty','Ticker','Catatan','Sync']);
      const insS = getOrCreateSheet(TABS.asuransi,
        ['ID','Nama','Jenis','Premi/Bln','Jatuh Tempo','Coverage','Sync']);
      const tnjS = getOrCreateSheet(TABS.tunjangan,
        ['ID','Nama','Jenis','Nominal/Bln','Catatan','Sync']);

      const now = new Date().toLocaleString('id-ID');
      
      function clearAndFill(sheet, rows, mapper) {
        if (sheet.getLastRow() > 1)
          sheet.deleteRows(2, sheet.getLastRow()-1);
        rows.forEach(r => sheet.appendRow([...mapper(r), now]));
      }
      
      clearAndFill(txnS, d.transactions||[], r =>
        [r.id,r.date,r.type,r.cat,r.note||'',r.amount,r.benefit||'']);
      clearAndFill(rmbS, d.reimburse||[], r =>
        [r.id,r.date,r.cat,r.note||'',r.amount,r.status]);
      clearAndFill(asetS, d.assets||[], r =>
        [r.id,r.type,r.name,r.date,r.buyPrice,r.qty,r.ticker||'',r.note||'']);
      clearAndFill(insS, d.insurance||[], r =>
        [r.id,r.name,r.type,r.premium,r.due||'',r.coverage||'']);
      clearAndFill(tnjS, d.tunjangan||[], r =>
        [r.id,r.name,r.type,r.amount,r.note||'']);

      return out({ok:true,msg:'Sync berhasil'});
    }
    return out({ok:false,msg:'Action tidak dikenal'});
  } catch(err) {
    return out({ok:false,error:err.message});
  }
}

function out(data) {
  return ContentService
    .createTextOutput(JSON.stringify(data))
    .setMimeType(ContentService.MimeType.JSON);
}

function doGet() {
  return out({ok:true,msg:'Pleninvest API aktif!'});
}</div></div></div>
      <div class="setup-step"><div class="step-num">3</div><div><div class="step-title">Deploy sebagai Web App</div><div class="step-body">Klik <strong>Deploy → New Deployment</strong> → pilih type <strong>Web app</strong>.<br>• Execute as: <strong>Me</strong><br>• Who has access: <strong>Only myself</strong><br>→ Klik <strong>Deploy</strong>, izinkan akses Google, lalu <strong>salin URL yang muncul</strong>.</div></div></div>
      <div class="setup-step"><div class="step-num">4</div><div><div class="step-title">Paste URL di atas & Hubungkan</div><div class="step-body">Tempel URL di kolom koneksi di atas → klik Hubungkan. Selesai! 🎉 Semua datamu akan sync otomatis.</div></div></div>
      <div class="setup-step"><div class="step-num">✓</div><div><div class="step-title">Keamanan Terjamin</div><div class="step-body">"Only myself" means only your Google account can access this endpoint. Even if the URL leaks, others cannot read or write your data without logging into your Google account.</div></div></div>
    </div>
  </div>

</div><!-- end container -->
<div class="footnote">🌳 Pleninvest by Ricky Atmoko · Data tersimpan lokal + Google Sheets (opsional) · Privasi 100% ada di tanganmu · Dibuat with ❤️ by Claude</div>
</div><!-- end app -->
@endsection
