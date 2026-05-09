@extends('layouts.app')

@section('content')
  <!-- ===== ASURANSI ===== -->
  <div class="section active" id="tab-asuransi">
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
  </div>
@endsection
