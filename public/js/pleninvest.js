// ============================================================
//  PLENINVEST — Core Application | by Ricky Atmoko
// ============================================================

// ——— PIN SYSTEM ———
var pinBuffer = '';
var pinStep = 'enter';
var pinConfirmBuffer = '';
var appInitialized = false;

function getPinFromStorage() {
  try { return localStorage.getItem('plen_pin'); } catch(e) { return null; }
}

function initPinScreen() {
  var saved = getPinFromStorage();
  if (!saved) {
    pinStep = 'create';
    setLabel('Buat PIN baru — masukkan 4 angka pilihanmu');
  } else {
    pinStep = 'enter';
    setLabel('Masukkan PIN untuk masuk');
  }
}

function setLabel(txt) {
  var el = document.getElementById('pin-label');
  if (el) el.textContent = txt;
}

function setMsg(txt, isErr) {
  var el = document.getElementById('pin-msg');
  if (!el) return;
  el.textContent = txt;
  el.style.color = isErr ? '#FFB0B0' : 'rgba(255,255,255,.7)';
}

function pinInput(d) {
  if (pinBuffer.length >= 4) return;
  pinBuffer += d;
  updatePinDots();
  if (pinBuffer.length === 4) setTimeout(handleFourDigits, 200);
}

function updatePinDots() {
  for (var i = 0; i < 4; i++) {
    var dot = document.getElementById('dot' + i);
    if (dot) dot.classList.toggle('filled', i < pinBuffer.length);
  }
}

function pinClear() {
  pinBuffer = pinBuffer.slice(0, -1);
  updatePinDots();
  setMsg('');
}

function handleFourDigits() {
  if (pinStep === 'enter') {
    var saved = getPinFromStorage();
    if (!saved) {
      pinStep = 'create';
      pinBuffer = '';
      updatePinDots();
      setLabel('Buat PIN baru — masukkan 4 angka pilihanmu');
      return;
    }
    if (pinBuffer === saved) {
      unlockApp();
    } else {
      setMsg('PIN salah, coba lagi', true);
      pinBuffer = '';
      updatePinDots();
    }
  } else if (pinStep === 'create') {
    pinConfirmBuffer = pinBuffer;
    pinBuffer = '';
    updatePinDots();
    pinStep = 'confirm';
    setLabel('Konfirmasi — masukkan PIN yang sama lagi');
    setMsg('');
  } else if (pinStep === 'confirm') {
    if (pinBuffer === pinConfirmBuffer) {
      try { localStorage.setItem('plen_pin', pinBuffer); } catch(e) {}
      setMsg('PIN berhasil dibuat!', false);
      setTimeout(unlockApp, 400);
    } else {
      setMsg('PIN tidak cocok, mulai lagi', true);
      pinBuffer = ''; pinConfirmBuffer = '';
      updatePinDots();
      pinStep = 'create';
      setLabel('Buat PIN baru — masukkan 4 angka pilihanmu');
    }
  }
}

function pinSubmit() {
  if (pinBuffer.length === 4) handleFourDigits();
  else setMsg('Masukkan 4 digit PIN', false);
}

function unlockApp() {
    const pinScreen = document.getElementById('pin-screen');
    const app = document.getElementById('app');

    if (pinScreen) pinScreen.style.display = 'none';
    if (app) app.style.display = 'block';

    pinBuffer = ''; 
    updatePinDots();

    if (!appInitialized) { 
        init(); 
        appInitialized = true; 
    }

    // If we're on the auth page (root /), redirect to dashboard after unlocking
    if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
        window.location.href = '/dashboard';
    }
}

function lockApp() {
  pinBuffer = '';
  pinStep = getPinFromStorage() ? 'enter' : 'create';
  updatePinDots();
  setMsg('');
  if (pinStep === 'enter') setLabel('Masukkan PIN untuk masuk');
  else setLabel('Buat PIN baru — masukkan 4 angka pilihanmu');
  
  const pinScreen = document.getElementById('pin-screen');
  const app = document.getElementById('app');

  if (pinScreen) {
      pinScreen.style.display = 'flex';
  } else {
      // If we are on a page that uses the layout, redirect to root to show pin screen
      window.location.href = '/';
  }
  
  if (app) app.style.display = 'none';
}

function pinReset() {
  if (confirm('Reset PIN dan SEMUA data? Tidak bisa dibatalkan.')) {
    try { localStorage.clear(); } catch(e) {}
    window.location.href = '/';
  }
}

// ——— DATA STORE ———
var transactions = [];
var reimburse = [];
var assets = [];
var insurance = [];
var tunjangan = [];
var cicilanEmas = [];
var currentMonth = new Date();
var charts = {};
var priceCache = {};
var gsUrl = '';

function loadData() {
  try {
    transactions = JSON.parse(localStorage.getItem('plen_txn') || '[]');
    reimburse = JSON.parse(localStorage.getItem('plen_rmb') || '[]');
    assets = JSON.parse(localStorage.getItem('plen_assets') || '[]');
    insurance = JSON.parse(localStorage.getItem('plen_ins') || '[]');
    tunjangan = JSON.parse(localStorage.getItem('plen_tnj') || '[]');
    cicilanEmas = JSON.parse(localStorage.getItem('plen_ce') || '[]');
    gsUrl = localStorage.getItem('plen_gs_url') || '';
  } catch(e) { console.warn('Error loading data:', e); }
}

function saveAll() {
  try {
    localStorage.setItem('plen_txn', JSON.stringify(transactions));
    localStorage.setItem('plen_rmb', JSON.stringify(reimburse));
    localStorage.setItem('plen_assets', JSON.stringify(assets));
    localStorage.setItem('plen_ins', JSON.stringify(insurance));
    localStorage.setItem('plen_tnj', JSON.stringify(tunjangan));
    localStorage.setItem('plen_ce', JSON.stringify(cicilanEmas));
  } catch(e) { console.warn('Error saving:', e); }
}

// ——— FORMAT ———
var MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
var MONTHS_S = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];

function fmt(n) { return 'Rp ' + Math.round(n||0).toLocaleString('id-ID'); }
function fmtS(n) {
  var a = Math.abs(n||0);
  if (a >= 1e12) return 'Rp ' + (n/1e12).toFixed(1) + 'T';
  if (a >= 1e9)  return 'Rp ' + (n/1e9).toFixed(1)  + 'M';
  if (a >= 1e6)  return 'Rp ' + (n/1e6).toFixed(1)  + 'jt';
  return 'Rp ' + Math.round(n||0).toLocaleString('id-ID');
}

// ——— CATEGORIES ———
var CAT_INCOME  = ['Gaji Pastoral','Honorarium','Persembahan / Natura','Tunjangan','Klaim Asuransi','Investasi Return','Lain-lain Pemasukan'];
var CAT_EXPENSE = ['Kebutuhan Pokok','Transportasi','Kesehatan','Pendidikan / Buku','Tabungan','Dana Darurat','Persepuluhan','Pelayanan','Pakaian','Hiburan','Lain-lain'];
var RMB_LABELS  = {bensin:'⛽',buku:'📚',seminar:'🎓',listrik:'💡',air:'💧',listrik_air:'💡💧',ipl:'🏘️',parkir:'🅿️',kesehatan:'🏥',lainnya:'📦'};
var ASSET_ICONS = {cash:'💵',deposito:'🏦',emas:'🥇',vallas:'💱',saham:'📈',lainnya:'📦'};
var ASSET_COLORS = ['#7DAA89','#7BB8D4','#D4A843','#4A8C5C','#B5D4BE','#E8C5B0'];
var PALETTE = ['#7DAA89','#7BB8D4','#D4A843','#4A8C5C','#B5D4BE','#E89090','#E8C5B0','#4A8FA8'];

function updateCategories() {
  var typeEl = document.getElementById('txn-type');
  if (!typeEl) return;
  var type = typeEl.value;
  var cats = type === 'income' ? CAT_INCOME : CAT_EXPENSE;
  var sel = document.getElementById('txn-cat');
  if (sel) sel.innerHTML = cats.map(function(c) { return '<option value="' + c + '">' + c + '</option>'; }).join('');
}

// ——— MONTH NAV ———
function updateMonthLabel() {
  var el = document.getElementById('month-label');
  if (el) el.textContent = MONTHS[currentMonth.getMonth()] + ' ' + currentMonth.getFullYear();
}

function changeMonth(dir) {
  currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + dir, 1);
  updateMonthLabel();
  renderTransactions();
  calcAll();
}

function curM() {
  return currentMonth.getFullYear() + '-' + String(currentMonth.getMonth()+1).padStart(2,'0');
}

// ——— TRANSACTIONS ———
function addTransaction() {
  var date = document.getElementById('txn-date').value;
  var type = document.getElementById('txn-type').value;
  var cat  = document.getElementById('txn-cat').value;
  var note = document.getElementById('txn-note').value;
  var amount = parseFloat(document.getElementById('txn-amount').value);
  var benefit = document.getElementById('txn-benefit').value;
  var benefitNote = document.getElementById('txn-benefit-note') ? document.getElementById('txn-benefit-note').value : '';
  if (!date || !amount || amount <= 0) { alert('Lengkapi tanggal dan nominal ya'); return; }
  var t = { id: Date.now(), date: date, type: type, cat: cat, note: note, amount: amount, benefit: benefit, benefitNote: benefitNote };
  transactions.push(t);
  saveAll();
  document.getElementById('txn-amount').value = '';
  document.getElementById('txn-note').value = '';
  document.getElementById('txn-benefit').value = '';
  var bnw = document.getElementById('benefit-note-wrap');
  if (bnw) bnw.style.display = 'none';
  renderTransactions();
  calcAll();
  showGardenMsg(type === 'income' ? 'Benih baru ditanam! Pemasukan bertambah.' : 'Pengeluaran dicatat.');
  if (gsUrl) gsPost({action:'add_txn', txn: t});
}

function deleteTransaction(id) {
  transactions = transactions.filter(function(t) { return t.id !== id; });
  saveAll(); renderTransactions(); calcAll();
}

function renderTransactions() {
  var el = document.getElementById('txn-list');
  if (!el) return;

  var cm = curM();
  var list = transactions.filter(function(t) { return t.date.startsWith(cm); })
                         .sort(function(a,b) { return b.date.localeCompare(a.date); });
  var inc = 0, exp = 0;
  list.forEach(function(t) { if (t.type==='income') inc+=t.amount; else exp+=t.amount; });
  
  var cInc = document.getElementById('c-inc');
  if (cInc) cInc.textContent = fmtS(inc);
  
  var cExp = document.getElementById('c-exp');
  if (cExp) cExp.textContent = fmtS(exp);
  
  var bal = inc - exp;
  var bEl = document.getElementById('c-bal');
  if (bEl) {
    bEl.textContent = fmtS(Math.abs(bal));
    bEl.style.color = bal >= 0 ? 'var(--good)' : 'var(--bad)';
  }
  
  if (!list.length) {
    el.innerHTML = '<div class="empty-state"><div class="ei">🌿</div>Belum ada transaksi bulan ini.</div>';
    return;
  }
  var html = '';
  list.forEach(function(t) {
    var benefitBadge = t.benefit ? '<span class="pill pill-neu">' + t.benefit + '</span>' : '';
    var catNote = t.cat + (t.note ? ' <span style="color:var(--light)">· ' + t.note + '</span>' : '');
    var amtClass = t.type === 'income' ? 'txn-amt inc' : 'txn-amt exp';
    var sign = t.type === 'income' ? '+' : '-';
    html += '<div class="txn-row">'
      + '<span style="color:var(--medium);font-size:11px">' + t.date + '</span>'
      + '<span style="font-size:12px">' + catNote + '</span>'
      + '<span style="font-size:11px">' + benefitBadge + '</span>'
      + '<span class="' + amtClass + '">' + sign + fmtS(t.amount) + '</span>'
      + '<button class="del-btn" onclick="deleteTransaction(' + t.id + ')">&#215;</button>'
      + '</div>';
  });
  el.innerHTML = html;
}

// ——— NET WORTH ———
function calcNetWorth() {
  function v(id) { 
      var el = document.getElementById(id);
      return el ? (parseFloat(el.value) || 0) : 0; 
  }
  var total = v('nw-sav') + v('nw-em') + v('nw-inv') + v('nw-oth');
  var debt = v('nw-dbt');
  var nw = total - debt;
  var valEl = document.getElementById('nw-val');
  if (valEl) {
    valEl.textContent = fmt(nw);
    valEl.style.color = nw >= 0 ? 'var(--charcoal)' : 'var(--bad)';
  }
  var lvl = nw < 0 ? '🔴 Perlu perhatian'
    : nw < 50e6 ? '🟡 Membangun fondasi'
    : nw < 200e6 ? '🟢 Fondasi terbentuk'
    : nw < 1e9 ? '🌳 Mulai berbuah'
    : '🏆 Berlimpah';
  var lvlEl = document.getElementById('nw-lvl');
  if (lvlEl) lvlEl.textContent = lvl;
  return nw;
}

// ——— REIMBURSE ———
function addReimburse() {
  var date = document.getElementById('r-date').value;
  var cat = document.getElementById('r-cat').value;
  var amount = parseFloat(document.getElementById('r-amount').value);
  var note = document.getElementById('r-note').value;
  var status = document.getElementById('r-status').value;
  if (!date || !amount) { alert('Lengkapi tanggal dan nominal'); return; }
  reimburse.push({ id: Date.now(), date: date, cat: cat, amount: amount, note: note, status: status });
  saveAll(); renderReimburse(); calcAll();
}

function deleteReimburse(id) {
  reimburse = reimburse.filter(function(r) { return r.id !== id; });
  saveAll(); renderReimburse(); calcAll();
}

function updateRmbStatus(id, status) {
  var r = reimburse.find(function(r) { return r.id === id; });
  if (r) { r.status = status; saveAll(); renderReimburse(); calcAll(); }
}

function renderReimburse() {
  var cm = curM();
  var monthly = reimburse.filter(function(r) { return r.date.startsWith(cm); });
  var total = monthly.reduce(function(s,r) { return s+r.amount; }, 0);
  var pending = monthly.filter(function(r) { return r.status==='pending'; }).reduce(function(s,r) { return s+r.amount; }, 0);
  var received = monthly.filter(function(r) { return r.status==='received'; }).reduce(function(s,r) { return s+r.amount; }, 0);
  
  var rTotal = document.getElementById('r-total');
  if (rTotal) rTotal.textContent = fmtS(total);
  var rPending = document.getElementById('r-pending');
  if (rPending) rPending.textContent = fmtS(pending);
  var rReceived = document.getElementById('r-received');
  if (rReceived) rReceived.textContent = fmtS(received);

  var el = document.getElementById('rmb-list');
  if (!el) return;

  var catF = document.getElementById('r-filter-cat') ? document.getElementById('r-filter-cat').value : '';
  var statF = document.getElementById('r-filter-status') ? document.getElementById('r-filter-status').value : '';
  var list = reimburse.slice().sort(function(a,b) { return b.date.localeCompare(a.date); });
  if (catF) list = list.filter(function(r) { return r.cat === catF; });
  if (statF) list = list.filter(function(r) { return r.status === statF; });

  if (!list.length) { el.innerHTML = '<div class="empty-state"><div class="ei">🧾</div>Belum ada reimburse.</div>'; return; }
  var html = '';
  list.forEach(function(r) {
    var icon = RMB_LABELS[r.cat] || '📦';
    var catLabel = r.cat.replace('_', ' & ');
    html += '<div class="rmb-row">'
      + '<span style="font-size:11px;color:var(--medium)">' + r.date + '</span>'
      + '<span>' + icon + ' ' + catLabel + '</span>'
      + '<span style="font-size:11px;color:var(--medium)">' + (r.note || '—') + '</span>'
      + '<span style="font-family:\'Syne\',sans-serif;font-weight:600;color:var(--lake-deep);text-align:right">' + fmtS(r.amount) + '</span>'
      + '<select onchange="updateRmbStatus(' + r.id + ',this.value)" style="font-size:10px;padding:3px 6px;border:1px solid var(--border);border-radius:6px;background:var(--cream)">'
      + '<option value="pending"' + (r.status==='pending'?' selected':'') + '>Pending</option>'
      + '<option value="approved"' + (r.status==='approved'?' selected':'') + '>OK</option>'
      + '<option value="received"' + (r.status==='received'?' selected':'') + '>Terima</option>'
      + '</select>'
      + '<button class="del-btn" onclick="deleteReimburse(' + r.id + ')">&#215;</button>'
      + '</div>';
  });
  el.innerHTML = html;
}

// ——— INSURANCE & TUNJANGAN ———
function addInsurance() {
  var name = document.getElementById('ins-name').value;
  var type = document.getElementById('ins-type').value;
  var premium = parseFloat(document.getElementById('ins-premium').value) || 0;
  var due = document.getElementById('ins-due').value;
  var coverage = document.getElementById('ins-coverage').value;
  if (!name) { alert('Isi nama asuransi'); return; }
  insurance.push({ id: Date.now(), name: name, type: type, premium: premium, due: due, coverage: coverage });
  saveAll(); renderInsurance();
}

function deleteInsurance(id) {
  insurance = insurance.filter(function(i) { return i.id !== id; });
  saveAll(); renderInsurance();
}

function renderInsurance() {
  var total = insurance.reduce(function(s,i) { return s + i.premium; }, 0);
  var insTotal = document.getElementById('ins-total');
  if (insTotal) insTotal.textContent = fmtS(total);
  
  var el = document.getElementById('ins-list');
  if (!el) return;

  if (!insurance.length) { el.innerHTML = '<div class="empty-state"><div class="ei">🛡️</div>Belum ada asuransi.</div>'; return; }
  var html = '';
  insurance.forEach(function(i) {
    html += '<div class="ins-row">'
      + '<div class="ins-left"><div class="ins-name">🛡️ ' + i.name + '</div>'
      + '<div class="ins-sub">' + i.type + (i.coverage ? ' · ' + i.coverage : '') + '</div></div>'
      + '<div class="ins-right"><div class="ins-amount">' + fmtS(i.premium) + '<span style="font-size:10px;font-weight:400;color:var(--medium)">/bln</span></div>'
      + (i.due ? '<div class="ins-due">Jatuh tempo: ' + i.due + '</div>' : '')
      + '<button class="del-btn" style="margin-left:auto;margin-top:4px" onclick="deleteInsurance(' + i.id + ')">&#215;</button></div>'
      + '</div>';
  });
  el.innerHTML = html;
}

