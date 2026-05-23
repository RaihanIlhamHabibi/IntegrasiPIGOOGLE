@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; }

  .dash {
    font-family: 'Sora', sans-serif;
    min-height: 100vh;
    background: #f7f6f3;
    padding: 2rem 1.5rem 3rem;
    color: #1a1916;
  }

  /* ── TOPBAR ── */
  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.5rem;
    animation: fadeUp .5s ease both;
  }
  .topbar-left { display: flex; flex-direction: column; gap: 2px; }
  .topbar-eyebrow {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #888880;
  }
  .topbar-title {
    font-size: 26px;
    font-weight: 600;
    color: #1a1916;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .topbar-title i { font-size: 22px; color: #b0aca3; }
  .exit-btn {
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    font-weight: 500;
    padding: 9px 20px;
    border-radius: 100px;
    border: 1px solid #dddbd5;
    background: #fff;
    color: #888880;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .exit-btn:hover {
    background: #fff0f0;
    color: #c0392b;
    border-color: #f5c6c4;
  }

  /* ── ALERT ── */
  .alert-google {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fffbeb;
    border: 1px solid #f5e08a;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 2rem;
    font-size: 13.5px;
    color: #7a6200;
    animation: fadeUp .5s .05s ease both;
  }
  .alert-google i { font-size: 20px; color: #d4a017; flex-shrink: 0; }
  .alert-google a { color: #7a6200; font-weight: 600; text-decoration: underline; text-underline-offset: 2px; }

  /* ── STATS ── */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 2rem;
  }
  .stat-card {
    background: #fff;
    border: 1px solid #e8e5de;
    border-radius: 16px;
    padding: 1.25rem 1.4rem;
    position: relative;
    overflow: hidden;
    animation: fadeUp .5s ease both;
  }
  .stat-card:nth-child(1) { animation-delay: .1s; }
  .stat-card:nth-child(2) { animation-delay: .15s; }
  .stat-card:nth-child(3) { animation-delay: .2s; }
  .stat-bg-icon {
    position: absolute;
    right: 14px;
    top: 14px;
    font-size: 28px;
    opacity: .07;
    color: #1a1916;
  }
  .stat-label {
    font-size: 11.5px;
    font-weight: 500;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #999891;
    margin-bottom: 10px;
  }
  .stat-value {
    font-size: 32px;
    font-weight: 600;
    line-height: 1;
    color: #1a1916;
    margin-bottom: 10px;
  }
  .stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 500;
    padding: 4px 12px;
    border-radius: 100px;
  }
  .pill-success { background: #eaf7f0; color: #1a7a46; }
  .pill-muted   { background: #f3f2ef; color: #999891; border: 1px solid #e8e5de; }
  .pill-danger  { background: #fff0f0; color: #c0392b; }

  /* ── SERVICE CARDS ── */
  .section-label {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #b0aca3;
    margin-bottom: 12px;
    animation: fadeUp .5s .25s ease both;
  }
  .services-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 2rem;
  }
  .service-card {
    background: #fff;
    border: 1px solid #e8e5de;
    border-radius: 18px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 14px;
    text-decoration: none;
    color: inherit;
    transition: border-color .2s, transform .2s, box-shadow .2s;
    animation: fadeUp .5s ease both;
  }
  .service-card:nth-child(1) { animation-delay: .3s; }
  .service-card:nth-child(2) { animation-delay: .35s; }
  .service-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0,0,0,.06);
    border-color: #ccc9c0;
    text-decoration: none;
    color: inherit;
  }
  .service-header { display: flex; align-items: center; gap: 12px; }
  .service-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }
  .icon-drive { background: #e8f1fb; color: #1a5fa5; }
  .icon-cal   { background: #eaf3de; color: #2e6e11; }
  .service-name { font-size: 15px; font-weight: 600; color: #1a1916; }
  .service-desc { font-size: 13px; color: #999891; line-height: 1.6; flex: 1; }
  .service-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 500;
    padding-top: 10px;
    border-top: 1px solid #f0ede7;
  }
  .footer-drive { color: #1a5fa5; }
  .footer-cal   { color: #2e6e11; }
  .service-footer i { font-size: 16px; transition: transform .2s; }
  .service-card:hover .service-footer i { transform: translateX(4px); }

  /* ── QUICK ACTIONS ── */
  .quick-grid {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    animation: fadeUp .5s .4s ease both;
  }
  .quick-btn {
    font-family: 'Sora', sans-serif;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12.5px;
    font-weight: 500;
    padding: 9px 18px;
    border-radius: 100px;
    border: 1px solid #e8e5de;
    background: #fff;
    color: #555450;
    cursor: pointer;
    transition: all .15s;
    text-decoration: none;
  }
  .quick-btn i { font-size: 15px; }
  .quick-btn:hover {
    background: #f3f2ef;
    border-color: #ccc9c0;
    color: #1a1916;
    text-decoration: none;
  }
  .quick-btn-primary {
    background: #1a1916;
    color: #f7f6f3;
    border-color: #1a1916;
  }
  .quick-btn-primary:hover {
    background: #333028;
    color: #fff;
    border-color: #333028;
  }

  /* ── DIVIDER ── */
  .divider { height: 1px; background: #e8e5de; margin: 1.75rem 0; }

  /* ── ANIMATION ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 640px) {
    .stats-grid { grid-template-columns: 1fr 1fr; }
    .stats-grid .stat-card:last-child { grid-column: span 2; }
    .services-grid { grid-template-columns: 1fr; }
    .stat-value { font-size: 26px; }
  }
</style>

<div class="dash">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="topbar-left">
      <span class="topbar-eyebrow">Selamat datang kembali</span>
      <div class="topbar-title">
        Dashboard
        <i class="ti ti-layout-dashboard"></i>
      </div>
    </div>
    <form action="{{ route('logout') }}" method="POST" style="margin:0">
      @csrf
      <button type="submit" class="exit-btn">
        <i class="ti ti-logout" style="font-size:15px"></i> Exit
      </button>
    </form>
  </div>

  {{-- ALERT --}}
  @if(!($hasGoogle ?? false))
  <div class="alert-google">
    <i class="ti ti-alert-triangle"></i>
    <span>
      Akun Google belum terhubung. &nbsp;
      <a href="{{ route('auth.google') }}">Hubungkan sekarang →</a>
    </span>
  </div>
  @endif

  {{-- STATS --}}
  <div class="stats-grid">
    <div class="stat-card">
      <i class="ti ti-file stat-bg-icon"></i>
      <div class="stat-label">File di Drive</div>
      <div class="stat-value">{{ $driveCount ?? '—' }}</div>
      @if($hasGoogle ?? false)
        <span class="stat-pill pill-success"><i class="ti ti-cloud" style="font-size:12px"></i> Tersimpan</span>
      @else
        <span class="stat-pill pill-muted"><i class="ti ti-cloud-off" style="font-size:12px"></i> Belum terhubung</span>
      @endif
    </div>

    <div class="stat-card">
      <i class="ti ti-calendar stat-bg-icon"></i>
      <div class="stat-label">Acara Kalender</div>
      <div class="stat-value">{{ $eventCount ?? '—' }}</div>
      @if($hasGoogle ?? false)
        <span class="stat-pill pill-success"><i class="ti ti-check" style="font-size:12px"></i> Aktif</span>
      @else
        <span class="stat-pill pill-muted"><i class="ti ti-clock-off" style="font-size:12px"></i> Belum terhubung</span>
      @endif
    </div>

    <div class="stat-card">
      <i class="ti ti-brand-google stat-bg-icon"></i>
      <div class="stat-label">Status Google</div>
      <div class="stat-value" style="font-size:18px; padding-top:6px; margin-bottom:12px">
        @if($hasGoogle ?? false) Terhubung @else Tidak aktif @endif
      </div>
      @if($hasGoogle ?? false)
        <span class="stat-pill pill-success"><i class="ti ti-circle-check" style="font-size:12px"></i> Online</span>
      @else
        <span class="stat-pill pill-danger"><i class="ti ti-circle-x" style="font-size:12px"></i> Offline</span>
      @endif
    </div>
  </div>

  {{-- SERVICES --}}
  <div class="section-label">Layanan</div>
  <div class="services-grid">
    <a href="{{ route('google-drive.index') }}" class="service-card">
      <div class="service-header">
        <div class="service-icon icon-drive">
          <i class="ti ti-brand-google-drive"></i>
        </div>
        <span class="service-name">Google Drive</span>
      </div>
      <p class="service-desc">
        Upload dan kelola file langsung ke Google Drive kamu. Akses kapan saja, dari mana saja.
      </p>
      <div class="service-footer footer-drive">
        Buka Drive
        <i class="ti ti-arrow-right"></i>
      </div>
    </a>

    <a href="{{ route('google-calendar.index') }}" class="service-card">
      <div class="service-header">
        <div class="service-icon icon-cal">
          <i class="ti ti-calendar-event"></i>
        </div>
        <span class="service-name">Google Calendar</span>
      </div>
      <p class="service-desc">
        Buat dan kelola acara kalender langsung dari aplikasi tanpa perlu buka Google Calendar.
      </p>
      <div class="service-footer footer-cal">
        Buka Kalender
        <i class="ti ti-arrow-right"></i>
      </div>
    </a>
  </div>

  {{-- QUICK ACTIONS --}}
  <div class="divider"></div>
  <div class="section-label">Aksi cepat</div>
  <div class="quick-grid">
    <a href="{{ route('google-drive.index') }}" class="quick-btn quick-btn-primary">
      <i class="ti ti-upload"></i> Upload file
    </a>
    <a href="{{ route('google-calendar.create') }}" class="quick-btn">
      <i class="ti ti-plus"></i> Buat acara
    </a>
    @if(!($hasGoogle ?? false))
    <a href="{{ route('auth.google') }}" class="quick-btn">
      <i class="ti ti-brand-google"></i> Hubungkan Google
    </a>
    @else
    <a href="{{ route('auth.google') }}" class="quick-btn">
      <i class="ti ti-refresh"></i> Reconnect Google
    </a>
    @endif
  </div>

</div>
@endsection