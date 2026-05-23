<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BDA System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-color: #1f2937;
                --secondary-color: #3b82f6;
                --success-color: #10b981;
                --danger-color: #ef4444;
            }

            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f3f4f6;
            }

            .navbar {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .navbar-brand {
                font-size: 1.5rem;
                font-weight: 600;
                color: #fff !important;
            }

            .nav-link {
                color: rgba(255, 255, 255, 0.8) !important;
                transition: color 0.3s;
            }

            .nav-link:hover {
                color: #fff !important;
            }

            .btn-primary {
                background-color: var(--secondary-color);
                border-color: var(--secondary-color);
            }

            .btn-primary:hover {
                background-color: #2563eb;
                border-color: #2563eb;
            }

            .card {
                border: none;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
                border-radius: 8px;
                transition: transform 0.3s, box-shadow 0.3s;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            }

            .breadcrumb {
                background-color: transparent;
            }

            .alert {
                border: none;
                border-radius: 8px;
            }

            .modal-content {
                border-radius: 8px;
                border: none;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }

            .table {
                border-collapse: collapse;
            }

            .table thead th {
                background-color: var(--primary-color);
                color: white;
                border: none;
                font-weight: 600;
            }

            .table tbody tr:hover {
                background-color: #f9fafb;
            }

            .badge {
                padding: 0.5rem 0.75rem;
                border-radius: 0.375rem;
            }

            .dropdown-menu {
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .form-control, .form-select {
                border-radius: 6px;
                border: 1px solid #d1d5db;
            }

            .form-control:focus, .form-select:focus {
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .btn-icon {
                width: 36px;
                height: 36px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                transition: all 0.3s;
            }

            .btn-icon:hover {
                background-color: #e5e7eb;
            }

            .hero-section {
                background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(14,165,233,0.05));
                border-radius: 2rem;
            }

            .device-card {
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            }

            .badge.bg-light.text-primary {
                background-color: rgba(59, 130, 246, 0.12) !important;
                color: #2563eb !important;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <i class="bi bi-cloud-upload"></i> BDA System
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('google-drive.index') }}">
                                    <i class="bi bi-folder2"></i> Google Drive
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('google-calendar.index') }}">
                                    <i class="bi bi-calendar-event"></i> Calendar
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-danger p-0">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus"></i> Register
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('auth.google') }}">
                                    <i class="bi bi-google"></i> Login with Google
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container py-4">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="bg-dark text-white text-center py-3 mt-5">
            <div class="container">
                <p class="mb-0">&copy; 2026 BDA System. All rights reserved.</p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @yield('scripts')
    </body>
</html>
