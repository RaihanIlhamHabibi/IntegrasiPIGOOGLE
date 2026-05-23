@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .login-wrap {
    font-family: 'Sora', sans-serif;
    min-height: 100vh;
    background: #f7f6f3;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    position: relative;
    overflow: hidden;
  }

  .bg-shape {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }
  .shape-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, #e8f1fb 0%, transparent 70%);
    top: -160px; right: -120px;
  }
  .shape-2 {
    width: 380px; height: 380px;
    background: radial-gradient(circle, #eaf3de 0%, transparent 70%);
    bottom: -100px; left: -80px;
  }
  .login-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,0,0,.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,0,0,.025) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
  }

  .login-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 440px;
    background: #fff;
    border: 1px solid #e8e5de;
    border-radius: 24px;
    box-shadow: 0 8px 40px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }

  /* top accent strip */
  .card-strip {
    height: 4px;
    background: linear-gradient(90deg, #185fa5 0%, #639922 50%, #185fa5 100%);
    background-size: 200% 100%;
    animation: shimmer 3s linear infinite;
  }
  @keyframes shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  .card-inner { padding: 2.25rem 2.25rem 2rem; }

  /* logo mark */
  .logo-mark {
    width: 48px; height: 48px;
    border-radius: 14px;
    background: #e8f1fb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #185fa5;
    margin-bottom: 1.25rem;
  }

  .card-title {
    font-size: 22px;
    font-weight: 700;
    color: #111110;
    letter-spacing: -.02em;
    margin-bottom: 5px;
  }
  .card-sub {
    font-size: 13.5px;
    color: #aaa9a3;
    line-height: 1.5;
    margin-bottom: 1.75rem;
  }

  /* form */
  .field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 1rem; }
  .field label {
    font-size: 13px;
    font-weight: 500;
    color: #555450;
  }
  .field input {
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #e0ddd7;
    background: #fafaf8;
    color: #1a1916;
    transition: border-color .15s, box-shadow .15s, background .15s;
    outline: none;
    width: 100%;
  }
  .field input:focus {
    border-color: #185fa5;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(24,95,165,.1);
  }
  .field input.is-invalid {
    border-color: #e24b4a;
    background: #fff8f8;
  }
  .field input.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(226,75,74,.1);
  }
  .invalid-msg {
    font-size: 12px;
    color: #e24b4a;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .field-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    margin-top: .25rem;
  }
  .remember-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #888880;
    cursor: pointer;
  }
  .remember-wrap input[type="checkbox"] {
    width: 15px; height: 15px;
    accent-color: #185fa5;
    cursor: pointer;
  }
  .link-register {
    font-size: 13px;
    color: #185fa5;
    text-decoration: none;
    font-weight: 500;
  }
  .link-register:hover { text-decoration: underline; text-underline-offset: 2px; }

  .btn-submit {
    font-family: 'Sora', sans-serif;
    width: 100%;
    padding: 12px;
    border-radius: 100px;
    border: none;
    background: #1a1916;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .btn-submit:hover { background: #333028; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(0,0,0,.15); }
  .btn-submit:active { transform: translateY(0); }

  .divider-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 1.25rem 0;
  }
  .divider-line { flex: 1; height: 1px; background: #e8e5de; }
  .divider-text { font-size: 12px; color: #c0bdb5; font-weight: 500; white-space: nowrap; }

  .btn-google {
    font-family: 'Sora', sans-serif;
    width: 100%;
    padding: 11px;
    border-radius: 100px;
    border: 1px solid #e0ddd7;
    background: #fff;
    color: #555450;
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    transition: all .18s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
  }
  .btn-google i { font-size: 16px; color: #185fa5; }
  .btn-google:hover { background: #f7f6f3; border-color: #c0bdb5; color: #1a1916; text-decoration: none; }

  .card-footer-note {
    text-align: center;
    font-size: 11.5px;
    color: #c0bdb5;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
  }
  .card-footer-note i { font-size: 13px; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
</style>

<div class="login-wrap">
  <div class="bg-shape shape-1"></div>
  <div class="bg-shape shape-2"></div>

  <div class="login-card">
    <div class="card-strip"></div>
    <div class="card-inner">

      <div class="logo-mark">
        <i class="ti ti-brand-google-drive"></i>
      </div>

      <h1 class="card-title">Selamat datang</h1>
      <p class="card-sub">Masuk untuk mengakses Google Drive dan Google Calendar kamu.</p>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="field">
          <label for="email">Alamat Email</label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="nama@email.com"
            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            required
            autofocus
          >
          @error('email')
            <span class="invalid-msg"><i class="ti ti-alert-circle" style="font-size:13px"></i> {{ $message }}</span>
          @enderror
        </div>

        <div class="field">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
            required
          >
          @error('password')
            <span class="invalid-msg"><i class="ti ti-alert-circle" style="font-size:13px"></i> {{ $message }}</span>
          @enderror
        </div>

        <div class="field-row">
          <label class="remember-wrap">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            Ingat saya
          </label>
          <a href="{{ route('register') }}" class="link-register">Belum punya akun?</a>
        </div>

        <button type="submit" class="btn-submit">
          <i class="ti ti-login" style="font-size:16px"></i> Masuk
        </button>
      </form>

      <div class="divider-row">
        <span class="divider-line"></span>
        <span class="divider-text">atau masuk dengan</span>
        <span class="divider-line"></span>
      </div>

      <a href="{{ route('auth.google') }}" class="btn-google">
        <i class="ti ti-brand-google"></i> Lanjutkan dengan Google
      </a>

      <div class="card-footer-note">
        <i class="ti ti-shield-check"></i>
        Dilindungi OAuth 2.0 — data kamu aman
      </div>

    </div>
  </div>
</div>
@endsection