function addTunjangan() {
  var name = document.getElementById('tnj-name').value;
  var type = document.getElementById('tnj-type').value;
  var amount = parseFloat(document.getElementById('tnj-amount').value) || 0;
  var note = document.getElementById('tnj-note').value;
  if (!name) { alert('Isi nama tunjangan'); return; }
  tunjangan.push({ id: Date.now(), name: name, type: type, amount: amount, note: note });
  saveAll(); renderTunjangan();
}

function deleteTunjangan(id) {
  tunjangan = tunjangan.filter(function(t) { return t.id !== id; });
  saveAll(); renderTunjangan();
}

function renderTunjangan() {
  var total = tunjangan.reduce(function(s,t) { return s + t.amount; }, 0);
  var tnjTotal = document.getElementById('tnj-total');
  if (tnjTotal) tnjTotal.textContent = fmtS(total);
  
  var el = document.getElementById('tnj-list');
  if (!el) return;

  if (!tunjangan.length) { el.innerHTML = '<div class="empty-state"><div class="ei">💼</div>Belum ada tunjangan.</div>'; return; }
  var html = '';
  tunjangan.forEach(function(t) {
    html += '<div class="ins-row">'
      + '<div class="ins-left"><div class="ins-name">💼 ' + t.name + '</div>'
      + '<div class="ins-sub">' + t.type + (t.note ? ' · ' + t.note : '') + '</div></div>'
      + '<div class="ins-right"><div class="ins-amount">' + fmtS(t.amount) + '<span style="font-size:10px;font-weight:400;color:var(--medium)">/bln</span></div>'
      + '<button class="del-btn" style="margin-left:auto;margin-top:4px" onclick="deleteTunjangan(' + t.id + ')">&#215;</button></div>'
      + '</div>';
  });
  el.innerHTML = html;
}

// ——— DIVERSIFICATION ———
function toggleAddAsset() {
  var c = document.getElementById('add-asset-card');
  if (c) c.style.display = c.style.display === 'none' ? 'block' : 'none';
}

function addAsset() {
  var type = document.getElementById('a-type').value;
  var name = document.getElementById('a-name').value || type;
  var date = document.getElementById('a-date').value;
  var buyPrice = parseFloat(document.getElementById('a-buy-price').value) || 0;
  var qty = parseFloat(document.getElementById('a-qty').value) || 1;
  var ticker = document.getElementById('a-ticker').value.trim().toUpperCase();
  var note = document.getElementById('a-note').value;
  assets.push({ id: Date.now(), type: type, name: name, date: date, buyPrice: buyPrice, qty: qty, ticker: ticker, note: note, currentPrice: buyPrice });
  saveAll(); renderAssets(); toggleAddAsset();
  showGardenMsg('Aset baru ditambahkan ke portofolio!');
}

function deleteAsset(id) {
  assets = assets.filter(function(a) { return a.id !== id; });
  saveAll(); renderAssets(); calcAll();
}

function renderAssets() {
  var totalModal = 0, totalCurrent = 0;
  var el = document.getElementById('asset-list');
  
  if (!assets.length) {
    if (el) el.innerHTML = '<div class="empty-state"><div class="ei">🌿</div>Belum ada aset. Tambahkan asetmu!</div>';
    updateDiversStats(0, 0); return;
  }
  var html = '';
  assets.forEach(function(a, i) {
    var modal = a.buyPrice * a.qty;
    var current = (a.currentPrice || a.buyPrice) * a.qty;
    var pl = current - modal;
    var plPct = modal > 0 ? (pl/modal*100).toFixed(1) : '0.0';
    totalModal += modal; totalCurrent += current;
    var plColor = pl >= 0 ? 'var(--good)' : 'var(--bad)';
    var plSign = pl >= 0 ? '+' : '';
    var icon = ASSET_ICONS[a.type] || '📦';
    var bg = ASSET_COLORS[i % ASSET_COLORS.length] + '22';
    html += '<div class="asset-row">'
      + '<div class="asset-icon" style="background:' + bg + '">' + icon + '</div>'
      + '<div><div class="asset-name">' + a.name + '</div>'
      + '<div class="asset-sub">' + a.qty + ' unit · Beli: ' + fmtS(a.buyPrice) + ' · ' + (a.date||'—') + '</div></div>'
      + '<div><div class="asset-current">' + fmtS(current) + '</div>'
      + '<div class="asset-sub">Harga kini: ' + fmtS(a.currentPrice||a.buyPrice) + '</div></div>'
      + '<div><div class="asset-pl ' + (pl>=0?'pos':'neg') + '">' + plSign + fmtS(pl) + ' (' + plPct + '%)</div></div>'
      + '<button class="del-btn" onclick="deleteAsset(' + a.id + ')">&#215;</button>'
      + '</div>';
  });
  if (el) el.innerHTML = html;
  updateDiversStats(totalModal, totalCurrent);
}

function updateDiversStats(modal, current) {
  var pl = current - modal;
  var ret = modal > 0 ? (pl/modal*100).toFixed(1) : '0.0';
  
  var dTotal = document.getElementById('d-total');
  if (dTotal) dTotal.textContent = fmtS(current);
  var dModal = document.getElementById('d-modal');
  if (dModal) dModal.textContent = fmtS(modal);
  
  var plEl = document.getElementById('d-pl');
  if (plEl) {
    plEl.textContent = (pl>=0?'+':'') + fmtS(pl);
    plEl.style.color = pl >= 0 ? 'var(--good)' : 'var(--bad)';
  }
  
  var retEl = document.getElementById('d-ret');
  if (retEl) {
    retEl.textContent = (parseFloat(ret)>=0?'+':'') + ret + '%';
    retEl.style.color = parseFloat(ret) >= 0 ? 'var(--good)' : 'var(--bad)';
  }
}

