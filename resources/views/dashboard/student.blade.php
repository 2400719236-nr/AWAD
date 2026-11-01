@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
    @if ($student)
        <div class="card welcome-card mb-4">
            <div class="card-body p-4 d-flex flex-wrap align-items-center gap-3">
                <span class="avatar avatar-lg bg-white text-primary">{{ strtoupper(substr($student->first_name, 0, 1).substr($student->last_name, 0, 1)) }}</span>
                <div class="flex-grow-1">
                    <h1 class="h4 fw-bold mb-1">Welcome, {{ $student->first_name }}!</h1>
                    <p class="text-white-75 mb-0">{{ $student->registration_number }} &middot; {{ $student->programme }}, Year {{ $student->year_of_study }}</p>
                </div>
                <a href="{{ route('students.show', $student) }}" class="btn btn-light text-primary fw-semibold"><i class="bi bi-person"></i> My profile</a>
            </div>
        </div>
    @else
        <div class="alert alert-warning">Your account is not linked to a student record yet. Please contact the administrator.</div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-sm-4">@include('partials.stat-card', ['label' => 'Registered courses', 'value' => $courses->count(), 'icon' => 'journal-check', 'color' => 'primary', 'url' => route('registrations.index')])</div>
        <div class="col-sm-4">@include('partials.stat-card', ['label' => 'Total credit units', 'value' => $totalCredits, 'icon' => 'award', 'color' => 'success'])</div>
        <div class="col-sm-4">@include('partials.stat-card', ['label' => 'Available to join', 'value' => $available->count(), 'icon' => 'plus-square', 'color' => 'warning'])</div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between py-3">
                    <span>My registered courses ({{ $courses->count() }})</span>
                    <a href="{{ route('registrations.index') }}" class="small">Manage</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($courses as $course)
                        <li class="list-group-item py-3 d-flex align-items-center gap-3">
                            <span class="stat-icon bg-soft-primary" style="width:40px;height:40px;border-radius:10px"><i class="bi bi-journal-check"></i></span>
                            <div class="flex-grow-1 lh-sm">
                                <div><span class="fw-semibold">{{ $course->code }}</span> {{ $course->title }}</div>
                                <div class="small text-muted">{{ $course->lecturer?->name ?? 'Lecturer not assigned' }}</div>
                            </div>
                            <span class="badge bg-light text-dark">{{ $course->credit_units }} CU</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted py-4 text-center">You have not registered for any courses yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white fw-semibold py-3">Available courses</div>
                <ul class="list-group list-group-flush">
                    @forelse ($available as $course)
                        <li class="list-group-item py-3 d-flex justify-content-between align-items-center gap-3">
                            <div class="lh-sm">
                                <div><span class="fw-semibold">{{ $course->code }}</span> {{ $course->title }}</div>
                                <div class="small text-muted">{{ $course->lecturer?->name ?? 'Lecturer not assigned' }} &middot; {{ $course->students_count }}/{{ $course->capacity }} registered</div>
                            </div>
                            @can('create', App\Models\CourseRegistration::class)
                                @if ($course->students_count < $course->capacity)
                                    <form method="POST" action="{{ route('registrations.store') }}">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button class="btn btn-sm btn-primary text-nowrap"><i class="bi bi-plus-lg"></i> Register</button>
                                    </form>
                                @else
                                    <span class="badge bg-danger">Full</span>
                                @endif
                            @endcan
                        </li>
                    @empty
                        <li class="list-group-item text-muted py-4 text-center">No other courses are available.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
