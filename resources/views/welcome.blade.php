@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  .hero-wrap {
    font-family: 'Sora', sans-serif;
    min-height: 100vh;
    background: #ffffff;
    color: #1a1916;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1.5rem;
    position: relative;
    overflow: hidden;
  }

  /* soft background shapes */
  .bg-shape {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }
  .shape-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, #e8f1fb 0%, transparent 70%);
    top: -180px; right: -150px;
  }
  .shape-2 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, #eaf3de 0%, transparent 70%);
    bottom: -120px; left: -100px;
  }
  .shape-3 {
    width: 200px; height: 200px;
    background: radial-gradient(circle, #fef9ec 0%, transparent 70%);
    top: 40%; left: 30%;
  }

  /* subtle line grid */
  .hero-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(0,0,0,.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,0,0,.03) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
  }

  .hero-inner {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 1100px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5rem;
    align-items: center;
  }

  /* ── LEFT ── */
  .hero-left { display: flex; flex-direction: column; gap: 1.75rem; }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #185fa5;
    background: #e8f1fb;
    border: 1px solid #b5d4f4;
    padding: 6px 14px;
    border-radius: 100px;
    width: fit-content;
    animation: fadeUp .5s ease both;
  }

  .hero-headline {
    font-size: clamp(28px, 3.5vw, 46px);
    font-weight: 700;
    line-height: 1.12;
    color: #111110;
    letter-spacing: -.02em;
    animation: fadeUp .5s .07s ease both;
  }
  .hero-headline .line-accent {
    color: #185fa5;
    position: relative;
    display: inline-block;
  }
  .hero-headline .line-accent::after {
    content: '';
    position: absolute;
    bottom: 2px; left: 0; right: 0;
    height: 3px;
    background: #b5d4f4;
    border-radius: 2px;
    opacity: .6;
  }

  .hero-sub {
    font-size: 15px;
    color: #888880;
    line-height: 1.75;
    max-width: 420px;
    animation: fadeUp .5s .14s ease both;
  }

  .hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    animation: fadeUp .5s .21s ease both;
  }
  .btn-h {
    font-family: 'Sora', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    font-weight: 500;
    padding: 12px 22px;
    border-radius: 100px;
    cursor: pointer;
    text-decoration: none;
    transition: all .18s;
    border: none;
  }
  .btn-h i { font-size: 16px; }
  .btn-solid {
    background: #1a1916;
    color: #fff;
  }
  .btn-solid:hover { background: #333028; color: #fff; text-decoration: none; transform: translateY(-1px); }
  .btn-border {
    background: #fff;
    color: #1a1916;
    border: 1px solid #dddbd5;
  }
  .btn-border:hover { background: #f7f6f3; color: #1a1916; text-decoration: none; border-color: #bbb9b2; }
  .btn-google {
    background: #fff;
    color: #555450;
    border: 1px solid #e8e5de;
  }
  .btn-google:hover { background: #f7f6f3; color: #1a1916; text-decoration: none; }

  .hero-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    animation: fadeUp .5s .28s ease both;
  }
  .meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #aaa9a3;
  }
  .meta-item i { font-size: 14px; color: #c0bdb5; }

  /* ── RIGHT ── */
  .hero-right {
    animation: fadeUp .5s .18s ease both;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  /* main card */
  .main-card {
    background: #fff;
    border: 1px solid #e8e5de;
    border-radius: 22px;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 4px 32px rgba(0,0,0,.06), 0 1px 4px rgba(0,0,0,.04);
  }

  .card-header-band {
    background: #f7f6f3;
    border-bottom: 1px solid #e8e5de;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 7px;
  }
  .dot { width: 10px; height: 10px; border-radius: 50%; }
  .dot-r { background: #ffb3ae; }
  .dot-y { background: #ffd580; }
  .dot-g { background: #a3e4a3; }
  .card-header-label {
    margin-left: 6px;
    font-size: 12px;
    color: #aaa9a3;
    font-weight: 500;
    letter-spacing: .03em;
  }

  .card-body-inner { padding: 1.5rem; display: flex; flex-direction: column; gap: 12px; }

  .feat-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1px solid #f0ede7;
    background: #fafaf8;
    transition: all .18s;
    cursor: default;
  }
  .feat-row:hover {
    border-color: #dddbd5;
    background: #fff;
    transform: translateX(3px);
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
  }
  .feat-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    flex-shrink: 0;
  }
  .fi-blue  { background: #e8f1fb; color: #185fa5; }
  .fi-green { background: #eaf3de; color: #2e6e11; }
  .feat-info { flex: 1; }
  .feat-title { font-size: 13.5px; font-weight: 600; color: #1a1916; margin-bottom: 2px; }
  .feat-desc  { font-size: 12px; color: #aaa9a3; line-height: 1.4; }
  .feat-tag {
    font-size: 11px;
    font-weight: 500;
    padding: 3px 10px;
    border-radius: 100px;
  }
  .tag-blue  { background: #e8f1fb; color: #185fa5; }
  .tag-green { background: #eaf3de; color: #2e6e11; }

  /* stats row */
  .stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  .stat-mini {
    background: #fff;
    border: 1px solid #e8e5de;
    border-radius: 16px;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 12px rgba(0,0,0,.04);
  }
  .stat-mini-label {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .05em;
    text-transform: uppercase;
    color: #aaa9a3;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .stat-mini-label i { font-size: 13px; }
  .stat-mini-value {
    font-size: 28px;
    font-weight: 700;
    color: #1a1916;
    line-height: 1;
  }
  .stat-mini-sub { font-size: 11.5px; color: #c0bdb5; margin-top: 4px; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 768px) {
    .hero-inner { grid-template-columns: 1fr; gap: 2.5rem; }
    .shape-1 { width: 300px; height: 300px; }
    .shape-2 { width: 220px; height: 220px; }
  }
</style>

<div class="hero-wrap">
  <div class="bg-shape shape-1"></div>
  <div class="bg-shape shape-2"></div>
  <div class="bg-shape shape-3"></div>

  <div class="hero-inner">

    {{-- LEFT --}}
    <div class="hero-left">
      <span class="hero-badge">
        <i class="ti ti-brand-google" style="font-size:13px"></i> Aplikasi BDA
      </span>

      <h1 class="hero-headline">
        Kelola Drive &<br>Kalender <span class="line-accent">Lebih Cepat</span>
      </h1>

      <p class="hero-sub">
        Upload file ke Google Drive dan atur event Google Calendar langsung dari satu aplikasi. Tanpa berpindah tab.
      </p>

      <div class="hero-actions">
        <a href="{{ route('register') }}" class="btn-h btn-solid">
          <i class="ti ti-user-plus"></i> Daftar Sekarang
        </a>
        <a href="{{ route('login') }}" class="btn-h btn-border">
          <i class="ti ti-login"></i> Masuk Akun
        </a>
        <a href="{{ route('auth.google') }}" class="btn-h btn-google">
          <i class="ti ti-brand-google"></i> Login Google
        </a>
      </div>

      <div class="hero-meta">
        <span class="meta-item"><i class="ti ti-shield-check"></i> OAuth 2.0 aman</span>
        <span class="meta-item"><i class="ti ti-bolt"></i> Gratis dicoba</span>
        <span class="meta-item"><i class="ti ti-credit-card-off"></i> Tanpa kartu kredit</span>
      </div>
    </div>

    {{-- RIGHT --}}
    <div class="hero-right">
      <div class="main-card">
        <div class="card-header-band">
          <span class="dot dot-r"></span>
          <span class="dot dot-y"></span>
          <span class="dot dot-g"></span>
          <span class="card-header-label">bda-app.local</span>
        </div>
        <div class="card-body-inner">
          <div class="feat-row">
            <div class="feat-icon fi-blue">
              <i class="ti ti-brand-google-drive"></i>
            </div>
            <div class="feat-info">
              <div class="feat-title">Google Drive</div>
              <div class="feat-desc">Upload, preview, dan hapus file kapan saja.</div>
            </div>
            <span class="feat-tag tag-blue">Aktif</span>
          </div>

          <div class="feat-row">
            <div class="feat-icon fi-green">
              <i class="ti ti-calendar-event"></i>
            </div>
            <div class="feat-info">
              <div class="feat-title">Google Calendar</div>
              <div class="feat-desc">Kelola acara harian dari satu dashboard.</div>
            </div>
            <span class="feat-tag tag-green">Aktif</span>
          </div>
        </div>
      </div>

      <div class="stats-row">
        <div class="stat-mini">
          <div class="stat-mini-label">
            <i class="ti ti-file" style="color:#185fa5"></i> Drive Files
          </div>
          <div class="stat-mini-value">120+</div>
          <div class="stat-mini-sub">file tersimpan</div>
        </div>
        <div class="stat-mini">
          <div class="stat-mini-label">
            <i class="ti ti-calendar" style="color:#2e6e11"></i> Events
          </div>
          <div class="stat-mini-value">45</div>
          <div class="stat-mini-sub">acara terjadwal</div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection