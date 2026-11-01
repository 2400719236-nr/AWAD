<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    {{-- Bootstrap is served from public/vendor so the app works without internet --}}
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    {{-- Navigation (sidebar) --}}
    @auth
        @include('partials.navigation')
    @endauth

    <div class="app-main">
        {{-- Header --}}
        <header class="topbar px-3 px-lg-4 py-3 d-flex align-items-center gap-3">
            @auth
                <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="bi bi-list"></i>
                </button>
            @endauth
            <div class="flex-grow-1">
                <div class="small text-muted">{{ now()->format('l, d F Y') }}</div>
                <div class="fw-semibold">@yield('title', 'Dashboard')</div>
            </div>

            @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-reset" data-bs-toggle="dropdown">
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="d-none d-sm-block lh-sm">
                            <span class="d-block fw-semibold small">{{ auth()->user()->name }}</span>
                            <span class="d-block text-muted small text-capitalize">{{ auth()->user()->getRoleNames()->first() }}</span>
                        </span>
                        <i class="bi bi-chevron-down small text-muted"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-house me-2"></i>Home page</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
            @endauth
        </header>

        {{-- Content --}}
        <main class="flex-grow-1 p-3 p-lg-4">
            @include('partials.alerts')

            @hasSection('header')
                <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-4">
                    <h1 class="h4 mb-0 fw-bold">@yield('header')</h1>
                    <div class="d-flex gap-2">@yield('actions')</div>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="px-4 py-3 small text-muted d-flex flex-wrap justify-content-between border-top bg-white">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
            <span>BUC3131 Advanced Web Application Development</span>
        </footer>
    </div>
</div>

<script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
