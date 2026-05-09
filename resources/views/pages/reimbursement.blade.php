@extends('layouts.app')

@section('content')
  <!-- ===== REIMBURSE ===== -->
  <div class="section active" id="tab-reimburse">
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
        <select id="r-filter-cat" onchange="renderReimburse()" style="padding:7px 11px;border:1.5 solid var(--border);border-radius:var(--radius-sm);font-family:'Inter',sans-serif;font-size:12px;background:var(--cream);outline:none">
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
@endsection
