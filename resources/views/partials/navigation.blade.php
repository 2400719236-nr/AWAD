<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
            <i class="bi bi-mortarboard-fill"></i> {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @can('view students')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Students</a>
                        </li>
                    @endcan
                    @can('view courses')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                                {{ auth()->user()->hasRole('lecturer') ? 'My Courses' : 'Courses' }}
                            </a>
                        </li>
                    @endcan
                    @canany(['view all registrations', 'view own registrations'])
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                                {{ auth()->user()->can('view all registrations') ? 'Registrations' : 'My Registrations' }}
                            </a>
                        </li>
                    @endcanany
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            <span class="badge bg-light text-primary text-capitalize ms-1">{{ auth()->user()->getRoleNames()->first() }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
