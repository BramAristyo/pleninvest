@extends('layouts.app')

@section('content')
  <!-- ===== SHEETS ===== -->
  <div class="section active" id="tab-sheets">
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
      <div class="setup-step"><div class="step-num">✓</div><div><div class="step-title">Keamanan Terjamin</div><div class="step-body">"Only myself" berarti hanya akun Google-mu yang bisa mengakses endpoint ini. Bahkan jika URL bocor, orang lain tidak bisa membaca atau menulis datamu tanpa login ke akun Google-mu.</div></div></div>
    </div>
  </div>
@endsection
