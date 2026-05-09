@extends('layouts.app')

@section('content')
  <!-- ===== GRAFIK ===== -->
  <div class="section active" id="tab-grafik">
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
@endsection
