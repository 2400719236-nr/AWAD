@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-md landing-nav sticky-top border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3 text-white"
                      style="width:36px;height:36px;background:linear-gradient(135deg,#818cf8,#4f46e5)"><i class="bi bi-mortarboard-fill"></i></span>
                SMS Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#roles">Who it's for</a></li>
                    <li class="nav-item"><a class="nav-link" href="#how">How it works</a></li>
                </ul>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-grid-1x2 me-1"></i> Go to dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <header class="hero py-5">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill bg-light text-primary mb-3 px-3 py-2">Academic year {{ \App\Models\CourseRegistration::CURRENT_ACADEMIC_YEAR }}</span>
                    <h1 class="display-5 fw-bold mb-3">Student Management System</h1>
                    <p class="lead mb-4">
                        One place to manage students, courses, lecturers and course registrations.
                        Simple for administrators, lecturers and students.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary fw-semibold">Open my dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg text-primary fw-semibold">Get started</a>
                        @endauth
                        <a href="#features" class="btn btn-outline-light btn-lg">Learn more</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-panel p-4">
                        <div class="row g-3 text-center">
                            @foreach ([
                                ['value' => $stats['students'], 'label' => 'Students', 'icon' => 'people'],
                                ['value' => $stats['courses'], 'label' => 'Courses', 'icon' => 'journal-bookmark'],
                                ['value' => $stats['lecturers'], 'label' => 'Lecturers', 'icon' => 'person-video3'],
                                ['value' => $stats['registrations'], 'label' => 'Registrations', 'icon' => 'card-checklist'],
                            ] as $stat)
                                <div class="col-6">
                                    <div class="bg-white bg-opacity-10 rounded-4 p-3">
                                        <i class="bi bi-{{ $stat['icon'] }} fs-3"></i>
                                        <div class="fs-2 fw-bold">{{ $stat['value'] }}</div>
                                        <div class="small text-white-50">{{ $stat['label'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Features --}}
    <section id="features" class="py-5">
        <div class="container py-lg-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Everything a department needs</h2>
                <p class="text-muted">Built with Laravel, Eloquent relationships and role-based access control.</p>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'people', 'color' => 'primary', 'title' => 'Student records', 'text' => 'Add, search, update and remove students with full validation.'],
                    ['icon' => 'journal-bookmark', 'color' => 'success', 'title' => 'Course catalogue', 'text' => 'Manage courses, credit units, capacity and the lecturer assigned to each one.'],
                    ['icon' => 'card-checklist', 'color' => 'warning', 'title' => 'Registrations', 'text' => 'Students register online. Duplicates and full courses are blocked automatically.'],
                    ['icon' => 'shield-lock', 'color' => 'info', 'title' => 'Secure access', 'text' => 'Roles and permissions decide who sees what. Nobody can open another user\'s records.'],
                ] as $feature)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 p-3">
                            <div class="card-body">
                                <span class="feature-icon bg-soft-{{ $feature['color'] }} mb-3"><i class="bi bi-{{ $feature['icon'] }}"></i></span>
                                <h5 class="fw-semibold">{{ $feature['title'] }}</h5>
                                <p class="text-muted mb-0">{{ $feature['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Roles --}}
    <section id="roles" class="py-5" style="background: var(--sms-bg)">
        <div class="container py-lg-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Who it's for</h2>
                <p class="text-muted">Each role gets its own dashboard.</p>
            </div>
            <div class="row g-4">
                @foreach ([
                    ['icon' => 'shield-check', 'title' => 'Administrators', 'items' => ['Manage students and courses', 'Assign lecturers to courses', 'Register or remove students', 'Give students login access']],
                    ['icon' => 'person-video3', 'title' => 'Lecturers', 'items' => ['See assigned courses', 'View each class list', 'Track how full each course is']],
                    ['icon' => 'backpack', 'title' => 'Students', 'items' => ['Browse available courses', 'Register for courses online', 'View and drop their own registrations']],
                ] as $role)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body p-4">
                                <span class="feature-icon bg-soft-primary mb-3"><i class="bi bi-{{ $role['icon'] }}"></i></span>
                                <h5 class="fw-semibold">{{ $role['title'] }}</h5>
                                <ul class="list-unstyled text-muted mb-0">
                                    @foreach ($role['items'] as $item)
                                        <li class="mb-1"><i class="bi bi-check2 text-success me-2"></i>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how" class="py-5">
        <div class="container py-lg-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <h2 class="fw-bold">How it works</h2>
                    <p class="text-muted">Three steps from login to a confirmed registration.</p>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary mt-2">Login now</a>
                    @endguest
                </div>
                <div class="col-lg-7">
                    @foreach ([
                        ['Log in', 'Use the account given to you by the administrator.'],
                        ['Open your dashboard', 'See your courses, class lists or system totals depending on your role.'],
                        ['Take action', 'Register for a course, manage records or check who is in your class.'],
                    ] as $i => [$title, $text])
                        <div class="d-flex gap-3 mb-3">
                            <span class="avatar flex-shrink-0">{{ $i + 1 }}</span>
                            <div>
                                <div class="fw-semibold">{{ $title }}</div>
                                <div class="text-muted">{{ $text }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <footer class="landing-footer py-4">
        <div class="container d-flex flex-wrap justify-content-between small">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
            <span>BUC3131 Advanced Web Application Development &middot; Makerere University Business School</span>
        </div>
    </footer>
@endsection