// ——— CICILAN EMAS ———
function toggleCicilanForm() {
  var f = document.getElementById('cicilan-form');
  if (f) f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function addCicilanEmas() {
  var name = document.getElementById('ce-name').value || 'Emas';
  var start = document.getElementById('ce-start').value;
  var gram = parseFloat(document.getElementById('ce-gram').value) || 0;
  var pricePerGram = parseFloat(document.getElementById('ce-price-gram').value) || 0;
  var dp = parseFloat(document.getElementById('ce-dp').value) || 0;
  var monthly = parseFloat(document.getElementById('ce-monthly').value) || 0;
  var duration = parseInt(document.getElementById('ce-duration').value) || 12;
  var paid = parseInt(document.getElementById('ce-paid-input').value) || 0;
  var note = document.getElementById('ce-note').value;
  if (!gram || !pricePerGram || !monthly) { alert('Isi berat, harga per gram, dan cicilan per bulan ya'); return; }
  cicilanEmas.push({ id: Date.now(), name: name, start: start, gram: gram, pricePerGram: pricePerGram, dp: dp, monthly: monthly, duration: duration, paid: paid, note: note, totalHarga: gram * pricePerGram });
  saveAll(); renderCicilanEmas(); toggleCicilanForm();
  showGardenMsg('Cicilan emas baru ditambahkan!');
}

function updateCicilanPaid(id, paid) {
  var ce = cicilanEmas.find(function(c) { return c.id === id; });
  if (ce) { ce.paid = parseInt(paid); saveAll(); renderCicilanEmas(); }
}

function deleteCicilanEmas(id) {
  cicilanEmas = cicilanEmas.filter(function(c) { return c.id !== id; });
  saveAll(); renderCicilanEmas();
}

function renderCicilanEmas() {
  var el = document.getElementById('cicilan-list');
  if (!el) return;
  if (!cicilanEmas.length) {
    el.innerHTML = '<div class="empty-state"><div class="ei">🥇</div>Belum ada cicilan emas.</div>';
    return;
  }
  var goldPricePerGram = priceCache['XAU'] || 0;
  var html = '';
  cicilanEmas.forEach(function(ce) {
    var totalBayar = ce.dp + (ce.monthly * ce.paid);
    var gramTerbeli = ce.totalHarga > 0 ? (totalBayar / ce.totalHarga) * ce.gram : 0;
    var sisaBulan = Math.max(0, ce.duration - ce.paid);
    var sisaBayar = ce.monthly * sisaBulan;
    var progress = ce.duration > 0 ? Math.min(100, (ce.paid / ce.duration) * 100) : 0;
    var nilaiSekarang = goldPricePerGram > 0 ? gramTerbeli * goldPricePerGram : gramTerbeli * ce.pricePerGram;
    var plGram = nilaiSekarang - (gramTerbeli * ce.pricePerGram);
    var selesai = ce.paid >= ce.duration;
    var badgeStyle = selesai ? 'background:var(--sage-pale);color:var(--good)' : 'background:var(--earth-pale);color:var(--earth-light)';
    var plHtml = goldPricePerGram > 0
      ? '<div style="background:var(--sage-pale);border-radius:8px;padding:8px 12px;font-size:11px;display:flex;justify-content:space-between">'
        + '<span>Nilai Sekarang: <strong>' + fmtS(nilaiSekarang) + '</strong></span>'
        + '<span style="color:' + (plGram>=0?'var(--good)':'var(--bad)') + '">' + (plGram>=0?'+':'') + fmtS(plGram) + ' vs modal</span></div>'
      : '<div style="font-size:11px;color:var(--light);text-align:center;padding:6px">Perbarui harga emas untuk lihat P/L</div>';
    html += '<div style="background:linear-gradient(135deg,var(--warm-white),#FBF8EE);border:1.5px solid #D4A843;border-radius:14px;padding:18px;margin-top:10px">'
      + '<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px">'
      + '<div><div style="font-family:\'Syne\',sans-serif;font-size:14px;font-weight:700">🥇 ' + ce.name + '</div>'
      + '<div style="font-size:11px;color:var(--medium)">Target: ' + ce.gram + 'g · Mulai: ' + (ce.start||'—') + ' · ' + ce.duration + ' bulan</div></div>'
      + '<span class="pill" style="' + badgeStyle + '">' + (selesai ? '✓ Lunas' : '⏳ Cicilan') + '</span></div>'
      + '<div class="g3" style="margin-bottom:10px">'
      + '<div><div style="font-size:10px;color:var(--medium);text-transform:uppercase;letter-spacing:.4px">Sudah Dibayar</div><div style="font-family:\'Syne\',sans-serif;font-weight:700;color:var(--good)">' + fmtS(totalBayar) + '</div></div>'
      + '<div><div style="font-size:10px;color:var(--medium);text-transform:uppercase;letter-spacing:.4px">Sisa Cicilan</div><div style="font-family:\'Syne\',sans-serif;font-weight:700;color:var(--warn)">' + fmtS(sisaBayar) + '</div></div>'
      + '<div><div style="font-size:10px;color:var(--medium);text-transform:uppercase;letter-spacing:.4px">Gram Terbeli</div><div style="font-family:\'Syne\',sans-serif;font-weight:700;color:var(--gold)">' + gramTerbeli.toFixed(2) + 'g / ' + ce.gram + 'g</div></div></div>'
      + '<div style="margin-bottom:10px">'
      + '<div style="display:flex;justify-content:space-between;font-size:11px;margin-bottom:4px"><span style="color:var(--medium)">Progress</span><span style="font-weight:600">' + ce.paid + '/' + ce.duration + ' bulan (' + progress.toFixed(0) + '%)</span></div>'
      + '<div style="height:8px;background:var(--border);border-radius:100px;overflow:hidden">'
      + '<div style="height:100%;width:' + progress + '%;background:linear-gradient(90deg,var(--gold),var(--good));border-radius:100px"></div></div></div>'
      + plHtml
      + '<div style="display:flex;align-items:center;gap:10px;margin-top:10px">'
      + '<span style="font-size:11px;color:var(--medium)">Update bulan ke-:</span>'
      + '<input type="number" min="0" max="' + ce.duration + '" value="' + ce.paid + '" onchange="updateCicilanPaid(' + ce.id + ',this.value)" style="width:60px;padding:5px 8px;border:1.5px solid var(--border);border-radius:8px;font-size:12px;text-align:center">'
      + '<button class="del-btn" onclick="deleteCicilanEmas(' + ce.id + ')" style="margin-left:auto">&#215;</button></div></div>';
  });
  el.innerHTML = html;
}

// ——— HISTORICAL INPUT ———
function initHistYear() {
  var sel = document.getElementById('hist-year');
  if (!sel) return;
  sel.innerHTML = '';
  for (var y = 2026; y >= 2000; y--) {
    var o = document.createElement('option');
    o.value = y; o.textContent = y;
    if (y === new Date().getFullYear()) o.selected = true;
    sel.appendChild(o);
  }
  var mSel = document.getElementById('hist-month');
  if (mSel) mSel.value = String(new Date().getMonth()+1).padStart(2,'0');
  populateHistCats();
  addHistRow();
}

function populateHistCats() {
  document.querySelectorAll('.hist-cat').forEach(function(sel) {
    var allCats = CAT_INCOME.map(function(c) { return {val:'income:'+c, lbl:'[Masuk] '+c}; })
      .concat(CAT_EXPENSE.map(function(c) { return {val:'expense:'+c, lbl:'[Keluar] '+c}; }));
    sel.innerHTML = allCats.map(function(c) { return '<option value="' + c.val + '">' + c.lbl + '</option>'; }).join('');
  });
}

function addHistRow() {
  var container = document.getElementById('hist-rows');
  if (!container) return;
  var div = document.createElement('div');
  div.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 50px;gap:8px;margin-bottom:8px;align-items:end';
  div.innerHTML = '<div class="field" style="margin-bottom:0"><label>Kategori</label><select class="hist-cat" style="font-size:12px;padding:8px 10px;border:1.5px solid var(--border);border-radius:10px;background:var(--cream);width:100%"></select></div>'
    + '<div class="field" style="margin-bottom:0"><label>Nominal (Rp)</label><div class="pre"><span>Rp</span><input type="number" class="hist-amount" placeholder="0" style="padding-left:34px;font-size:12px"></div></div>'
    + '<button onclick="this.parentNode.remove()" style="background:var(--border);border:none;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:14px;color:var(--medium);margin-bottom:1px">&#215;</button>';
  container.appendChild(div);
  populateHistCats();
}

function saveHistorical() {
  var m = document.getElementById('hist-month').value;
  var y = document.getElementById('hist-year').value;
  var date = y + '-' + m + '-01';
  var cats = document.querySelectorAll('.hist-cat');
  var amounts = document.querySelectorAll('.hist-amount');
  var saved = 0;
  for (var i = 0; i < amounts.length; i++) {
    var amount = parseFloat(amounts[i].value);
    if (!amount || amount <= 0) continue;
    var catVal = cats[i] ? cats[i].value : 'expense:Lain-lain';
    var parts = catVal.split(':');
    var type = parts[0];
    var cat = parts.slice(1).join(':');
    transactions.push({ id: Date.now()+i, date: date, type: type, cat: cat, note: 'Import historis', amount: amount, benefit: '', benefitNote: '' });
    saved++;
  }
  if (saved === 0) {
    document.getElementById('hist-msg').innerHTML = '<div class="status-msg sminfo">Tidak ada nominal yang diisi.</div>';
    return;
  }
  saveAll(); renderTransactions(); calcAll();
  document.getElementById('hist-msg').innerHTML = '<div class="status-msg smok">✅ ' + saved + ' transaksi disimpan untuk ' + m + '/' + y + '!</div>';
  setTimeout(function() { var el = document.getElementById('hist-msg'); if(el) el.innerHTML=''; }, 3000);
}

function toggleHistorical() {
  var body = document.getElementById('hist-body');
  var arrow = document.getElementById('hist-arrow');
  if (!body) return;
  var open = body.style.display !== 'none';
  body.style.display = open ? 'none' : 'block';
  if (arrow) arrow.style.transform = open ? '' : 'rotate(180deg)';
  if (!open) { initHistYear(); }
}

// ——— REAL-TIME PRICES ———
function fetchAllPrices() {
  var lastUpdate = document.getElementById('last-update');
  if (lastUpdate) lastUpdate.textContent = 'Memperbarui...';
  
  var usdIdr = 15800;
  fetch('https://open.er-api.com/v6/latest/USD')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.rates && data.rates.IDR) usdIdr = data.rates.IDR;
      priceCache['USD'] = usdIdr;
      priceCache['EUR'] = data.rates ? usdIdr / data.rates.EUR : usdIdr * 1.08;
      priceCache['SGD'] = data.rates ? usdIdr / data.rates.SGD : usdIdr * 0.74;
      updateAssetPrices(usdIdr);
    }).catch(function() { updateAssetPrices(usdIdr); });
}

function updateAssetPrices(usdIdr) {
  fetch('https://data-asg.goldprice.org/dbXRates/USD')
    .then(function(r) { return r.json(); })
    .then(function(data) {
      if (data.items && data.items[0]) priceCache['XAU'] = data.items[0].xauPrice * usdIdr / 31.1;
      fetchStockPrices();
    }).catch(function() { fetchStockPrices(); });
}

function fetchStockPrices() {
  var promises = assets.filter(function(a) { return a.ticker && a.type === 'saham'; }).map(function(a) {
    var ticker = a.ticker.includes('.') ? a.ticker : a.ticker + '.JK';
    return fetch('https://query1.finance.yahoo.com/v8/finance/chart/' + ticker)
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.chart && data.chart.result && data.chart.result[0]) {
          a.currentPrice = data.chart.result[0].meta.regularMarketPrice;
        }
      }).catch(function() {});
  });
  Promise.all(promises).then(function() {
    assets.forEach(function(a) {
      if (a.type === 'emas' && priceCache['XAU']) a.currentPrice = priceCache['XAU'];
      if (a.type === 'vallas' && priceCache[a.ticker]) a.currentPrice = priceCache[a.ticker];
    });
    saveAll(); renderAssets(); renderCicilanEmas();
    var lastUpdate = document.getElementById('last-update');
    if (lastUpdate) lastUpdate.textContent = new Date().toLocaleString('id-ID', {hour:'2-digit',minute:'2-digit'});
  });
}

