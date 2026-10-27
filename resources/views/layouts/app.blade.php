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
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; background: #f5f7fb; }
        main { flex: 1; }
    </style>
</head>
<body>
    {{-- Header --}}
    <header>
        @include('partials.navigation')
    </header>

    {{-- Content --}}
    <main class="container py-4">
        @include('partials.alerts')

        @hasSection('header')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">@yield('header')</h1>
                <div>@yield('actions')</div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-dark text-white-50 py-3 mt-auto">
        <div class="container d-flex justify-content-between small">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
            <span>BUC3131 Advanced Web Application Development</span>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
