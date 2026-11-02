{{-- Sidebar: links are shown according to the user's permissions --}}
@php($user = auth()->user())

<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <a href="{{ route('dashboard') }}" class="brand d-flex align-items-center gap-2">
                <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                <span>SMS Portal</span>
            </a>
            <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
        </div>

        <nav class="flex-grow-1 overflow-auto">
            <div class="nav-section">Main</div>
            <ul class="nav flex-column gap-1">
                <li>
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>
                @if ($user->student)
                    <li>
                        <a class="nav-link {{ request()->routeIs('students.show') ? 'active' : '' }}" href="{{ route('students.show', $user->student) }}">
                            <i class="bi bi-person-badge"></i> My Profile
                        </a>
                    </li>
                @endif
            </ul>

            <div class="nav-section">{{ $user->hasRole('admin') ? 'Management' : 'Academics' }}</div>
            <ul class="nav flex-column gap-1">
                @can('view students')
                    <li>
                        <a class="nav-link {{ request()->routeIs('students.index', 'students.create', 'students.edit') || ($user->hasRole('admin') && request()->routeIs('students.show')) ? 'active' : '' }}"
                           href="{{ route('students.index') }}">
                            <i class="bi bi-people"></i> Students
                        </a>
                    </li>
                @endcan
                @can('view courses')
                    <li>
                        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                            <i class="bi bi-journal-bookmark"></i> {{ $user->hasRole('lecturer') ? 'My Courses' : 'Courses' }}
                        </a>
                    </li>
                @endcan
                @canany(['view all registrations', 'view own registrations'])
                    <li>
                        <a class="nav-link {{ request()->routeIs('registrations.*') ? 'active' : '' }}" href="{{ route('registrations.index') }}">
                            <i class="bi bi-card-checklist"></i> {{ $user->can('view all registrations') ? 'Registrations' : 'My Registrations' }}
                        </a>
                    </li>
                @endcanany
            </ul>
        </nav>

        <div class="user-box p-3 mt-3 d-flex align-items-center gap-2">
            <span class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            <div class="flex-grow-1 lh-sm overflow-hidden">
                <div class="text-white small fw-semibold text-truncate">{{ $user->name }}</div>
                <div class="small text-capitalize">{{ $user->getRoleNames()->first() }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-white" title="Logout"><i class="bi bi-box-arrow-right"></i></button>
            </form>
        </div>
    </div>
</aside>