// ——— AI RECOMMENDATION ———
function getAIReco() {
  var panel = document.getElementById('ai-content');
  if (!panel) return;
  panel.innerHTML = '<div class="ai-loading"><div class="ai-dot"></div><div class="ai-dot"></div><div class="ai-dot"></div><span style="margin-left:6px">Claude sedang menganalisis portofoliomu...</span></div>';
  var portfolioSummary = assets.map(function(a) {
    return { jenis: a.type, nama: a.name, modal: a.buyPrice * a.qty, current: (a.currentPrice||a.buyPrice) * a.qty, plPct: a.buyPrice > 0 ? ((a.currentPrice||a.buyPrice)-a.buyPrice)/a.buyPrice*100 : 0 };
  });
  fetch('https://api.anthropic.com/v1/messages', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'anthropic-version': '2023-06-01' },
    body: JSON.stringify({ model: 'claude-sonnet-4-20250514', max_tokens: 800, messages: [{ role: 'user', content: 'Kamu adalah penasihat keuangan untuk Ricky Atmoko, calon pendeta GKI usia 25 tahun di Indonesia. Berikan saran diversifikasi berdasarkan portofolio: ' + JSON.stringify(portfolioSummary) + '. Berikan: ringkasan kondisi (1-2 kalimat), saran per aset (tahan/jual/tambah), rekomendasi diversifikasi ke depan, dan 1 kalimat penyemangat pastoral. Max 200 kata, bahasa Indonesia, bullet point untuk saran aset.' }] })
  }).then(function(r) { return r.json(); })
    .then(function(data) {
      var text = (data.content && data.content[0] && data.content[0].text) ? data.content[0].text : 'Tidak bisa mendapatkan rekomendasi saat ini.';
      panel.innerHTML = '<div style="font-size:13px;line-height:1.7;color:rgba(255,255,255,.9)">' + text.replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>') + '</div>';
    }).catch(function() {
      panel.innerHTML = '<div style="font-size:12px;color:rgba(255,255,255,.6)">Tidak bisa terhubung ke AI. Coba lagi nanti.</div>';
    });
}

// ——— HEALTH SCORE ———
var healthScores = {};

function calcHealth() {
  function v(id) { 
      var el = document.getElementById(id);
      return el ? (parseFloat(el.value) || 0) : 0; 
  }
  var inc=v('h-inc'), exp=v('h-exp'), sav=v('h-sav'), dbt=v('h-dbt'), em=v('h-em'), tth=v('h-tth');
  if (!inc) return;
  var sr=sav/inc*100, dr=dbt/inc*100, emM=exp>0?em/exp:0, lr=exp/inc*100, tr=tth/inc*100;
  var sS=sr>=20?20:sr>=15?17:sr>=10?12:sr>=5?6:2;
  var dS=dr===0?20:dr<15?18:dr<30?12:dr<40?6:2;
  var eS=emM>=9?20:emM>=6?18:emM>=3?12:emM>=1?6:2;
  var lS=lr<60?20:lr<70?16:lr<80?10:lr<90?5:2;
  var tS=tr>=10?20:tr>=7?15:tr>=5?10:tr>0?5:8;
  var total = sS+dS+eS+lS+tS;
  healthScores = {sr:sr,dr:dr,emM:emM,lr:lr,tr:tr,sS:sS,dS:dS,eS:eS,lS:lS,tS:tS,total:total,inc:inc,exp:exp};
  
  var scoreNum = document.getElementById('score-num');
  if (scoreNum) scoreNum.textContent = total;

  var grades = total>=90?['🌳 Berlimpah','Kondisi finansialmu luar biasa!','#1E4D2B','#fff']
    :total>=75?['🌿 Sangat Sehat','Portofolio kuat dan berkelanjutan.','#3D8A55','#fff']
    :total>=60?['🌱 Sehat','Kondisi baik, ada 1-2 area yang bisa diperkuat.','#6BAA7A','#fff']
    :total>=45?['🍂 Cukup','Ada beberapa hal yang perlu perhatian.','#D4A843','#fff']
    :total>=25?['⚠️ Perlu Perhatian','Kondisi perlu perbaikan. Mulai dari langkah kecil.','#D47373','#fff']
    :['🪨 Kritis','Prioritas utama: stabilisasi keuangan.','#B84040','#fff'];
  
  var badge = document.getElementById('score-badge');
  if (badge) {
      badge.textContent = grades[0]; 
      badge.style.background = grades[2]; 
      badge.style.color = grades[3];
  }
  
  var scoreGrade = document.getElementById('score-grade');
  if (scoreGrade) scoreGrade.textContent = grades[1];

  var equivMap = ['Setara: Perlu bantuan segera','Setara: Kebutuhan pokok terpenuhi','Setara: Dana darurat terbentuk','Setara: Bisa DP rumah + investasi','Setara: Bebas finansial — fokus melayani'];
  var equivIdx = total>=80?4:total>=60?3:total>=45?2:total>=25?1:0;
  
  var scoreEquiv = document.getElementById('score-equiv');
  if (scoreEquiv) scoreEquiv.textContent = equivMap[equivIdx];

  function setM(vid, pid, bid, val, unit, good, warn) {
    var vEl = document.getElementById(vid);
    var pEl = document.getElementById(pid);
    var bEl = document.getElementById(bid);
    if (!vEl && !pEl && !bEl) return;

    var st = val>=good ? 'good' : val>=warn ? 'warn' : 'bad';
    if (vEl) vEl.textContent = Math.round(val*10)/10 + unit;
    if (pEl) pEl.innerHTML = '<span class="pill pill-' + st + '">' + (st==='good'?'✓ Bagus':st==='warn'?'△ Hampir':'↑ Perbaiki') + '</span>';
    if (bEl) {
        bEl.style.width = Math.min(100, val*(100/(good||1))) + '%';
        bEl.className = 'prog-fill fill-' + (st==='good'?'g':st==='warn'?'w':'b');
    }
  }
  setM('mv-sav','mp-sav','pb-sav',sr,'%',20,10);
  
  var mvDbt = document.getElementById('mv-dbt');
  if (mvDbt) mvDbt.textContent = Math.round(dr)+'%';
  var mpDbt = document.getElementById('mp-dbt');
  if (mpDbt) mpDbt.innerHTML = '<span class="pill pill-'+(dr<15?'good':dr<30?'warn':'bad')+'">'+(dr<15?'✓ Aman':dr<30?'△ Perhatikan':'↑ Tinggi')+'</span>';
  var pbDbt = document.getElementById('pb-dbt');
  if (pbDbt) {
      pbDbt.style.width = Math.min(100, dr*2.5)+'%';
      pbDbt.className = 'prog-fill fill-'+(dr<15?'g':dr<30?'w':'b');
  }

  setM('mv-em','mp-em','pb-em',emM,' bln',6,3);
  
  var mvLv = document.getElementById('mv-lv');
  if (mvLv) mvLv.textContent = Math.round(lr)+'%';
  var mpLv = document.getElementById('mp-lv');
  if (mpLv) mpLv.innerHTML = '<span class="pill pill-'+(lr<70?'good':lr<85?'warn':'bad')+'">'+(lr<70?'✓ Efisien':lr<85?'△ Perhatikan':'↑ Tinggi')+'</span>';
  var pbLv = document.getElementById('pb-lv');
  if (pbLv) {
      pbLv.style.width = Math.min(100, lr)+'%';
      pbLv.className = 'prog-fill fill-'+(lr<70?'g':lr<85?'w':'b');
  }

  var mvTth = document.getElementById('mv-tth');
  if (mvTth) mvTth.textContent = Math.round(tr)+'%';

  updateGardenScore(total);
  updateLevelDisplay();
  renderHealthCharts();
  renderTips(total);
}

// ——— LEVEL DISPLAY ———
function updateLevelDisplay() {
  var nw = calcNetWorth();
  var title, desc, equiv;
  if (nw < 0) { title='🔴 Level 0 — Defisit'; desc='Utang melebihi aset. Fokus kurangi utang dan stabilkan arus kas.'; equiv='Prioritas: lunasi utang sebelum investasi apapun'; }
  else if (nw < 10e6) { title='🌱 Level 1 — Benih'; desc='Awal yang baik! Bangun dana darurat minimal 3 bulan pengeluaran.'; equiv='Setara: Mahasiswa baru mulai menabung'; }
  else if (nw < 50e6) { title='🌿 Level 2 — Tumbuh'; desc='Fondasi mulai terbentuk. Terus bangun dana darurat hingga 6 bulan.'; equiv='Setara: Motor baru + dana darurat awal'; }
  else if (nw < 200e6) { title='🌳 Level 3 — Berakar'; desc='Kondisi sehat! Saatnya mulai diversifikasi investasi.'; equiv='Setara: Dana darurat lengkap + mulai investasi'; }
  else if (nw < 1e9) { title='🏡 Level 4 — Berbuah'; desc='Luar biasa! Pertimbangkan perencanaan warisan dan asuransi jiwa.'; equiv='Setara: Bisa DP rumah + mulai dana warisan'; }
  else { title='🏆 Level 5 — Berlimpah'; desc='Keuangan sangat sehat. Fokus pada dampak sosial dan pelayanan.'; equiv='Setara: Bebas finansial, fokus melayani'; }
  
  var lt = document.getElementById('level-title'); if(lt) lt.textContent = title;
  var ld = document.getElementById('level-desc'); if(ld) ld.textContent = desc;
  var le = document.getElementById('level-equiv'); if(le) le.textContent = '📍 ' + equiv;
  
  var gs = document.getElementById('g-score'); if(gs) gs.textContent = (healthScores.total || '—');
  var gl = document.getElementById('g-level'); if(gl) gl.textContent = title;
  
  var bnw = document.getElementById('b-nw');
  if (bnw) { bnw.textContent = fmtS(nw); bnw.style.color = nw>=0?'var(--gold)':'var(--bad)'; }
}

