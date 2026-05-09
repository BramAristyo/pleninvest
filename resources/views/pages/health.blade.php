@extends('layouts.app')

@section('content')
  <!-- ===== KESEHATAN ===== -->
  <div class="section active" id="tab-kesehatan">
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
@endsection
