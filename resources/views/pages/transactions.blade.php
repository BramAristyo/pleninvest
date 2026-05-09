@extends('layouts.app')

@section('content')
  <!-- ===== CATAT ===== -->
  <div class="section active" id="tab-catat">
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
          <select id="txn-benefit" onchange="document.getElementById('benefit-note-wrap').style.display = this.value ? 'block' : 'none';">
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
@endsection
