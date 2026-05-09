<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pleninvest — by Ricky Atmoko</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pleninvest.css') }}">
</head>
<body>

<!-- PIN SCREEN -->
<div id="pin-screen">
  <div class="pin-card">
    <div class="pin-logo">Plen<span>invest</span></div>
    <div class="pin-tagline">by Ricky Atmoko · Privat & Aman</div>
    <div class="pin-label" id="pin-label">Masukkan PIN (4 digit)</div>
    <div class="pin-dots">
      <div class="pin-dot" id="dot0"></div><div class="pin-dot" id="dot1"></div>
      <div class="pin-dot" id="dot2"></div><div class="pin-dot" id="dot3"></div>
    </div>
    <div class="pin-pad">
      <button class="pin-btn" onclick="pinInput('1')">1</button>
      <button class="pin-btn" onclick="pinInput('2')">2</button>
      <button class="pin-btn" onclick="pinInput('3')">3</button>
      <button class="pin-btn" onclick="pinInput('4')">4</button>
      <button class="pin-btn" onclick="pinInput('5')">5</button>
      <button class="pin-btn" onclick="pinInput('6')">6</button>
      <button class="pin-btn" onclick="pinInput('7')">7</button>
      <button class="pin-btn" onclick="pinInput('8')">8</button>
      <button class="pin-btn" onclick="pinInput('9')">9</button>
      <button class="pin-btn" onclick="pinClear()" style="font-size:14px">⌫</button>
      <button class="pin-btn" onclick="pinInput('0')">0</button>
      <button class="pin-btn" onclick="pinSubmit()" style="background:rgba(255,255,255,.3)">✓</button>
    </div>
    <div class="pin-msg" id="pin-msg"></div>
    <div class="pin-forgot" onclick="pinReset()">Lupa PIN? Reset data</div>
  </div>
</div>

<script src="{{ asset('js/pleninvest.js') }}"></script>
</body>
</html>
