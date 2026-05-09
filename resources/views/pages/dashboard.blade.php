@extends('layouts.app')

@section('content')
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
@endsection