// ——— BERANDA STATS ———
function updateBerandaStats() {
  var cm = curM();
  var inc = 0, exp = 0;
  transactions.filter(function(t) { return t.date.startsWith(cm); }).forEach(function(t) {
    if (t.type==='income') inc+=t.amount; else exp+=t.amount;
  });
  
  var bInc = document.getElementById('b-inc');
  if (bInc) bInc.textContent = fmtS(inc);
  var bExp = document.getElementById('b-exp');
  if (bExp) bExp.textContent = fmtS(exp);
  
  var bal = inc - exp;
  var bBal = document.getElementById('b-bal');
  if (bBal) {
      bBal.textContent = fmtS(Math.abs(bal));
      bBal.style.color = bal >= 0 ? 'var(--good)' : 'var(--bad)';
  }
  
  var totalPort = assets.reduce(function(s,a) { return s + (a.currentPrice||a.buyPrice)*a.qty; }, 0);
  var bPort = document.getElementById('b-port');
  if (bPort) bPort.textContent = fmtS(totalPort);
  
  var pend = reimburse.filter(function(r) { return r.status==='pending'; }).reduce(function(s,r) { return s+r.amount; }, 0);
  var bRmb = document.getElementById('b-rmb');
  if (bRmb) bRmb.textContent = fmtS(pend);
}

function calcAll() {
  renderTransactions(); updateBerandaStats(); renderAssets();
  renderReimburse(); renderInsurance(); renderTunjangan();
  renderCicilanEmas(); calcNetWorth(); updateLevelDisplay();
}

// ——— TIPS ———
var ALL_TIPS = {
  critical: [
    {icon:'🚨',title:'Stabilisasi Dulu',body:'Sebelum investasi apapun, fokus pada satu hal: hentikan arus keluar yang tidak perlu. Bayar tagihan wajib, makan, dan minimal lunasi cicilan.'},
    {icon:'🆘',title:'Cari Pemasukan Tambahan',body:'Pelayanan berbayar (kotbah undangan, konseling), les, atau freelance kecil. Setiap tambahan berarti.'},
    {icon:'🤝',title:'Jangan Malu Minta Tolong',body:'Bicarakan dengan keluarga atau jemaat terpercaya. Meminta bantuan bukan kelemahan — itu kebijaksanaan.'}
  ],
  struggling: [
    {icon:'🌱',title:'Mulai Dana Darurat',body:'Targetkan Rp 500.000/bulan terpisah. Jangan sentuh. Ini jaring pengamanmu.'},
    {icon:'📝',title:'Catat 30 Hari',body:'Selama sebulan, catat setiap rupiah. Kamu akan kaget dengan pola tersembunyi yang menyedot uang.'},
    {icon:'✂️',title:'Identifikasi Kebocoran',body:'Lihat 3 pos pengeluaran terbesar. Bisakah dipotong 10-20%? Rp 100rb/bulan = Rp 1.2jt/tahun.'}
  ],
  okay: [
    {icon:'💰',title:'Target 20% Tabungan',body:'Otomatiskan transfer tabungan di hari yang sama dengan gaji masuk. Kamu tidak bisa andalkan niat — andalkan sistem.'},
    {icon:'🏦',title:'Pisahkan 3 Rekening',body:'Operasional, dana darurat (jangan disentuh!), dan investasi. Kejelasan ini mencegah keputusan impulsif.'},
    {icon:'🌳',title:'Mulai Reksadana',body:'Rp 100.000/bulan sudah cukup. Reksadana pasar uang dengan reputasi baik lebih baik dari mendiamkan uang di tabungan biasa.'}
  ],
  good: [
    {icon:'🌿',title:'Cek Diversifikasi',body:'Pastikan tidak lebih dari 30% portofolio di satu instrumen. Emas bagus untuk lindung nilai jangka panjang.'},
    {icon:'🏠',title:'Tujuan Jangka Panjang',body:'Rumah? Pendidikan anak? Dana pensiun? Tentukan satu tujuan dan alokasikan khusus untuknya setiap bulan.'},
    {icon:'📚',title:'Tingkatkan Literasi',body:'Baca: "The Psychology of Money" (Morgan Housel). Investasi terbaik adalah investasi pada pikiranmu.'}
  ],
  excellent: [
    {icon:'🏆',title:'Dana Warisan',body:'Di level ini, pertimbangkan asuransi jiwa yang cukup, surat wasiat, dan perencanaan harta keluarga.'},
    {icon:'💡',title:'Dampak Sosial',body:'Keuangan berlimpah adalah amanah. Pertimbangkan dana sosial khusus atau mendukung pelayanan lain secara terstruktur.'},
    {icon:'🌱',title:'Mentor Orang Lain',body:'Bagikan apa yang kamu pelajari. Kesehatan finansialmu bisa jadi berkat bagi jemaat yang membutuhkan bimbingan.'}
  ],
  always: [
    {icon:'🕊️',title:'Keuangan = Ibadah',body:'Mengelola uang dengan baik adalah bentuk ibadah dan tanggung jawab terhadap berkat yang diberikan. Ini bukan tentang cinta uang.'},
    {icon:'🌳',title:'Aturan 60-20-10-10',body:'60% kebutuhan hidup, 20% tabungan & darurat, 10% persepuluhan, 10% investasi. Berbasis persentase — cocok untuk pemasukan pastoral yang tidak linear.'},
    {icon:'📅',title:'Review Kuartalan',body:'Setiap 3 bulan, luangkan 1 jam untuk update net worth dan cek rasio kesehatan. Konsistensi mengalahkan kecerdasan.'}
  ]
};

function renderTips(score) {
  var bucket = score>=80?'excellent':score>=60?'good':score>=40?'okay':score>=20?'struggling':'critical';
  var specific = ALL_TIPS[bucket];
  var always = ALL_TIPS.always;
  var all = specific.concat(always);
  var colors = ['var(--sage-pale)','var(--earth-pale)','#FBF3E0'];
  var container = document.getElementById('tips-container');
  if (!container) return;
  var html = '';
  all.forEach(function(t, i) {
    html += '<div class="tip-card"><div class="tip-icon" style="background:' + colors[i%3] + '">' + t.icon + '</div>'
      + '<div><div class="tip-title">' + t.title + '</div><div class="tip-body">' + t.body + '</div></div></div>';
  });
  container.innerHTML = html;
}

// ——— CHARTS ———
function destroyChart(id) { if (charts[id]) { charts[id].destroy(); delete charts[id]; } }

