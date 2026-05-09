@extends('layouts.app')

@section('content')
<!-- REGISTER SCREEN -->
<div id="pin-screen">
  <div class="pin-card">
    <div class="pin-logo">Plen<span>invest</span></div>
    <div class="pin-tagline">by Ricky Atmoko · Buat Akun Baru</div>
    
    <form action="{{ url('/register') }}" method="POST">
        @csrf
        
        @if ($errors->any())
            <div style="background: rgba(184, 64, 64, 0.2); color: #fff; padding: 10px; border-radius: 12px; margin-bottom: 16px; font-size: 11px; text-align: left; border: 1px solid rgba(255,255,255,0.1);">
                <ul style="margin: 0; padding-left: 15px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom: 12px;">
            <div class="pin-label">Nama Lengkap</div>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Anda" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; text-align: center; outline: none; font-family: 'Inter', sans-serif;">
        </div>
        
        <div style="margin-bottom: 12px;">
            <div class="pin-label">Username</div>
            <input type="text" name="username" value="{{ old('username') }}" required placeholder="Username unik" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; text-align: center; outline: none; font-family: 'Inter', sans-serif;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 24px;">
            <div>
                <div class="pin-label">PIN (4 Digit)</div>
                <input type="password" name="pin" maxlength="4" required placeholder="****" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; text-align: center; outline: none; font-family: 'Inter', sans-serif; letter-spacing: 4px;">
            </div>
            <div>
                <div class="pin-label">Konfirmasi</div>
                <input type="password" name="pin_confirmation" maxlength="4" required placeholder="****" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: white; text-align: center; outline: none; font-family: 'Inter', sans-serif; letter-spacing: 4px;">
            </div>
        </div>

        <button type="submit" class="pin-btn" style="width: 100%; background: var(--forest); border: none; font-weight: 700;">DAFTAR SEKARANG</button>
    </form>
    
    <div class="pin-forgot" onclick="window.location.href='{{ url('/login') }}'">Sudah punya akun? Login di sini</div>
  </div>
</div>
@endsection
