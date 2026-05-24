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
  .bg-shape { position: absolute; border-radius: 50%; pointer-events: none; }
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

  /* Feature list */
  .feature-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 1.75rem;
    padding: 1.25rem;
    background: #f7f6f3;
    border-radius: 14px;
    border: 1px solid #e8e5de;
  }
  .feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #555450;
  }
  .feature-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
  }
  .fi-blue  { background: #e8f1fb; color: #185fa5; }
  .fi-green { background: #eaf3de; color: #639922; }
  .fi-dark  { background: #f0ede8; color: #555450; }

  .btn-google {
    font-family: 'Sora', sans-serif;
    width: 100%;
    padding: 13px;
    border-radius: 100px;
    border: 1.5px solid #e0ddd7;
    background: #fff;
    color: #1a1916;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
    margin-bottom: 1rem;
  }
  .btn-google i { font-size: 18px; color: #185fa5; }
  .btn-google:hover {
    background: #f7f6f3;
    border-color: #185fa5;
    box-shadow: 0 4px 16px rgba(24,95,165,.12);
    transform: translateY(-1px);
    text-decoration: none;
    color: #1a1916;
  }

  .divider-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 1.25rem 0;
  }
  .divider-line { flex: 1; height: 1px; background: #e8e5de; }
  .divider-text { font-size: 12px; color: #c0bdb5; font-weight: 500; }

  .btn-login {
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
    text-decoration: none;
  }
  .btn-login:hover {
    background: #333028;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
    color: #fff;
    text-decoration: none;
  }

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

      <h1 class="card-title">Buat akun baru</h1>
      <p class="card-sub">Daftar dengan Google untuk langsung mengakses Drive dan Calendar.</p>

      {{-- Feature highlights --}}
      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-icon fi-blue"><i class="ti ti-brand-google-drive"></i></div>
          Upload dan kelola file langsung di Google Drive kamu
        </div>
        <div class="feature-item">
          <div class="feature-icon fi-green"><i class="ti ti-calendar-event"></i></div>
          Buat dan terima event Google Calendar bersama tim
        </div>
        <div class="feature-item">
          <div class="feature-icon fi-dark"><i class="ti ti-users"></i></div>
          Semua anggota terdaftar otomatis saling berbagi konten
        </div>
      </div>

      {{-- Daftar dengan Google --}}
      <a href="{{ route('auth.google') }}" class="btn-google">
        <i class="ti ti-brand-google"></i>
        Daftar dengan Google
      </a>

      <div class="divider-row">
        <span class="divider-line"></span>
        <span class="divider-text">sudah punya akun?</span>
        <span class="divider-line"></span>
      </div>

      <a href="{{ route('login') }}" class="btn-login">
        <i class="ti ti-login" style="font-size:16px"></i>
        Masuk ke akun
      </a>

      <div class="card-footer-note">
        <i class="ti ti-shield-check"></i>
        Dilindungi OAuth 2.0 — data kamu aman
      </div>

    </div>
  </div>
</div>
@endsection