<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ingest') }} - Track Your Health</title>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Premium Custom Styles -->
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(21, 29, 48, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --primary-accent: #6366f1;
            --primary-accent-glow: rgba(99, 102, 241, 0.15);
            --poop-accent: #d97706;
            --poop-accent-glow: rgba(217, 119, 6, 0.15);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success-color: #10b981;
            --danger-color: #ef4444;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 10% 20%, rgba(99, 102, 241, 0.1) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(217, 119, 6, 0.08) 0px, transparent 50%);
            color: var(--text-main);
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.4);
        }

        .glass-navbar {
            background: rgba(11, 15, 25, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .nav-link-custom {
            color: var(--text-muted);
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--text-main);
            background: var(--primary-accent-glow);
        }

        .btn-primary-custom {
            background-color: var(--primary-accent);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            color: #fff;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.4);
        }

        .btn-primary-custom:hover {
            background-color: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(99, 102, 241, 0.6);
            color: #fff;
        }

        .btn-poop-custom {
            background-color: var(--poop-accent);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            color: #fff;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px 0 rgba(217, 119, 6, 0.4);
        }

        .btn-poop-custom:hover {
            background-color: #b45309;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px 0 rgba(217, 119, 6, 0.6);
            color: #fff;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            color: var(--text-main) !important;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 3px var(--primary-accent-glow);
            outline: none;
        }

        .form-label-custom {
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
        }

        /* Customize scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .toast-container-custom {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1050;
        }

        .alert-custom {
            border-radius: 12px;
            border: 1px solid var(--card-border);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            color: var(--text-main);
        }
    </style>
    @yield('styles')
</head>
<body class="d-flex flex-column min-h-screen">

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg glass-navbar py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}" style="font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; color: var(--text-main);">
                <i class="bi bi-heart-pulse-fill text-indigo" style="color: var(--primary-accent);"></i>
                <span>INGEST</span>
            </a>
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1 mt-2 mt-lg-0">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('history') ? 'active' : '' }}" href="{{ route('history') }}">
                            <i class="bi bi-calendar2-week-fill"></i> Log History
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted d-none d-md-inline" style="font-size: 0.9rem;">
                        <i class="bi bi-person-fill-check"></i> {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm border-0 bg-transparent nav-link-custom py-2 px-3">
                            <i class="bi bi-box-arrow-right"></i> Log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 py-5">
        <div class="container">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-custom alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert" style="border-left: 4px solid var(--success-color);">
                    <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-custom alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert" style="border-left: 4px solid var(--danger-color);">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                    <div>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-custom alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid var(--danger-color);">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                        <strong class="text-danger">Please fix the following validation errors:</strong>
                        <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-auto text-center border-top" style="border-color: var(--card-border) !important; background: rgba(11, 15, 25, 0.4);">
        <div class="container text-muted">
            <p class="mb-0" style="font-size: 0.9rem;">&copy; {{ date('Y') }} Ingest Tracker. Stay healthy and consistent.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