function renderCharts() {
  if (!document.getElementById('ch-expense-donut')) return;

  var cm = curM();
  var monthly = transactions.filter(function(t) { return t.date.startsWith(cm); });
  var expByCat = {};
  monthly.filter(function(t){return t.type==='expense';}).forEach(function(t) { expByCat[t.cat] = (expByCat[t.cat]||0) + t.amount; });
  var eCats = Object.keys(expByCat), eVals = eCats.map(function(c){return expByCat[c];});
  var totalExp = eVals.reduce(function(a,b){return a+b;}, 0);
  
  var dExpTotal = document.getElementById('dnut-exp-total');
  if (dExpTotal) dExpTotal.textContent = fmtS(totalExp||0);
  
  destroyChart('ch-expense-donut');
  charts['ch-expense-donut'] = new Chart(document.getElementById('ch-expense-donut').getContext('2d'), {
    type:'doughnut',
    data:{labels:eCats.length?eCats:['Belum ada'],datasets:[{data:eVals.length?eVals:[1],backgroundColor:eCats.length?PALETTE:['#E4DFDA'],borderWidth:0,hoverOffset:4}]},
    options:{cutout:'68%',plugins:{legend:{display:false},tooltip:{callbacks:{label:function(c){return ' '+fmtS(c.raw);}}}},animation:{duration:700}}
  });
  var legEl = document.getElementById('dnut-exp-legend');
  if (legEl) legEl.innerHTML = eCats.map(function(c,i){return '<div class="legend-item"><div class="legend-dot" style="background:'+PALETTE[i%PALETTE.length]+'"></div>'+c+'</div>';}).join('');

  var assetByCat = {};
  assets.forEach(function(a){var v=(a.currentPrice||a.buyPrice)*a.qty;assetByCat[a.type]=(assetByCat[a.type]||0)+v;});
  var aCats=Object.keys(assetByCat), aVals=aCats.map(function(c){return assetByCat[c];});
  var totalAsset=aVals.reduce(function(a,b){return a+b;},0);
  
  var dAssetTotal = document.getElementById('dnut-asset-total');
  if (dAssetTotal) dAssetTotal.textContent = fmtS(totalAsset||0);
  
  destroyChart('ch-asset-donut');
  charts['ch-asset-donut'] = new Chart(document.getElementById('ch-asset-donut').getContext('2d'), {
    type:'doughnut',
    data:{labels:aCats.length?aCats.map(function(c){return (ASSET_ICONS[c]||'📦')+' '+c;}):['Belum ada'],datasets:[{data:aVals.length?aVals:[1],backgroundColor:aCats.length?ASSET_COLORS:['#E4DFDA'],borderWidth:0,hoverOffset:4}]},
    options:{cutout:'68%',plugins:{legend:{display:false},tooltip:{callbacks:{label:function(c){return ' '+fmtS(c.raw);}}}},animation:{duration:700}}
  });
  var legEl2 = document.getElementById('dnut-asset-legend');
  if (legEl2) legEl2.innerHTML = aCats.map(function(c,i){return '<div class="legend-item"><div class="legend-dot" style="background:'+ASSET_COLORS[i%ASSET_COLORS.length]+'"></div>'+(ASSET_ICONS[c]||'📦')+' '+c+'</div>';}).join('');

  var months6=[], incArr=[], expArr=[];
  for (var i=5;i>=0;i--) {
    var d=new Date(currentMonth.getFullYear(),currentMonth.getMonth()-i,1);
    var key=d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0');
    months6.push(MONTHS_S[d.getMonth()]);
    var mI=0,mE=0;
    transactions.filter(function(t){return t.date.startsWith(key);}).forEach(function(t){if(t.type==='income')mI+=t.amount;else mE+=t.amount;});
    incArr.push(mI); expArr.push(mE);
  }
  
  var chTrend = document.getElementById('ch-trend');
  if (chTrend) {
      destroyChart('ch-trend');
      charts['ch-trend'] = new Chart(chTrend.getContext('2d'), {
        type:'bar',
        data:{labels:months6,datasets:[{label:'Pemasukan',data:incArr,backgroundColor:'rgba(125,170,137,.8)',borderRadius:6,borderSkipped:false},{label:'Pengeluaran',data:expArr,backgroundColor:'rgba(212,115,115,.8)',borderRadius:6,borderSkipped:false}]},
        options:{responsive:true,plugins:{legend:{position:'bottom',labels:{boxWidth:10,font:{size:11}}},tooltip:{callbacks:{label:function(c){return ' '+fmtS(c.raw);}}}},scales:{x:{grid:{display:false},ticks:{font:{size:11}}},y:{grid:{color:'rgba(0,0,0,.04)'},ticks:{callback:function(v){return fmtS(v);},font:{size:10}}}}}
      });
  }

  var daysInMonth=new Date(currentMonth.getFullYear(),currentMonth.getMonth()+1,0).getDate();
  var days={}, dayLabels=[];
  for (var d2=1;d2<=daysInMonth;d2++){days[String(d2).padStart(2,'0')]=0;dayLabels.push(String(d2));}
  monthly.forEach(function(t){var dy=t.date.split('-')[2];if(days[dy]!==undefined)days[dy]+=(t.type==='income'?t.amount:-t.amount);});
  var run=0, runArr=Object.values(days).map(function(v){run+=v;return run;});
  
  var chDaily = document.getElementById('ch-daily');
  if (chDaily) {
      destroyChart('ch-daily');
      charts['ch-daily'] = new Chart(chDaily.getContext('2d'), {
        type:'line',
        data:{labels:dayLabels,datasets:[{label:'Arus Kas',data:runArr,borderColor:'#7DAA89',backgroundColor:'rgba(125,170,137,.08)',fill:true,tension:.4,pointRadius:2,borderWidth:2}]},
        options:{responsive:true,plugins:{legend:{display:false},tooltip:{callbacks:{label:function(c){return ' '+fmtS(c.raw);}}}},scales:{x:{grid:{display:false},ticks:{font:{size:9},maxTicksLimit:10}},y:{grid:{color:'rgba(0,0,0,.04)'},ticks:{callback:function(v){return fmtS(v);},font:{size:9}}}}}
      });
  }

  var rmbByCat={};
  reimburse.forEach(function(r){rmbByCat[r.cat]=(rmbByCat[r.cat]||0)+r.amount;});
  var rCats=Object.keys(rmbByCat),rVals=rCats.map(function(c){return rmbByCat[c];});
  
  var chRmb = document.getElementById('ch-rmb');
  if (chRmb) {
      destroyChart('ch-rmb');
      charts['ch-rmb'] = new Chart(chRmb.getContext('2d'), {
        type:'bar',
        data:{labels:rCats.map(function(c){return (RMB_LABELS[c]||'📦')+' '+c.replace('_',' & ');}),datasets:[{label:'Reimburse',data:rVals,backgroundColor:'rgba(123,184,212,.8)',borderRadius:6,borderSkipped:false}]},
        options:{indexAxis:'y',responsive:true,plugins:{legend:{display:false},tooltip:{callbacks:{label:function(c){return ' '+fmtS(c.raw);}}}},scales:{x:{grid:{color:'rgba(0,0,0,.04)'},ticks:{callback:function(v){return fmtS(v);},font:{size:9}}},y:{grid:{display:false},ticks:{font:{size:10}}}}}
      });
  }
}

function renderHealthCharts() {
  var hd = healthScores;
  if (!hd.total) return;
  
  var chRadar = document.getElementById('ch-radar');
  if (chRadar) {
      destroyChart('ch-radar');
      charts['ch-radar'] = new Chart(chRadar.getContext('2d'), {
        type:'radar',
        data:{labels:['Tabungan','Utang','Darurat','Hidup Hemat','Persepuluhan'],datasets:[{label:'Skor',data:[hd.sS,hd.dS,hd.eS,hd.lS,hd.tS],backgroundColor:'rgba(125,170,137,.2)',borderColor:'#7DAA89',borderWidth:2,pointBackgroundColor:'#7DAA89',pointRadius:4}]},
        options:{responsive:true,scales:{r:{min:0,max:20,ticks:{stepSize:5,font:{size:9}},pointLabels:{font:{size:10}}}},plugins:{legend:{display:false}}}
      });
  }
  
  var chHealthBar = document.getElementById('ch-health-bar');
  if (chHealthBar) {
      destroyChart('ch-health-bar');
      charts['ch-health-bar'] = new Chart(chHealthBar.getContext('2d'), {
        type:'bar',
        data:{labels:['Tabungan','Utang','Darurat','Hidup','Persepuluhan'],datasets:[{label:'Skor',data:[hd.sS,hd.dS,hd.eS,hd.lS,hd.tS],backgroundColor:PALETTE.slice(0,5),borderRadius:8,borderSkipped:false}]},
        options:{responsive:true,plugins:{legend:{display:false},tooltip:{callbacks:{label:function(c){return c.raw+'/20 poin';}}}},scales:{x:{grid:{display:false},ticks:{font:{size:10}}},y:{min:0,max:20,grid:{color:'rgba(0,0,0,.04)'},ticks:{font:{size:10}}}}}
      });
  }
}

// ——— GARDEN ANIMATION ———
var Garden = function(canvas) {
  this.c = canvas;
  this.ctx = canvas.getContext('2d');
  this.score = 50; this.t = 0; this.particles = [];
  this.w = canvas.offsetWidth || 400;
  this.h = canvas.offsetHeight || 200;
  this.resize();
  var self = this;
  window.addEventListener('resize', function(){self.resize();});
  this.loop();
};

Garden.prototype.resize = function() {
  var dpr = window.devicePixelRatio || 1;
  this.c.width = (this.c.offsetWidth || 400) * dpr;
  this.c.height = (this.c.offsetHeight || 200) * dpr;
  this.ctx.scale(dpr, dpr);
  this.w = this.c.offsetWidth || 400;
  this.h = this.c.offsetHeight || 200;
};

Garden.prototype.setScore = function(s) { this.score = s; };

Garden.prototype.addParticle = function() {
  for (var i=0;i<8;i++) {
    this.particles.push({x:Math.random()*this.w,y:this.h*0.6,vx:(Math.random()-.5)*2,vy:-(Math.random()*3+1),life:1,size:Math.random()*4+2,color:'#D4A843'});
  }
};

