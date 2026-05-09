@extends('layouts.app')

@section('content')
  <!-- ===== DIVERSIFIKASI ===== -->
  <div class="section active" id="tab-divers">
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

    <!-- CICILAN EMAS -->
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
@endsection
