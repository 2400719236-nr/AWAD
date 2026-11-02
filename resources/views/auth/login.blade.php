@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="container-fluid">
        <div class="row auth-wrapper">
            <div class="col-lg-5 auth-side d-none d-lg-flex flex-column justify-content-between p-5">
                <a href="{{ route('home') }}" class="text-white text-decoration-none fw-bold fs-5">
                    <i class="bi bi-mortarboard-fill me-2"></i>SMS Portal
                </a>
                <div>
                    <h2 class="fw-bold mb-3">Welcome back!</h2>
                    <p class="text-white-50 mb-4">Sign in to manage students, courses and registrations.</p>
                    <ul class="list-unstyled text-white-50 mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle text-white me-2"></i>Role-based dashboards</li>
                        <li class="mb-2"><i class="bi bi-check-circle text-white me-2"></i>Online course registration</li>
                        <li><i class="bi bi-check-circle text-white me-2"></i>Secure access to your own records</li>
                    </ul>
                </div>
                <small class="text-white-50">&copy; {{ date('Y') }} {{ config('app.name') }}</small>
            </div>

            <div class="col-lg-7 d-flex align-items-center justify-content-center p-4" style="background: var(--sms-bg)">
                <div class="w-100" style="max-width: 420px">
                    <a href="{{ route('home') }}" class="text-decoration-none small d-inline-block mb-4"><i class="bi bi-arrow-left"></i> Back to home</a>

                    @include('partials.alerts')

                    <div class="card">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h4 fw-bold mb-1">Sign in</h1>
                            <p class="text-muted mb-4">Enter your email and password.</p>

                            <form method="POST" action="{{ route('login') }}" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                                               class="form-control @error('email') is-invalid @enderror" placeholder="you@sms.test" required autofocus>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                                        <input type="password" id="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror" required>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                    <label for="remember" class="form-check-label">Remember me</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                            </form>
                        </div>
                    </div>

                    @if (app()->environment('local'))
                        <div class="small text-muted mt-3 text-center">
                            Demo: <code>admin@sms.test</code>, <code>grace.namutebi@sms.test</code> or <code>aisha.nakato@sms.test</code>, password <code>password</code>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