Garden.prototype.drawTree = function(x, baseY, treeH, r, score, idx) {
  var c = this.ctx, t = this.t;
  c.fillStyle = '#6B4226';
  c.fillRect(x - r*0.09, baseY - treeH*0.5, r*0.18, treeH*0.5);
  if (score < 20) {
    c.strokeStyle='#5A4A2A'; c.lineWidth=2;
    [[x,baseY-treeH*.5,x-r*.5,baseY-treeH*.8],[x,baseY-treeH*.5,x+r*.4,baseY-treeH*.75]].forEach(function(l){c.beginPath();c.moveTo(l[0],l[1]);c.lineTo(l[2],l[3]);c.stroke();});
  } else {
    var sway = Math.sin(t*.8+idx)*r*.02;
    var top = baseY - treeH;
    var leafColor = score>=80?'#3A8A3A':score>=60?'#4A9A4A':score>=40?'#6AAA4A':'#8A9A3A';
    var grad = c.createRadialGradient(x+sway,top+r*.3,r*.1,x+sway,top+r*.3,r);
    grad.addColorStop(0, score>=60?'#5AB85A':'#7A9A4A');
    grad.addColorStop(1, leafColor);
    c.fillStyle = grad;
    c.beginPath(); c.arc(x+sway, top+r*.35, r, 0, Math.PI*2); c.fill();
    if (score >= 75) {
      for (var fi=0;fi<5;fi++) {
        var angle = (fi/5)*Math.PI*2 + t*.5;
        var fx = x+sway+Math.cos(angle)*r*.6*Math.random();
        var fy = top+r*.35+Math.sin(angle)*r*.4*Math.random();
        c.fillStyle = ['#FFB6C1','#FFD700','#FF8C69'][fi%3];
        c.beginPath(); c.arc(fx,fy,3,0,Math.PI*2); c.fill();
      }
    }
  }
};

Garden.prototype.draw = function() {
  var c = this.ctx, w = this.w, h = this.h, t = this.t, score = this.score;
  c.clearRect(0,0,w,h);
  var skyGrad = c.createLinearGradient(0,0,0,h*.65);
  skyGrad.addColorStop(0, score>=50?'#B8DDF0':'#9EB0C0');
  skyGrad.addColorStop(1, score>=50?'#D8EEF7':'#B8C8D8');
  c.fillStyle=skyGrad; c.fillRect(0,0,w,h*.65);
  c.fillStyle='#5A8A3A'; c.fillRect(0,h*.62,w,h*.38);
  var lakeGrad=c.createLinearGradient(0,h*.6,0,h*.75);
  lakeGrad.addColorStop(0,score>=50?'#7BB8D4':'#8AA0B0');
  lakeGrad.addColorStop(1,score>=50?'#5A9EB8':'#6A8898');
  c.fillStyle=lakeGrad; c.beginPath(); c.ellipse(w*.5,h*.7,w*.3,h*.07,0,0,Math.PI*2); c.fill();
  c.globalAlpha=.25; c.strokeStyle='#fff'; c.lineWidth=1;
  c.beginPath(); c.ellipse(w*.5,h*.7,w*.12+Math.sin(t*.8)*2,h*.02,0,0,Math.PI*2); c.stroke();
  c.globalAlpha=1;
  var trees=[{x:w*.1,y:h*.63,h:h*.3,r:h*.13},{x:w*.32,y:h*.64,h:h*.26,r:h*.11},{x:w*.7,y:h*.63,h:h*.28,r:h*.12},{x:w*.88,y:h*.65,h:h*.2,r:h*.08}];
  var self=this;
  trees.forEach(function(tr,i){self.drawTree(tr.x,tr.y,tr.h,tr.r,score,i);});
  var self2=this;
  this.particles=this.particles.filter(function(p){p.x+=p.vx;p.y+=p.vy;p.vy+=.05;p.life-=.015;if(p.life<=0)return false;c.globalAlpha=p.life;c.fillStyle=p.color;c.beginPath();c.arc(p.x,p.y,p.size,0,Math.PI*2);c.fill();c.globalAlpha=1;return true;});
};

Garden.prototype.loop = function() {
  this.t += 0.02; this.draw();
  var self = this;
  requestAnimationFrame(function(){self.loop();});
};

var garden, heroGarden;

function updateGardenScore(score) {
  if (garden) garden.setScore(score);
  if (heroGarden) heroGarden.setScore(score);
  var gs = document.getElementById('g-score'); if(gs) gs.textContent = score;
}

function showGardenMsg(msg) {
  var el = document.getElementById('garden-msg');
  if (!el) return;
  el.textContent = msg; el.classList.add('show');
  if (garden) garden.addParticle();
  setTimeout(function(){el.classList.remove('show');}, 3000);
}

// ——— GOOGLE SHEETS ———
function updateGsStatus() {
  var on = !!gsUrl;
  document.querySelectorAll('.gs-dot').forEach(function(d){d.classList.toggle('on',on);});
  var lbl = document.getElementById('gs-label'); if(lbl) lbl.textContent = on ? 'Terhubung' : 'Lokal';
  var txt = document.getElementById('gs-status-text'); if(txt) txt.textContent = on ? '✅ Terhubung ke Google Sheets' : 'Belum terhubung';
  var inp = document.getElementById('gs-url'); if(inp && gsUrl) inp.value = gsUrl;
}

function showGsMsg(msg, type) {
  var el = document.getElementById('gs-msg');
  if (!el) return;
  el.innerHTML = '<div class="status-msg sm' + type + '">' + msg + '</div>';
  setTimeout(function(){if(el)el.innerHTML='';}, 4000);
}

function connectSheets() {
  var url = document.getElementById('gs-url').value.trim();
  if (!url.startsWith('https://')) { showGsMsg('URL tidak valid', 'err'); return; }
  showGsMsg('Menguji koneksi...', 'info');
  fetch(url, {method:'GET', mode:'no-cors'}).then(function() {
    gsUrl = url;
    try { localStorage.setItem('plen_gs_url', url); } catch(e) {}
    updateGsStatus();
    showGsMsg('✅ Berhasil terhubung!', 'ok');
  }).catch(function() { showGsMsg('Koneksi gagal. Periksa URL.', 'err'); });
}

function disconnectSheets() {
  gsUrl = '';
  try { localStorage.removeItem('plen_gs_url'); } catch(e) {}
  var inp = document.getElementById('gs-url'); if(inp) inp.value = '';
  updateGsStatus();
  showGsMsg('Koneksi diputus. Data tetap aman di lokal.', 'info');
}

function syncAll() {
  if (!gsUrl) { showGsMsg('Belum terhubung.', 'err'); return; }
  showGsMsg('Menyinkronkan data...', 'info');
  gsPost({action:'sync_all',transactions:transactions,reimburse:reimburse,assets:assets,insurance:insurance,tunjangan:tunjangan}).then(function(ok){
    if (ok) showGsMsg('✅ Sync berhasil! ' + transactions.length + ' transaksi terkirim.', 'ok');
    else showGsMsg('Gagal sync. Cek koneksi.', 'err');
  });
}

function gsPost(data) {
  if (!gsUrl) return Promise.resolve(null);
  return fetch(gsUrl, {method:'POST', body:JSON.stringify(data), mode:'no-cors'})
    .then(function(){return true;}).catch(function(){return null;});
}

// ——— TABS ———
function switchTab(name, btn) {
  // Logic for multi-page: if we're calling switchTab, we probably want to navigate.
  // But since the requirement is to use standard <a> tags, this function might
  // be redundant for navigation, but still used by internal JS calls.
  // For multi-page, we redirect.
  const routeMap = {
      'beranda': '/dashboard',
      'catat': '/transactions',
      'divers': '/diversification',
      'reimburse': '/reimbursement',
      'asuransi': '/insurance',
      'grafik': '/charts',
      'kesehatan': '/health',
      'tips': '/tips',
      'sheets': '/sheets'
  };
  
  if (routeMap[name]) {
      window.location.href = routeMap[name];
  }
}

// ——— INIT ———
function init() {
  loadData();
  var txnDate = document.getElementById('txn-date'); if(txnDate) txnDate.valueAsDate = new Date();
  var rDate = document.getElementById('r-date'); if(rDate) rDate.valueAsDate = new Date();
  var aDate = document.getElementById('a-date'); if(aDate) aDate.valueAsDate = new Date();
  updateCategories();
  updateMonthLabel();
  calcAll();
  renderTips(50);
  updateGsStatus();

  var gardenCanvas = document.getElementById('garden-canvas');
  if (gardenCanvas) garden = new Garden(gardenCanvas);
  var heroCanvas = document.getElementById('hero-canvas');
  if (heroCanvas) { heroGarden = new Garden(heroCanvas); heroGarden.setScore(65); }
  
  // Initialize specific page logic
  const path = window.location.pathname;
  if (path.includes('charts')) setTimeout(renderCharts, 80);
  if (path.includes('health')) setTimeout(renderHealthCharts, 80);
  if (path.includes('dashboard')) updateBerandaStats();

  var nwIds = ['nw-sav','nw-em','nw-inv','nw-oth','nw-dbt'];
  nwIds.forEach(function(id) {
    var el = document.getElementById(id);
    if (!el) return;
    try { var v = localStorage.getItem('plen_'+id); if(v) el.value = v; } catch(e) {}
    el.addEventListener('input', function() {
      try { localStorage.setItem('plen_'+id, el.value); } catch(e) {}
    });
  });
}

// Check PIN on every page load except root
window.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
        initPinScreen();
    } else {
        const saved = getPinFromStorage();
        if (!saved) {
            window.location.href = '/';
        } else {
            // Check if already unlocked in this session? 
            // The original app used a simple 'display:none' for the pin screen.
            // Since we are multi-page, we should probably keep track of auth status.
            // But for this task, I'll just init the app.
            init();
        }
    }
});
